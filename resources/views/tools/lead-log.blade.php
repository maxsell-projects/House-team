<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Lead - House Team</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; color: #1f2937; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); overflow: hidden; }
        .header { background-color: #111827; color: white; padding: 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .header p { margin: 8px 0 0; color: #9ca3af; font-size: 14px; }
        .content { padding: 32px; }
        .section-title { font-size: 12px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin-top: 24px; }
        .section-title:first-child { margin-top: 0; }
        .grid { display: grid; grid-template-columns: 1fr; gap: 16px; }
        @media (min-width: 640px) { .grid { grid-template-columns: 1fr 1fr; } }
        .item { background: #f9fafb; padding: 12px; border-radius: 8px; border: 1px solid #f3f4f6; }
        .label { display: block; font-size: 12px; color: #6b7280; margin-bottom: 4px; font-weight: 600; }
        .value { font-size: 16px; font-weight: 500; color: #111827; word-break: break-word; }
        .footer { background-color: #f9fafb; padding: 16px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 9999px; font-size: 12px; font-weight: 600; background-color: #e0e7ff; color: #4338ca; }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>Ficha de Oportunidade</h1>
            <p>Gerado automaticamente pelo Sistema House Team</p>
        </div>

        <div class="content">
            <div class="section-title">Dados do Cliente</div>
            <div class="grid">
                <div class="item">
                    <span class="label">Nome</span>
                    <div class="value">{{ $data['contact']['lead_name'] ?? '-' }}</div>
                </div>
                <div class="item">
                    <span class="label">Email</span>
                    <div class="value">{{ $data['contact']['lead_email'] ?? '-' }}</div>
                </div>
                <div class="item">
                    <span class="label">Telefone</span>
                    <div class="value">{{ $data['contact']['lead_phone'] ?? '-' }}</div>
                </div>
                <div class="item">
                    <span class="label">Data de Envio</span>
                    <div class="value">{{ $data['date'] ?? date('d/m/Y H:i') }}</div>
                </div>
            </div>

            <div class="section-title">Detalhes da Simulação / Pedido</div>
            <div class="grid">
                @foreach($data['info'] as $key => $value)
                    <div class="item" style="{{ strlen($value) > 50 ? 'grid-column: span 2;' : '' }}">
                        <span class="label">{{ $key }}</span>
                        <div class="value">
                            @if($loop->first && isset($data['type'])) 
                                <span class="badge">{{ $value }}</span>
                            @else
                                {!! nl2br(e($value)) !!}
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if(isset($data['consultant']))
            <div class="section-title">Contexto</div>
            <div class="item">
                <span class="label">Consultor Responsável (Origem)</span>
                <div class="value">{{ $data['consultant'] }}</div>
            </div>
            @endif
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} House Team - Uso Interno Exclusivo.
        </div>
    </div>

</body>
</html>