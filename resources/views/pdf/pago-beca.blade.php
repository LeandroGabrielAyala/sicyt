<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nota SICyT</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h4 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h4>SECRETARÍA DE INVESTIGACIÓN, CIENCIA Y TÉCNICA</h4>
    <p>Nota SICyT Nº {{ $numeroNota ?? '___' }}<br>
    Pres. Roque Sáenz Peña, {{ now()->format('d') }} de {{ now()->translatedFormat('F') }} de {{ now()->year }}</p>

    <p>Al Rector de la<br>
    Universidad Nacional del Chaco Austral<br>
    Abog. Germán E. OESTMANN<br>
    S / D</p>

    <p>
        Me dirijo a usted con el fin de solicitar se efectivice el pago correspondiente al mes de <strong>{{ strtoupper($pago->mes) }} {{ $pago->anio }}</strong>
        a los becarios de <strong>{{ $pago->tipo_beca }}</strong> – {{ $pago->convocatoriaBeca?->descripcion ?? '' }}.
    </p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Apellido y Nombre</th>
                <th>DNI</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pago->becariosPivot as $i => $pivot)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $pivot->becario->apellido }}, {{ $pivot->becario->nombre }}</td>
                    <td>{{ $pivot->becario->dni }}</td>
                    <td>${{ number_format($pivot->monto, 2, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" align="right"><strong>Total</strong></td>
                <td><strong>${{ number_format($pago->becariosSumMonto, 2, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <p style="margin-top: 40px;">Sin otro particular, lo saludo atentamente.</p>
</body>
</html>
