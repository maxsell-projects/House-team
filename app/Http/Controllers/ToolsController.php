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
    // IDs de Fallback
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

    public function showGainsSimulator() { return view('tools.gains'); }
    public function showCreditSimulator() { return view('tools.credit'); }
    public function showImtSimulator() { return view('tools.imt'); }

    // =========================================================================
    // MAIS-VALIAS: CÁLCULO PRÉVIO (AJAX)
    // =========================================================================
    public function calculateGainsOnly(Request $request)
    {
        $validated = $this->validateGainsRequest($request, false);
        $results = $this->calculator->calculate($validated);
        return response()->json($results);
    }

    // =========================================================================
    // MAIS-VALIAS: ENVIO FINAL (POST) -> CRM + PDF
    // =========================================================================
    public function calculateGains(Request $request, $domain = null)
    {
        $consultant = $this->getContext($domain);
        $validated = $this->validateGainsRequest($request, true);

        // Recalcula para garantir consistência
        $results = $this->calculator->calculate($validated);

        if ($request->filled('lead_email')) {
            // Tenta enviar PDF (com proteção)
            $this->sendEmailWithPdf(
                $validated['lead_email'], 
                $validated['lead_name'], 
                'Simulação de Mais-Valias', 
                'pdfs.simulation', 
                ['data' => $validated, 'results' => $results]
            );

            // Montagem rica de dados para o CRM (Com proteção contra NULL)
            $description = "Simulação Mais-Valias Detalhada:\n";
            $description .= "--------------------------------\n";
            $description .= "Compra: " . number_format((float)$validated['acquisition_value'], 2, ',', '.') . "€ (" . $validated['acquisition_year'] . ")\n";
            $description .= "Venda: " . number_format((float)$validated['sale_value'], 2, ',', '.') . "€ (" . $validated['sale_year'] . ")\n";
            $description .= "Despesas Totais: " . number_format((float)$validated['expenses_total'], 2, ',', '.') . "€\n";
            
            if (($validated['sold_to_state'] ?? 'Não') === 'Sim') $description .= "- Venda ao Estado: SIM (Isento)\n";
            if (($validated['reinvest_intention'] ?? 'Não') === 'Sim') $description .= "- Reinvestimento: " . number_format((float)($validated['reinvestment_amount'] ?? 0), 2, ',', '.') . "€\n";
            if (($validated['amortize_credit'] ?? 'Não') === 'Sim') $description .= "- Amortização Crédito: " . number_format((float)($validated['amortization_amount'] ?? 0), 2, ',', '.') . "€\n";
            
            $description .= "\nRESULTADO:\n";
            $description .= "Mais-Valia Bruta: " . ($results['gross_gain_fmt'] ?? '0,00') . "€\n";
            $description .= "Imposto Estimado: " . ($results['estimated_tax_fmt'] ?? '0,00') . "€\n";
            $description .= "Telefone: " . ($validated['lead_phone'] ?? 'N/A');

            $tags = ['Simulador Mais-Valias', 'Lead Site'];
            if ($consultant) {
                $tags[] = 'Consultor: ' . $consultant->name;
                $description .= "\n\nOrigem: Site " . $consultant->name;
            }

            $this->sendToCrm([
                'name'  => $validated['lead_name'],
                'email' => $validated['lead_email'],
                'phone' => $validated['lead_phone'],
                'tags'  => $tags,
                'description' => $description,
                'source' => 'Site House Team - Mais Valias',
                'property_price' => $validated['sale_value']
            ], 'seller', $consultant);
        }

        return response()->json(['success' => true, 'results' => $results]);
    }

    // Helper de Validação para evitar duplicação
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

        // Preenche com 0 se vier nulo para cálculos
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

    // =========================================================================
    // CRÉDITO
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

        // Gera PDF de forma segura
        $pdfContent = null;
        try {
            $viewData = ['title' => 'Relatório Crédito Habitação', 'data' => $data, 'date' => date('d/m/Y')];
            $pdfContent = Pdf::loadView('pdfs.simple-report', $viewData)->output();
        } catch (\Throwable $e) {
            Log::error('Erro ao gerar PDF Crédito: ' . $e->getMessage());
        }

        // Email
        if ($pdfContent) {
            try {
                Mail::send('emails.simulation-lead', ['name' => $data['lead_name'], 'simulationType' => 'Simulação Crédito Habitação'], function ($message) use ($data, $pdfContent) {
                    $message->from(config('mail.from.address'), config('mail.from.name'))
                        ->to($data['lead_email'])
                        ->subject('Simulação Crédito Habitação - Resultado Detalhado')
                        ->attachData($pdfContent, 'simulacao.pdf');
                });
            } catch (\Throwable $e) {
                Log::error('Erro email simulação crédito: ' . $e->getMessage());
            }
        }

        $description = "Simulação Crédito:\nImóvel: {$data['propertyValue']}€\nEmpréstimo: {$data['loanAmount']}€\nMensalidade: {$data['monthlyPayment']}€\nPrazo: {$data['years']} anos";
        $description .= "\nTelefone: " . $data['lead_phone'];

        if($pdfContent) {
            $pdfLink = $this->savePdfToStorage($pdfContent, 'credito');
            if($pdfLink) $description .= "\n\n📥 PDF: " . $pdfLink;
        }

        $tags = ['Simulador Crédito', 'Lead Site'];
        if ($consultant) {
            $tags[] = 'Consultor: ' . $consultant->name;
            $description .= "\nOrigem: Site " . $consultant->name;
        }

        $this->sendToCrm([
            'name'  => $data['lead_name'],
            'email' => $data['lead_email'],
            'phone' => $data['lead_phone'],
            'tags'  => $tags,
            'description' => $description,
            'source' => 'Site House Team - Simulador Crédito', 
            'property_price' => $data['propertyValue'] 
        ], 'credit', $consultant);

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

        $this->sendEmailWithPdf($data['lead_email'], $data['lead_name'], 'Simulação IMT e Selo', 'pdfs.simple-report', ['title' => 'Relatório IMT e Imposto de Selo', 'data' => $data]);

        $description = "Simulação IMT:\nValor: {$data['propertyValue']}€\nLocal: {$data['location']}\nFinalidade: {$data['purpose']}\n";
        $description .= "IMT: {$data['finalIMT']}€ | Selo: {$data['finalStamp']}€\nTotal Impostos: {$data['totalPayable']}€";
        $description .= "\nTelefone: " . $data['lead_phone'];

        $tags = ['Simulador IMT', 'Lead Site'];
        if ($consultant) {
            $tags[] = 'Consultor: ' . $consultant->name;
            $description .= "\nOrigem: Site " . $consultant->name;
        }

        $this->sendToCrm([
            'name'  => $data['lead_name'],
            'email' => $data['lead_email'],
            'phone' => $data['lead_phone'],
            'tags'  => $tags,
            'description' => $description,
            'source' => 'Site House Team - IMT',
            'property_price' => (float) str_replace(['.', ','], ['', '.'], $data['propertyValue'])
        ], 'buyer', $consultant);

        return response()->json(['success' => true]);
    }

    // =========================================================================
    // CONTATO GERAL
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

        $isValuation = ($request->input('subject') === 'avaliacao');
        if ($isValuation) {
            $rules['phone'] = 'required|string|max:20';
            $rules['year'] = 'required|integer';
            $rules['area'] = 'required|numeric';
            $rules['bedrooms'] = 'required|integer';
            $rules['bathrooms'] = 'required|integer';
            $rules['parking_type'] = 'required|string';
            $rules['condition'] = 'required|string';
        }

        $data = $request->validate($rules);
        if (empty($data['subject'])) $data['subject'] = 'Novo Contacto Geral';

        try {
            $primaryEmail = $consultant ? $consultant->email : 'admin@houseteam.pt';
            $adminEmail = 'admin@houseteam.pt';

            Mail::send('emails.contact-lead', ['data' => $data], function ($message) use ($primaryEmail, $adminEmail, $data, $consultant) {
                $message->from(config('mail.from.address'), config('mail.from.name'));
                $message->to($primaryEmail);
                if ($consultant) {
                    $message->bcc($adminEmail);
                    $message->subject("[House Team - {$consultant->name}] " . $data['subject']);
                } else {
                    $message->subject('[House Team] ' . $data['subject']);
                }
            });

            $crmTags = ['Lead Site'];
            if ($consultant) $crmTags[] = 'Consultor: ' . $consultant->name;

            $descriptionParts = [];
            if (!empty($data['phone'])) $descriptionParts[] = "📞 Telefone: " . $data['phone'];
            if (!empty($data['is_owner'])) {
                $descriptionParts[] = "👤 Proprietário? " . $data['is_owner'];
                if (($data['is_owner'] === 'Sim') || $isValuation) $crmTags[] = 'Vendedor';
            }

            if ($isValuation || !empty($data['year'])) {
                $descriptionParts[] = "\n🏡 DETALHES DO IMÓVEL:";
                $descriptionParts[] = "Tipo: " . ($data['property_type'] ?? 'N/A');
                $descriptionParts[] = "Ano: " . ($data['year'] ?? '-');
                $descriptionParts[] = "Área: " . ($data['area'] ?? '-') . " m²";
                $descriptionParts[] = "T" . ($data['bedrooms'] ?? 0);
                if (!empty($data['estimated_value'])) $descriptionParts[] = "💰 Valor Estimado: " . $data['estimated_value'] . "€";
            }

            if (!empty($data['message'])) $descriptionParts[] = "\n💬 Mensagem:\n" . $data['message'];
            if (!empty($data['property_code'])) {
                $crmTags[] = 'Interesse Imóvel';
                $descriptionParts[] = "Ref. Imóvel: " . $data['property_code'];
            }

            $subjectLower = mb_strtolower($data['subject']);
            $pipelineType = 'lead';

            if ($isValuation || str_contains($subjectLower, 'avalia')) $pipelineType = 'valuation';
            elseif (str_contains($subjectLower, 'vender')) $pipelineType = 'seller';
            elseif (str_contains($subjectLower, 'recrutamento')) $pipelineType = 'recruitment';
            elseif (str_contains($subjectLower, 'comprar')) $pipelineType = 'buyer';

            $this->sendToCrm([
                'name'  => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'tags'  => $crmTags,
                'description' => implode("\n", $descriptionParts),
                'source' => 'Site House Team',
                'property_price' => $data['estimated_value'] ?? null
            ], $pipelineType, $consultant);

            return back()->with('success', 'Mensagem enviada com sucesso!');

        } catch (\Throwable $e) {
            Log::error('Erro Contacto: ' . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro. Tente novamente.');
        }
    }

    private function sendEmailWithPdf($email, $name, $type, $pdfView, $viewData)
    {
        try {
            $viewData['date'] = date('d/m/Y');
            // Blinda contra falha na geração do PDF
            $pdf = Pdf::loadView($pdfView, $viewData);
            
            Mail::send('emails.simulation-lead', ['name' => $name, 'simulationType' => $type], function ($message) use ($email, $type, $pdf) {
                $message->from(config('mail.from.address'), config('mail.from.name'))
                    ->to($email)
                    ->subject($type . ' - Resultado Detalhado')
                    ->attachData($pdf->output(), 'simulacao.pdf');
            });
        } catch (\Throwable $e) {
            // Loga o erro mas NÃO PARA a execução
            Log::error('Erro crítico ao gerar/enviar PDF: ' . $e->getMessage());
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