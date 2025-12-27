<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CapitalGainsCalculatorService;
use App\Services\HighLevelService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // <--- Importante
use Illuminate\Support\Str; // <--- Importante

class ToolsController extends Controller
{
    protected $calculator;
    protected $crmService;

    public function __construct(CapitalGainsCalculatorService $calculator, HighLevelService $crmService)
    {
        $this->calculator = $calculator;
        $this->crmService = $crmService;
    }

    public function showGainsSimulator()
    {
        return view('tools.gains');
    }

    public function showCreditSimulator()
    {
        return view('tools.credit');
    }

    public function showImtSimulator()
    {
        return view('tools.imt');
    }

    public function calculateGains(Request $request)
    {
        // Validação (Mantida igual)
        $validated = $request->validate([
            'acquisition_value' => 'required|numeric|min:0',
            'acquisition_year' => 'required|integer|min:1900|max:2025',
            'acquisition_month' => 'required|string',
            'sale_value' => 'required|numeric|min:0',
            'sale_year' => 'required|integer|min:1900|max:2025',
            'sale_month' => 'required|string',
            'has_expenses' => 'required|string|in:Sim,Não',
            'expenses_works' => 'nullable|numeric|min:0',
            'expenses_imt' => 'nullable|numeric|min:0',
            'expenses_commission' => 'nullable|numeric|min:0',
            'expenses_other' => 'nullable|numeric|min:0',
            'sold_to_state' => 'required|string|in:Sim,Não',
            'hpp_status' => 'required_unless:sold_to_state,Sim|nullable|string',
            'retired_status' => 'required_unless:sold_to_state,Sim|nullable|string|in:Sim,Não',
            'self_built' => 'required_unless:sold_to_state,Sim|nullable|string|in:Sim,Não',
            'reinvest_intention' => 'required_unless:sold_to_state,Sim|nullable|string|in:Sim,Não',
            'reinvestment_amount' => 'nullable|numeric|min:0',
            'amortize_credit' => 'required_unless:sold_to_state,Sim|nullable|string|in:Sim,Não',
            'amortization_amount' => 'nullable|numeric|min:0',
            'joint_tax_return' => 'required_unless:sold_to_state,Sim|nullable|string|in:Sim,Não',
            'annual_income' => 'required_unless:sold_to_state,Sim|nullable|numeric|min:0',
            'public_support' => 'required_unless:sold_to_state,Sim|nullable|string|in:Sim,Não',
            'public_support_year' => 'nullable|integer',
            'public_support_month' => 'nullable|string',
            'lead_name' => 'required|string|max:255',
            'lead_email' => 'required|email|max:255'
        ]);

        $totalExpenses = 0.0;
        if ($validated['has_expenses'] === 'Sim') {
            $totalExpenses = (float) ($validated['expenses_works'] ?? 0) + (float) ($validated['expenses_imt'] ?? 0) + (float) ($validated['expenses_commission'] ?? 0) + (float) ($validated['expenses_other'] ?? 0);
        }
        $validated['expenses_total'] = $totalExpenses;

        $results = $this->calculator->calculate($validated);

        if ($request->filled('lead_email')) {
            $this->sendEmailWithPdf($validated['lead_email'], $validated['lead_name'], 'Simulação de Mais-Valias', 'pdfs.simulation', ['data' => $validated, 'results' => $results]);

            // CRM - Simulador Mais Valias
            $description = "Simulação Mais-Valias:\nCompra: {$validated['acquisition_value']}€ ({$validated['acquisition_year']})\nVenda: {$validated['sale_value']}€";
            
            $this->sendToCrm([
                'name'  => $validated['lead_name'],
                'email' => $validated['lead_email'],
                'tags'  => ['Simulador Mais-Valias', 'Lead Site'],
                'description' => $description
            ], 'credit');
        }

        return response()->json($results);
    }

    // --- AQUI ESTÁ A ALTERAÇÃO PRINCIPAL ---
    public function sendCreditSimulation(Request $request)
    {
        $data = $request->validate([
            'propertyValue' => 'required',
            'loanAmount' => 'required',
            'years' => 'required',
            'tan' => 'required',
            'monthlyPayment' => 'required',
            'mtic' => 'required',
            'lead_name' => 'required|string',
            'lead_email' => 'required|email'
        ]);

        // 1. Preparar Dados e Gerar o PDF na Memória
        $viewData = [
            'title' => 'Relatório Crédito Habitação', 
            'data' => $data,
            'date' => date('d/m/Y')
        ];
        
        $pdf = Pdf::loadView('pdfs.simple-report', $viewData);
        $pdfContent = $pdf->output();

        // 2. Enviar Email (Usando o conteúdo gerado)
        try {
            Mail::send('emails.simulation-lead', ['name' => $data['lead_name'], 'simulationType' => 'Simulação Crédito Habitação'], function ($message) use ($data, $pdfContent) {
                $message->to($data['lead_email'])
                    ->subject('Simulação Crédito Habitação - Resultado Detalhado')
                    ->attachData($pdfContent, 'simulacao.pdf');
            });
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de simulação: ' . $e->getMessage());
        }

        // 3. Salvar o PDF no Disco Público para criar um Link
        $fileName = 'credito_' . time() . '_' . Str::random(6) . '.pdf';
        $filePath = 'simulations/' . $fileName; // Salva na pasta 'storage/app/public/simulations'
        
        try {
            Storage::disk('public')->put($filePath, $pdfContent);
            $pdfUrl = asset('storage/' . $filePath); // Gera o link público (Ex: https://site.com/storage/...)

            // 4. Adicionar o Link na Descrição do CRM
            $description = "Simulação Crédito:\n" .
                           "Imóvel: {$data['propertyValue']}€\n" .
                           "Empréstimo: {$data['loanAmount']}€\n" .
                           "Mensalidade: {$data['monthlyPayment']}€\n\n" .
                           "📥 PDF RELATÓRIO: " . $pdfUrl;
        } catch (\Exception $e) {
            Log::error('Erro ao salvar PDF: ' . $e->getMessage());
            // Se falhar o save, manda sem o link
            $description = "Simulação Crédito:\nImóvel: {$data['propertyValue']}€\nEmpréstimo: {$data['loanAmount']}€\n(Erro ao gerar link do PDF)";
        }

        // 5. Enviar para o CRM
        $this->sendToCrm([
            'name'  => $data['lead_name'],
            'email' => $data['lead_email'],
            'tags'  => ['Simulador Crédito', 'Lead Site'],
            'description' => $description
        ], 'credit');

        return response()->json(['success' => true]);
    }

    public function sendImtSimulation(Request $request)
    {
        $data = $request->validate([
            'propertyValue' => 'required',
            'location' => 'required',
            'purpose' => 'required',
            'finalIMT' => 'required',
            'finalStamp' => 'required',
            'totalPayable' => 'required',
            'lead_name' => 'required|string',
            'lead_email' => 'required|email'
        ]);

        $this->sendEmailWithPdf($data['lead_email'], $data['lead_name'], 'Simulação IMT e Selo', 'pdfs.simple-report', ['title' => 'Relatório IMT e Imposto de Selo', 'data' => $data]);

        // CRM - Simulador IMT
        $description = "Simulação IMT:\nValor: {$data['propertyValue']}€\nLocal: {$data['location']}\nTotal Impostos: {$data['totalPayable']}€";

        $this->sendToCrm([
            'name'  => $data['lead_name'],
            'email' => $data['lead_email'],
            'tags'  => ['Simulador IMT', 'Lead Site'],
            'description' => $description
        ], 'credit');

        return response()->json(['success' => true]);
    }

    public function sendContact(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'phone'       => 'nullable|string|max:20',
            'subject'     => 'nullable|string|max:255',
            'message'     => 'nullable|string', 
            'property_type' => 'nullable|string',
            'year'        => 'nullable|integer',
            'area'        => 'nullable|numeric',
            'bedrooms'    => 'nullable|integer',
            'bathrooms'   => 'nullable|integer',
            'garages'     => 'nullable|integer',
            'parking_type' => 'nullable|string',
            'features'    => 'nullable|array',
            'condition'   => 'nullable|string',
            'address'     => 'nullable|string',
            'is_owner'    => 'nullable|string',
            'estimated_value' => 'nullable|numeric',
            'property_code' => 'nullable|string',
        ]);

        if (empty($data['subject'])) $data['subject'] = 'Novo Contacto Geral';

        try {
            $adminEmail = 'admin@houseteam.pt'; 
            Mail::send('emails.contact-lead', ['data' => $data], function ($message) use ($adminEmail, $data) {
                $message->to($adminEmail)->subject('[House Team] ' . $data['subject']);
            });

            // CRM Config
            $crmTags = ['Lead Site'];
            $descriptionParts = [];

            if (!empty($data['message'])) {
                $descriptionParts[] = "Mensagem: " . $data['message'];
            }
            if (!empty($data['property_code'])) {
                $crmTags[] = 'Interesse Imóvel';
                $descriptionParts[] = "Ref. Imóvel: " . $data['property_code'];
            }
            if (!empty($data['is_owner'])) {
                $crmTags[] = 'Vender Imóvel';
                $descriptionParts[] = "Quer Vender? Sim";
            }
            if (!empty($data['phone'])) {
                $descriptionParts[] = "Telefone: " . $data['phone'];
            }

            $this->sendToCrm([
                'name'  => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'tags'  => $crmTags,
                'description' => implode("\n", $descriptionParts)
            ], 'lead');

            return back()->with('success', 'O seu pedido foi enviado com sucesso! Entraremos em contacto brevemente.');

        } catch (\Exception $e) {
            Log::error('Erro ao enviar contacto: ' . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao enviar a mensagem. Por favor tente novamente.');
        }
    }

    private function sendEmailWithPdf($email, $name, $type, $pdfView, $viewData)
    {
        try {
            $viewData['date'] = date('d/m/Y');
            $pdf = Pdf::loadView($pdfView, $viewData);
            Mail::send('emails.simulation-lead', ['name' => $name, 'simulationType' => $type], function ($message) use ($email, $type, $pdf) {
                $message->to($email)
                    ->subject($type . ' - Resultado Detalhado')
                    ->attachData($pdf->output(), 'simulacao.pdf');
            });
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de simulação: ' . $e->getMessage());
        }
    }

    private function sendToCrm(array $contactData, string $pipelineType)
    {
        try {
            // 1. Cria Contato
            $contact = $this->crmService->createContact($contactData);

            if ($contact && isset($contact['id'])) {
                // 2. Se tiver descrição/mensagem, adiciona como NOTA
                if (!empty($contactData['description'])) {
                    $this->crmService->addNote($contact['id'], $contactData['description']);
                }

                // 3. Cria Oportunidade
                $this->crmService->createOpportunity($contact['id'], $contactData, $pipelineType);
            }
        } catch (\Exception $e) {
            Log::error('GHL Integration Error: ' . $e->getMessage());
        }
    }
}