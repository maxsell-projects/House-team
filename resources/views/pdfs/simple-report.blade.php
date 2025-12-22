<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #0f172a; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        .table th { background-color: #f8fafc; color: #0f172a; font-weight: bold; }
        .highlight { color: #dc2626; font-weight: bold; }
        .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">HOUSE <span style="color:#1e3a8a">TEAM</span></div>
        <h2>{{ $title }}</h2>
        <p>Data: {{ $date }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th colspan="2">Dados da Simulação</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $value)
                @if(!in_array($key, ['lead_name', 'lead_email', '_token']))
                <tr>
                    <td style="text-transform: capitalize; width: 50%;">{{ str_replace('_', ' ', $key) }}</td>
                    <td class="highlight">{{ is_numeric($value) ? number_format((float)$value, 2, ',', '.') : $value }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Documento informativo. Não dispensa a consulta de profissionais especializados.</p>
        <p>www.houseteamconsultores.pt</p>
    </div>
</body>
</html>