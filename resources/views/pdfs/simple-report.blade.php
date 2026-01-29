<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1e293b; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #c5a059; pb-20px; }
        h1 { color: #1e293b; margin: 0; font-size: 20px; }
        .row { margin-bottom: 8px; border-bottom: 1px dotted #eee; padding-bottom: 4px; }
        .label { font-weight: bold; width: 60%; display: inline-block; }
        .value { width: 35%; display: inline-block; text-align: right; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>House Team Consultores | {{ $date }}</p>
    </div>

    <div class="content">
        @foreach($data as $key => $value)
            @if(!in_array($key, ['lead_name', 'lead_email', 'lead_phone', '_token', 'g-recaptcha-response']))
                <div class="row">
                    <span class="label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                    <span class="value">{{ is_numeric($value) ? number_format($value, 2, ',', '.') : $value }}</span>
                </div>
            @endif
        @endforeach
    </div>

    <div class="footer">
        Gerado automaticamente por House Team Consultores
    </div>
</body>
</html>