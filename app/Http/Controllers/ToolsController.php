<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CapitalGainsCalculatorService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ToolsController extends Controller
{
    protected $calculator;

    public function __construct(CapitalGainsCalculatorService $calculator)
    {
        $this->calculator = $calculator;
    }

    // --- VIEWS ---
    public function showGainsSimulator() { return view('tools.gains'); }
    public function showCreditSimulator() { return view('tools.credit'); } // Caso tenhas rota controller
    public function showImtSimulator() { return view('tools.imt'); } // Caso tenhas rota controller

    // --- MAIS-VALIAS (Cálculo + Lead) ---
    public function calculateGains(Request $request)
    {
        // 1. Validação Robusta com Condicionais
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
            
            // Campos Condicionais
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
            
            // Dados da Lead
            'lead_name' => 'required|string|max:255',
            'lead_email' => 'required|email|max:255'
        ]);

        // 2. Soma das Despesas
        $totalExpenses = 0.0;
        if ($validated['has_expenses'] === 'Sim') {
            $totalExpenses = 
                (float) ($validated['expenses_works'] ?? 0) + 
                (float) ($validated['expenses_imt'] ?? 0) + 
                (float) ($validated['expenses_commission'] ?? 0) + 
                (float) ($validated['expenses_other'] ?? 0);
        }
        $validated['expenses_total'] = $totalExpenses;

        // 3. Cálculo
        $results = $this->calculator->calculate($validated);

        // 4. Envio de Email
        if ($request->filled('lead_email')) {
            $this->sendEmailWithPdf(
                $validated['lead_email'],
                $validated['lead_name'],
                'Simulação de Mais-Valias',
                'pdfs.simulation', // View específica de mais-valias (já existente)
                ['data' => $validated, 'results' => $results]
            );
        }

        return response()->json($results);
    }

    // --- CRÉDITO HABITAÇÃO (Novo Método) ---
    public function sendCreditSimulation(Request $request)
    {
        $data = $request->validate([
            'propertyValue' => 'required', 'loanAmount' => 'required', 'years' => 'required',
            'tan' => 'required', 'monthlyPayment' => 'required', 'mtic' => 'required',
            'lead_name' => 'required|string', 'lead_email' => 'required|email'
        ]);

        $this->sendEmailWithPdf(
            $data['lead_email'],
            $data['lead_name'],
            'Simulação Crédito Habitação',
            'pdfs.simple-report', // View genérica nova
            ['title' => 'Relatório Crédito Habitação', 'data' => $data]
        );

        return response()->json(['success' => true]);
    }

    // --- IMT (Novo Método) ---
    public function sendImtSimulation(Request $request)
    {
        $data = $request->validate([
            'propertyValue' => 'required', 'location' => 'required', 'purpose' => 'required',
            'finalIMT' => 'required', 'finalStamp' => 'required', 'totalPayable' => 'required',
            'lead_name' => 'required|string', 'lead_email' => 'required|email'
        ]);

        $this->sendEmailWithPdf(
            $data['lead_email'],
            $data['lead_name'],
            'Simulação IMT e Selo',
            'pdfs.simple-report', // View genérica nova
            ['title' => 'Relatório IMT e Imposto de Selo', 'data' => $data]
        );

        return response()->json(['success' => true]);
    }

    // --- CONTACTO GERAL ---
    public function sendContact(Request $request)
    {
        // Validação e lógica de envio do formulário de contacto da página /contato
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'message' => 'required|string',
        ]);
        
        // Exemplo: Mail::to('admin@houseteam.pt')->send(new ContactMail($data));
        
        return back()->with('success', 'Mensagem enviada com sucesso!');
    }

    // --- HELPER PARA EMAIL ---
    private function sendEmailWithPdf($email, $name, $type, $pdfView, $viewData)
    {
        try {
            $viewData['date'] = date('d/m/Y');
            
            // Gera o PDF
            $pdf = Pdf::loadView($pdfView, $viewData);

            // Envia o Email com a Blade "simulation-lead"
            Mail::send('emails.simulation-lead', ['name' => $name, 'simulationType' => $type], function ($message) use ($email, $type, $pdf) {
                $message->to($email)
                    ->subject($type . ' - Resultado Detalhado')
                    ->attachData($pdf->output(), 'simulacao.pdf');
            });

        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de simulação: ' . $e->getMessage());
        }
    }
}