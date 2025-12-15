<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CapitalGainsCalculatorService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class ToolsController extends Controller
{
    protected $calculator;

    public function __construct(CapitalGainsCalculatorService $calculator)
    {
        $this->calculator = $calculator;
    }

    // Exibe a view do simulador
    public function showGainsSimulator()
    {
        return view('tools.gains');
    }

    // Processa o cálculo
    public function calculateGains(Request $request)
    {
        // 1. Validação Robusta com Condicionais
        // 'required_unless:sold_to_state,Sim' garante que se vender ao estado, não exige campos irrelevantes
        $validated = $request->validate([
            // Dados Base Obrigatórios
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
            
            // Campos Condicionais (Só obrigatórios se NÃO for isento pelo Estado)
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
            
            // Dados da Lead (Obrigatórios para ver o resultado)
            'lead_name' => 'required|string|max:255',
            'lead_email' => 'required|email|max:255'
        ]);

        // 2. Soma e Preparação das Despesas
        $totalExpenses = 0.0;
        if ($validated['has_expenses'] === 'Sim') {
            $totalExpenses = 
                (float) ($validated['expenses_works'] ?? 0) + 
                (float) ($validated['expenses_imt'] ?? 0) + 
                (float) ($validated['expenses_commission'] ?? 0) + 
                (float) ($validated['expenses_other'] ?? 0);
        }
        // Adiciona o total ao array de dados
        $validated['expenses_total'] = $totalExpenses;

        // 3. Executa o Cálculo no Service
        $results = $this->calculator->calculate($validated);

        // 4. Gera PDF e Envia E-mail (Lead Magnet)
        if ($request->filled('lead_email')) {
            $this->sendPdfToLead($validated, $results);
        }

        // 5. Retorna JSON para o Frontend (AlpineJS)
        return response()->json($results);
    }

    /**
     * Função auxiliar para envio de email com PDF
     */
    private function sendPdfToLead(array $data, array $results)
    {
        try {
            $pdf = Pdf::loadView('pdfs.simulation', [
                'data' => $data,
                'results' => $results,
                'date' => date('d/m/Y')
            ]);

            Mail::send([], [], function ($message) use ($data, $pdf) {
                $message->to($data['lead_email'])
                    ->subject('Simulação de Mais-Valias - Resultado Detalhado')
                    ->attachData($pdf->output(), 'simulacao-mais-valias.pdf');
            });
        } catch (\Exception $e) {
            // Log silencioso para não interromper a UX do utilizador
            \Illuminate\Support\Facades\Log::error('Erro ao enviar email de simulação: ' . $e->getMessage());
        }
    }
}