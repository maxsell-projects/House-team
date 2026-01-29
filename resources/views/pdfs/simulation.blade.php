<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Relatório de Mais-Valias</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1e293b; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #c5a059; pb-20px; }
        .logo { max-width: 150px; margin-bottom: 10px; }
        h1 { color: #1e293b; margin: 0; font-size: 24px; }
        h2 { color: #c5a059; font-size: 16px; margin-top: 20px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .row { margin-bottom: 8px; }
        .label { font-weight: bold; width: 60%; display: inline-block; }
        .value { width: 35%; display: inline-block; text-align: right; }
        .result-box { background-color: #f1f5f9; padding: 15px; border-radius: 5px; margin-top: 30px; }
        .result-row { display: block; margin-bottom: 10px; font-size: 14px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Simulação de Mais-Valias</h1>
        <p>House Team Consultores</p>
        <p>Data: {{ $date }}</p>
    </div>

    <div class="content">
        <h2>Dados da Operação</h2>
        <div class="row">
            <span class="label">Valor de Aquisição:</span>
            <span class="value">{{ number_format($data['acquisition_value'], 2, ',', '.') }} €</span>
        </div>
        <div class="row">
            <span class="label">Ano/Mês Aquisição:</span>
            <span class="value">{{ $data['acquisition_month'] }}/{{ $data['acquisition_year'] }}</span>
        </div>
        <div class="row">
            <span class="label">Valor de Venda:</span>
            <span class="value">{{ number_format($data['sale_value'], 2, ',', '.') }} €</span>
        </div>
        <div class="row">
            <span class="label">Ano/Mês Venda:</span>
            <span class="value">{{ $data['sale_month'] }}/{{ $data['sale_year'] }}</span>
        </div>

        <h2>Despesas Dedutíveis</h2>
        <div class="row">
            <span class="label">Total Despesas:</span>
            <span class="value">{{ number_format($data['expenses_total'], 2, ',', '.') }} €</span>
        </div>

        <div class="result-box">
            <div class="result-row">
                <span class="label">Mais-Valia Bruta:</span>
                <span class="value" style="font-weight: bold;">{{ $results['gross_gain_fmt'] }} €</span>
            </div>
            <div class="result-row">
                <span class="label">Coeficiente de Desvalorização:</span>
                <span class="value">{{ $results['coefficient'] }}</span>
            </div>
            <div class="result-row">
                <span class="label" style="color: #c5a059;">IMPOSTO ESTIMADO (IRS):</span>
                <span class="value" style="font-size: 18px; font-weight: bold; color: #1e293b;">{{ $results['estimated_tax_fmt'] }} €</span>
            </div>
        </div>

        <div style="margin-top: 20px; font-size: 10px; color: #64748b;">
            <p><strong>Nota Legal:</strong> Esta simulação é meramente informativa e não dispensa a consulta de um contabilista certificado ou da Autoridade Tributária. Os valores apresentados são estimativas baseadas na legislação em vigor.</p>
        </div>
    </div>

    <div class="footer">
        House Team - www.houseteamconsultores.pt
    </div>
</body>
</html>