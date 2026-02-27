<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CapitalGainsCalculatorService;
use App\Services\HighLevelService;
use App\Models\Consultant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ToolsController extends Controller
{
    // IDs de Fallback (Vendedores/Responsáveis)
    protected const DEFAULT_CRM_ID = 'vB0OLIFiB7Edovavz2A9'; // Hugo Gaito
    protected const MARGARIDA_CRM_ID = '9RqDPhXN28GpNeSN3kWW'; // Margarida Lopes

    protected $calculator;
    protected $crmService;

    public function __construct(CapitalGainsCalculatorService $calculator, HighLevelService $crmService)
    {
        $this->calculator = $calculator;
        $this->crmService = $crmService;
    }

    private function getContext($domain)
    {
        if (!$domain) return null;
        $cleanDomain = preg_replace('/^www\./', '', $domain);
        return Consultant::where('domain', $cleanDomain)
            ->orWhere('lp_slug', $cleanDomain)
            ->first();
    }

    // Views
    public function showGainsSimulator() { return view('tools.gains'); }
    public function showCreditSimulator() { return view('tools.credit'); }
    public function showImtSimulator() { return view('tools.imt'); }

    // =========================================================================
    // NOVA ROTA: VISUALIZAR LOG (Abre o JSON na View Bonita)
    // =========================================================================
    public function showLeadLog($filename)
    {
        // Segurança: só aceita .json para evitar leitura de arquivos indevidos
        if (!Str::endsWith($filename, '.json')) abort(404);
        
        $path = 'leads/' . $filename;
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'Ficha não encontrada ou expirada.');
        }

        try {
            $json = Storage::disk('public')->get($path);
            $data = json_decode($json, true);
            // Retorna a View que criamos (resources/views/tools/lead-log.blade.php)
            return view('tools.lead-log', compact('data'));
        } catch (\Exception $e) {
            abort(500, 'Erro ao processar a ficha.');
        }
    }

    // =========================================================================
    // MAIS-VALIAS
    // =========================================================================
    public function calculateGainsOnly(Request $request)
    {
        $validated = $this->validateGainsRequest($request, false);
        $results = $this->calculator->calculate($validated);
        return response()->json($results);
    }

    public function calculateGains(Request $request, $domain = null)
    {
        $consultant = $this->getContext($domain);
        $validated = $this->validateGainsRequest($request, true);
        $results = $this->calculator->calculate($validated);

        if ($request->filled('lead_email')) {
            // 1. Envio do PDF (Email para o Cliente)
            $this->sendEmailWithPdf(
                $validated['lead_email'], 
                $validated['lead_name'], 
                'Simulação de Mais-Valias', 
                'pdfs.simulation', 
                ['data' => $validated, 'results' => $results]
            );

            // 2. Montagem dos Dados para a View (CRM)
            $info = [
                'TIPO' => 'SIMULAÇÃO MAIS-VALIAS',
                'Compra' => number_format((float)$validated['acquisition_value'], 2, ',', '.') . "€ (" . $validated['acquisition_year'] . ")",
                'Venda' => number_format((float)$validated['sale_value'], 2, ',', '.') . "€ (" . $validated['sale_year'] . ")",
                'Despesas' => number_format((float)$validated['expenses_total'], 2, ',', '.') . "€",
                'Venda ao Estado' => $validated['sold_to_state'] ?? 'Não',
                'Reinvestimento' => ($validated['reinvest_intention'] ?? 'Não') === 'Sim' ? number_format((float)($validated['reinvestment_amount'] ?? 0), 2, ',', '.') . "€" : 'Não',
                'RESULTADO_MAIS_VALIA' => ($results['gross_gain_fmt'] ?? '0,00') . "€",
                'IMPOSTO_ESTIMADO' => ($results['estimated_tax_fmt'] ?? '0,00') . "€"
            ];

            // 3. Processamento Centralizado
            $this->processCrmSubmission(
                $validated, 
                $info, 
                'Simulador Mais-Valias', 
                'seller', 
                $consultant,
                $validated['sale_value']
            );
        }

        return response()->json(['success' => true, 'results' => $results]);
    }

    // =========================================================================
    // CRÉDITO HABITAÇÃO
    // =========================================================================
    public function sendCreditSimulation(Request $request, $domain = null)
    {
        $consultant = $this->getContext($domain);
        $data = $request->validate([
            'propertyValue'  => 'required|numeric', 
            'loanAmount'     => 'required|numeric', 
            'years'          => 'required|integer',
            'tan'            => 'required|numeric', 
            'monthlyPayment' => 'required|numeric', 
            'mtic'           => 'required|numeric',
            'lead_name'      => 'required|string|max:255', 
            'lead_email'     => 'required|email|max:255',
            'lead_phone'     => 'required|string|max:20'
        ]);

        // Gera PDF e Envia Email (Cliente)
        $pdfContent = null;
        try {
            $viewData = ['title' => 'Relatório Crédito Habitação', 'data' => $data, 'date' => date('d/m/Y')];
            $pdfContent = Pdf::loadView('pdfs.simple-report', $viewData)->output();
            
            if ($pdfContent) {
                Mail::send('emails.simulation-lead', ['name' => $data['lead_name'], 'simulationType' => 'Simulação Crédito Habitação'], function ($message) use ($data, $pdfContent) {
                    $message->from(config('mail.from.address'), config('mail.from.name'))
                        ->to($data['lead_email'])
                        ->subject('Simulação Crédito Habitação - Resultado')
                        ->attachData($pdfContent, 'simulacao.pdf');
                });
            }
        } catch (\Throwable $e) {
            Log::error('Erro Processo Crédito: ' . $e->getMessage());
        }

        // Dados para a View (CRM)
        $info = [
            'TIPO' => 'SIMULAÇÃO CRÉDITO',
            'Imóvel' => $data['propertyValue'] . '€',
            'Empréstimo' => $data['loanAmount'] . '€',
            'Prazo' => $data['years'] . ' anos',
            'Mensalidade' => $data['monthlyPayment'] . '€',
            'MTIC' => $data['mtic'] . '€'
        ];

        $this->processCrmSubmission(
            [
                'lead_name' => $data['lead_name'],
                'lead_email' => $data['lead_email'],
                'lead_phone' => $data['lead_phone']
            ], 
            $info, 
            'Simulador Crédito', 
            'credit', 
            $consultant,
            $data['propertyValue']
        );

        return response()->json(['success' => true]);
    }

    // =========================================================================
    // IMT
    // =========================================================================
    public function sendImtSimulation(Request $request, $domain = null)
    {
        $consultant = $this->getContext($domain);
        $data = $request->validate([
            'propertyValue' => 'required', 
            'location' => 'required', 
            'purpose' => 'required',
            'finalIMT' => 'required', 
            'finalStamp' => 'required', 
            'totalPayable' => 'required',
            'lead_name' => 'required|string', 
            'lead_email' => 'required|email',
            'lead_phone' => 'required|string|max:20'
        ]);

        $this->sendEmailWithPdf($data['lead_email'], $data['lead_name'], 'Simulação IMT e Selo', 'pdfs.simple-report', ['title' => 'Relatório IMT', 'data' => $data]);

        $info = [
            'TIPO' => 'SIMULAÇÃO IMT',
            'Valor Imóvel' => $data['propertyValue'] . '€',
            'Localização' => $data['location'],
            'Finalidade' => $data['purpose'],
            'IMT' => $data['finalIMT'] . '€',
            'Selo' => $data['finalStamp'] . '€',
            'TOTAL IMPOSTOS' => $data['totalPayable'] . '€'
        ];

        $cleanValue = (float) str_replace(['.', ','], ['', '.'], $data['propertyValue']);

        $this->processCrmSubmission(
            [
                'lead_name' => $data['lead_name'],
                'lead_email' => $data['lead_email'],
                'lead_phone' => $data['lead_phone']
            ],
            $info, 
            'Simulador IMT', 
            'buyer', 
            $consultant,
            $cleanValue
        );

        return response()->json(['success' => true]);
    }

    // =========================================================================
    // CONTACTO GERAL
    // =========================================================================
    public function sendContact(Request $request, $domain = null)
    {
        $consultant = $this->getContext($domain);
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'nullable|string', 
            'property_type' => 'nullable|string',
            'year' => 'nullable|integer',
            'area' => 'nullable|numeric',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'garages' => 'nullable|integer',
            'parking_type' => 'nullable|string',
            'features' => 'nullable|array',
            'features_text' => 'nullable|string', 
            'condition' => 'nullable|string',
            'address' => 'nullable|string',
            'is_owner' => 'nullable|string',
            'estimated_value' => 'nullable|numeric',
            'property_code' => 'nullable|string',
        ];
        
        if ($request->input('subject') === 'avaliacao') {
            $rules['phone'] = 'required';
        }

        $data = $request->validate($rules);

        if (empty($data['subject'])) $data['subject'] = 'Novo Contacto Geral';

        // 1. Envio Email Admin/Consultor
        try {
            // CORREÇÃO: Usa o email configurado no .env (MAIL_FROM_ADDRESS) como admin
            $adminEmail = config('mail.from.address'); 

            // Se existir consultor, ele é o principal, senão é o admin
            $primaryEmail = $consultant ? $consultant->email : $adminEmail;

            Mail::send('emails.contact-lead', ['data' => $data], function ($message) use ($primaryEmail, $adminEmail, $data, $consultant) {
                $message->from(config('mail.from.address'), config('mail.from.name'));
                $message->to($primaryEmail);
                
                // Se foi para o consultor, manda BCC (cópia oculta) para o admin
                if ($consultant) {
                    $message->bcc($adminEmail);
                    $message->subject("[House Team - {$consultant->name}] " . $data['subject']);
                } else {
                    $message->subject('[House Team] ' . $data['subject']);
                }
            });
        } catch (\Throwable $e) {
            Log::error('Erro Email Contacto: ' . $e->getMessage());
        }

        // 2. Prepara dados para a View do CRM
        $info = [
            'TIPO' => 'FORMULÁRIO CONTACTO',
            'Assunto' => $data['subject'],
            'Mensagem' => $data['message'] ?? '-',
            'Proprietário' => $data['is_owner'] ?? 'Não informado'
        ];

        // Adiciona detalhes extras se existirem
        $extraFields = ['property_type' => 'Tipo', 'year' => 'Ano', 'area' => 'Área', 'bedrooms' => 'T', 'estimated_value' => 'Valor Est.', 'property_code' => 'Ref. Imóvel'];
        foreach($extraFields as $field => $label) {
            if(!empty($data[$field])) $info[$label] = $data[$field];
        }

        // 3. Determina Pipeline
        $subjectLower = mb_strtolower($data['subject']);
        $pipelineType = 'lead';
        if (str_contains($subjectLower, 'avalia')) $pipelineType = 'valuation';
        elseif (str_contains($subjectLower, 'vender')) $pipelineType = 'seller';
        elseif (str_contains($subjectLower, 'recrutamento')) $pipelineType = 'recruitment';
        elseif (str_contains($subjectLower, 'comprar')) $pipelineType = 'buyer';

        // 4. Processa
        $this->processCrmSubmission(
            [
                'lead_name' => $data['name'],
                'lead_email' => $data['email'],
                'lead_phone' => $data['phone'] ?? null
            ], 
            $info, 
            'Formulário Site', 
            $pipelineType, 
            $consultant,
            $data['estimated_value'] ?? null
        );

        return back()->with('success', 'Mensagem enviada com sucesso!');
    }

    // =========================================================================
    // CONTACTO FRAÇÃO (EMPREENDIMENTOS)
    // =========================================================================
    public function fractionContact(Request $request, $domain = null)
    {
        $consultant = $this->getContext($domain);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'development_title' => 'required|string',
            'fraction_ref' => 'required|string',
            'remax_id' => 'nullable|string',
        ]);

        $subject = 'Interesse na Fração ' . $data['fraction_ref'] . ' - ' . $data['development_title'];

        // 1. Envio Email Admin/Consultor
        try {
            $adminEmail = config('mail.from.address'); 
            $primaryEmail = $consultant ? $consultant->email : $adminEmail;

            // Prepare a generic structure simulating the 'emails.contact-lead' view variables
            $contactData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'subject' => $subject,
                'message' => "Gostaria de obter mais informações sobre a Fração " . $data['fraction_ref'] . 
                             " (ID Remax: " . ($data['remax_id'] ?? 'N/A') . ") do Empreendimento " . $data['development_title'] . "."
            ];

            Mail::send('emails.contact-lead', ['data' => $contactData], function ($message) use ($primaryEmail, $adminEmail, $subject, $consultant) {
                $message->from(config('mail.from.address'), config('mail.from.name'));
                $message->to($primaryEmail);
                
                if ($consultant) {
                    $message->bcc($adminEmail);
                    $message->subject("[House Team - {$consultant->name}] " . $subject);
                } else {
                    $message->subject('[House Team] ' . $subject);
                }
            });
        } catch (\Throwable $e) {
            Log::error('Erro Email Contacto Fração: ' . $e->getMessage());
        }

        // 2. Prepara dados para a View do CRM
        $info = [
            'TIPO' => 'CONTACTO FRAÇÃO (EMPREENDIMENTO)',
            'Empreendimento' => $data['development_title'],
            'Fração' => $data['fraction_ref'],
            'ID Remax' => $data['remax_id'] ?? 'Não informado'
        ];

        // 3. Processa
        $this->processCrmSubmission(
            [
                'lead_name' => $data['name'],
                'lead_email' => $data['email'],
                'lead_phone' => $data['phone']
            ], 
            $info, 
            'Formulário Empreendimentos', 
            'buyer', 
            $consultant
        );

        return back()->with('success', 'Mensagem enviada com sucesso! Entraremos em contacto em breve.');
    }

    // =========================================================================
    // MÉTODO CENTRALIZADOR: GERA VIEW LOG + ENVIA CRM
    // =========================================================================
    private function processCrmSubmission($contactData, $infoData, $tagSource, $pipelineType, $consultant, $monetaryValue = null)
    {
        try {
            // 1. Gera o JSON e o Link para a View Bonita
            $viewUrl = $this->generateLogJson($contactData, $infoData, $consultant);

            // 2. Prepara Tags
            $tags = [$tagSource, 'Lead Site'];
            if ($consultant) $tags[] = 'Consultor: ' . $consultant->name;

            // 3. Monta Nota com o Link (Isso é o que vai aparecer no CRM)
            $noteContent = "✅ FICHA COMPLETA DISPONÍVEL\n";
            $noteContent .= "🔗 ACESSAR: " . $viewUrl . "\n\n";
            $noteContent .= "Resumo Rápido:\n";
            // Adiciona um mini-resumo (primeiras 4 linhas) para facilitar visualização
            $count = 0;
            foreach ($infoData as $key => $val) {
                if($count < 4) $noteContent .= "- $key: $val\n";
                $count++;
            }
            if ($consultant) $noteContent .= "\nOrigem: " . $consultant->name;

            // 4. Envia para o Service (que já está blindado)
            $this->sendToCrm([
                'name'  => $contactData['lead_name'],
                'email' => $contactData['lead_email'],
                'phone' => $contactData['lead_phone'],
                'tags'  => $tags,
                'description' => $noteContent, // <--- Aqui vai o Link da View
                'source' => 'Site House Team',
                'property_price' => $monetaryValue
            ], $pipelineType, $consultant);

        } catch (\Throwable $e) {
            Log::error('Erro ProcessCrm: ' . $e->getMessage());
        }
    }

    /**
     * Gera um arquivo JSON na pasta public e retorna o LINK da ROTA para a VIEW.
     */
    private function generateLogJson($contact, $info, $consultant)
    {
        try {
            // Monta o array de dados que será passado para a View Blade
            $data = [
                'date' => date('d/m/Y H:i:s'),
                'contact' => $contact,
                'info' => $info,
                'consultant' => $consultant ? $consultant->name : null
            ];

            // Nome único para o arquivo JSON
            $fileName = 'lead_' . time() . '_' . Str::random(10) . '.json';
            
            // Salva no disco 'public' (storage/app/public/leads)
            Storage::disk('public')->put('leads/' . $fileName, json_encode($data));

            // Retorna a ROTA da View (ex: https://site.com/ferramentas/lead-view/lead_xxx.json)
            return route('tools.lead-view', ['filename' => $fileName]);

        } catch (\Throwable $e) {
            Log::error("Erro ao gerar JSON log: " . $e->getMessage());
            return "Erro ao gerar link (Verificar Logs).";
        }
    }

    // =========================================================================
    // HELPERS EXISTENTES
    // =========================================================================

    private function validateGainsRequest(Request $request, $isFinal = false)
    {
        $rules = [
            'acquisition_value' => 'required|numeric|min:0',
            'acquisition_year' => 'required|integer|min:1900|max:2026',
            'acquisition_month' => 'required|string',
            'sale_value' => 'required|numeric|min:0',
            'sale_year' => 'required|integer|min:1900|max:2026',
            'sale_month' => 'required|string',
            'has_expenses' => 'required|string|in:Sim,Não',
            'expenses_works' => 'nullable|numeric|min:0',
            'expenses_imt' => 'nullable|numeric|min:0',
            'expenses_commission' => 'nullable|numeric|min:0',
            'expenses_other' => 'nullable|numeric|min:0',
            'sold_to_state' => 'required|string|in:Sim,Não',
            'hpp_status' => 'nullable|string',
            'retired_status' => 'nullable|string',
            'self_built' => 'nullable|string',
            'reinvest_intention' => 'nullable|string',
            'reinvestment_amount' => 'nullable|numeric|min:0',
            'amortize_credit' => 'nullable|string',
            'amortization_amount' => 'nullable|numeric|min:0',
            'joint_tax_return' => 'nullable|string',
            'annual_income' => 'nullable|numeric|min:0',
            'public_support' => 'nullable|string',
            'public_support_year' => 'nullable|integer',
            'public_support_month' => 'nullable|string',
        ];

        if ($isFinal) {
            $rules['lead_name'] = 'required|string|max:255';
            $rules['lead_email'] = 'required|email|max:255';
            $rules['lead_phone'] = 'required|string|max:20';
        }

        $validated = $request->validate($rules);

        $totalExpenses = 0.0;
        if (($validated['has_expenses'] ?? 'Não') === 'Sim') {
            $totalExpenses = (float) ($validated['expenses_works'] ?? 0) + 
                             (float) ($validated['expenses_imt'] ?? 0) + 
                             (float) ($validated['expenses_commission'] ?? 0) + 
                             (float) ($validated['expenses_other'] ?? 0);
        }
        $validated['expenses_total'] = $totalExpenses;

        return $validated;
    }

    private function sendEmailWithPdf($email, $name, $type, $pdfView, $viewData)
    {
        try {
            $viewData['date'] = date('d/m/Y');
            $pdf = Pdf::loadView($pdfView, $viewData);
            
            Mail::send('emails.simulation-lead', ['name' => $name, 'simulationType' => $type], function ($message) use ($email, $type, $pdf) {
                $message->from(config('mail.from.address'), config('mail.from.name'))
                    ->to($email)
                    ->subject($type . ' - Resultado')
                    ->attachData($pdf->output(), 'simulacao.pdf');
            });
        } catch (\Throwable $e) {
            Log::error('Erro PDF Email: ' . $e->getMessage());
        }
    }

    private function savePdfToStorage($content, $prefix)
    {
        try {
            $fileName = $prefix . '_' . time() . '_' . Str::random(6) . '.pdf';
            Storage::disk('public')->put('simulations/' . $fileName, $content);
            return asset('storage/simulations/' . $fileName);
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function sendToCrm(array $contactData, string $pipelineType, $consultant = null)
    {
        try {
            $contact = $this->crmService->createContact($contactData);

            if ($contact && isset($contact['id'])) {
                
                $ownerId = self::DEFAULT_CRM_ID;
                if ($consultant && !empty($consultant->crm_user_id)) {
                    $ownerId = $consultant->crm_user_id;
                } elseif ($consultant && str_contains(strtolower($consultant->name), 'margarida')) {
                    $ownerId = self::MARGARIDA_CRM_ID;
                }

                $this->crmService->assignUser($contact['id'], $ownerId);

                if (!empty($contactData['description'])) {
                    $this->crmService->addNote($contact['id'], $contactData['description']);
                }
                
                $this->crmService->createOpportunity($contact['id'], $contactData, $pipelineType);
            }
        } catch (\Throwable $e) {
            Log::error('GHL Integration Error: ' . $e->getMessage());
        }
    }
}