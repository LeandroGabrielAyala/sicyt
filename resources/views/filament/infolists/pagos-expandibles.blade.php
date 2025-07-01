@props(['record'])

<div x-data class="w-full text-sm text-left bg-[#0f172a] rounded-xl shadow-md overflow-hidden" style="border: 1px solid #ffffff1a; color: #8c9aaf; max-width: 900px; margin: auto;">
    <table class="w-full table-fixed border-collapse" style="border-color: #ffffff1a; table-layout: fixed;">
        <thead style="color: #6670c5; background-color: #19213a;">
            <tr>
                <th class="px-4 py-3 border-b border-r" style="border-color: #ffffff1a; width: 10%;">Año</th>
                <th class="px-4 py-3 border-b border-r" style="border-color: #ffffff1a; width: 15%;">Mes</th>
                <th class="px-4 py-3 border-b border-r" style="border-color: #ffffff1a; width: 40%;">Convocatoria</th>
                <th class="px-4 py-3 border-b border-r" style="border-color: #ffffff1a; width: 15%;">Tipo</th>
                <th class="px-4 py-3 border-b" style="border-color: #ffffff1a; width: 20%;">$ Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $pago = $record;
            @endphp
            <tr style="border-bottom: 1px solid #ffffff1a;">
                <td class="px-4 py-3 border-r" style="border-color: #ffffff1a;">
                    {{ $pago->anio }}
                </td>
                <td class="px-4 py-3 border-r" style="border-color: #ffffff1a;">
                    {{ $pago->mes }}
                </td>
                <td class="px-4 py-3 border-r truncate" style="border-color: #ffffff1a;" title="{{ $pago->convocatoriaBeca?->descripcion }}">
                    {{ $pago->convocatoriaBeca?->descripcion ?? '—' }}
                </td>
                <td class="px-4 py-3 border-r" style="border-color: #ffffff1a;">
                    {{ $pago->tipo_beca }}
                </td>
                <td class="px-4 py-3" style="border-color: #ffffff1a; font-weight: 600; color: #6670c5;">
                    $ {{ number_format($pago->becariosSumMonto, 2, ',', '.') }}
                </td>
            </tr>

            
            <tr class="bg-[#19213a]" style="border-bottom: 1px solid #ffffff1a;">
                <td colspan="5" class="px-4 py-4 border-t rounded-b-xl" style="border-color: #ffffff1a; color: #8c9aaf;">
                    <table class="w-full table-fixed border-collapse rounded-lg overflow-hidden" style="border-color: #ffffff1a; table-layout: fixed;">
                        <thead style="color: #6670c5; background-color: #19213a;">
                            <tr>
                                <th class="px-4 py-3 border-b border-r" style="border-color: #ffffff1a; width: 70%; font-weight: 600; user-select: none; text-align: left; border-top-left-radius: 0.75rem;">
                                    Becario/a
                                </th>
                                <th class="px-4 py-3 border-b" style="border-color: #ffffff1a; width: 30%; font-weight: 600; user-select: none; text-align: right; border-top-right-radius: 0.75rem;">
                                    Monto Pagado
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pago->becariosPivot as $pivot)
                                <tr>
                                    <td colspan="2" class="px-4 py-2" style="border-color: #ffffff1a;">
                                        <div style="display: flex; align-items: center;">
                                            <div style="flex-shrink: 0;">
                                                {{ $pivot->becario->nombre_completo ?? '—' }}
                                            </div>
                                            <div style="flex-grow: 1; height: 1px; background-color: #6670c5; margin: 0 8px;"></div>
                                            <div style="flex-shrink: 0; text-align: right; min-width: 90px;">
                                                $ {{ number_format($pivot->monto, 2, ',', '.') }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>

        </tbody>
    </table>
</div>
