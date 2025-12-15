<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Relatório de Simulação de Mais-Valias</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #334155; margin: 0; padding: 0; }
        .container { width: 100%; max-width: 800px; margin: 0 auto; }
        
        /* Cabeçalho House Team (Navy) */
        .header { background-color: #020617; color: #fff; padding: 40px; text-align: center; margin-bottom: 40px; }
        .logo { font-size: 32px; font-weight: 900; letter-spacing: -1px; margin-bottom: 5px; color: #fff; }
        .logo span { color: #94a3b8; } /* Cinza claro para 'Consultores' */
        .subtitle { font-size: 10px; text-transform: uppercase; letter-spacing: 3px; color: #dc2626; font-weight: bold; }
        
        /* Cartão de Resultado */
        .result-card { border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; margin-bottom: 30px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .result-header { background-color: #020617; color: #fff; padding: 25px; text-align: center; }
        .result-title { font-size: 11px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; opacity: 0.7; }
        .result-value { font-size: 42px; font-weight: bold; color: #dc2626; } /* Vermelho House Team */
        
        /* Tabela de Detalhes */
        .details-table { width: 100%; border-collapse: collapse; margin-top: 0; }
        .details-table th, .details-table td { padding: 18px 25px; text-align: left; border-bottom: 1px solid #f1f5f9; font-size: 13px; }
        .details-table th { color: #64748b; font-weight: normal; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; }
        .details-table td { text-align: right; font-weight: bold; color: #0f172a; }
        
        .details-table .highlight { color: #020617; font-size: 15px; }
        .details-table .negative { color: #dc2626; }
        .details-table tr:last-child td { border-bottom: none; }

        /* Título da Secção */
        h3 { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #020617; border-bottom: 2px solid #dc2626; padding-bottom: 10px; margin-bottom: 20px; display: inline-block; }

        .footer { text-align: center; font-size: 9px; color: #94a3b8; margin-top: 50px; border-top: 1px solid #f1f5f9; padding-top: 20px; text-transform: uppercase; letter-spacing: 1px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">HOUSE <span>TEAM</span></div>
        <div class="subtitle">Consultores Imobiliários</div>
    </div>

    <div class="container">
        <div class="result-card">
            <div class="result-header">
                <div class="result-title">Imposto Estimado (IRS)</div>
                <div class="result-value">{{ $results['estimated_tax_fmt'] }} €</div>
            </div>
            <table class="details-table">
                <tr>
                    <th style="background-color: #fff;">Mais-Valia Bruta</th>
                    <td style="background-color: #fff; font-size: 16px;">{{ $results['gross_gain_fmt'] }} €</td>
                </tr>
                <tr>
                    <th style="background-color: #f8fafc;">Base Tributável (50%)</th>
                    <td style="background-color: #f8fafc; font-size: 16px;">{{ $results['taxable_gain_fmt'] }} €</td>
                </tr>
            </table>
        </div>

        <h3>Detalhamento do Cálculo</h3>

        <table class="details-table" style="border: 1px solid #e2e8f0; border-radius: 8px;">
            <tr>
                <th>Valor de Venda</th>
                <td>{{ $results['sale_fmt'] }} €</td>
            </tr>
            <tr>
                <th>Coeficiente de Atualização ({{ $data['acquisition_year'] }})</th>
                <td>{{ $results['coefficient'] }}</td>
            </tr>
            <tr>
                <th>Valor de Aquisição Atualizado</th>
                <td class="negative">- {{ $results['acquisition_updated_fmt'] }} €</td>
            </tr>
            <tr>
                <th>Despesas e Encargos</th>
                <td class="negative">- {{ $results['expenses_fmt'] }} €</td>
            </tr>
            @if(isset($results['reinvestment_fmt']) && $results['reinvestment_fmt'] != '0,00')
            <tr>
                <th>Valor Reinvestido / Amortizado</th>
                <td class="negative">- {{ $results['reinvestment_fmt'] }} €</td>
            </tr>
            @endif
            <tr style="background-color: #f8fafc; border-top: 2px solid #e2e8f0;">
                <th style="color: #020617; font-weight: bold;">MAIS-VALIA FINAL APURADA</th>
                <td class="highlight">{{ $results['gross_gain_fmt'] }} €</td>
            </tr>
        </table>

        <div class="footer">
            <p>Simulação gerada em {{ $date }} | House Team Consultores</p>
            <p>Este documento é meramente informativo e não dispensa a consulta oficial.</p>
        </div>
    </div>
</body>
</html>