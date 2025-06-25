@php
    $becarios = $proyecto->becarios ?? collect();
@endphp

<table class="w-full text-sm text-left text-gray-700 border border-gray-300 rounded-md overflow-hidden">
    <thead class="bg-gray-400">
        <tr>
            <th class="px-3 py-2 border border-gray-300">Becario</th>
            <th class="px-3 py-2 border border-gray-300">DNI</th>
            <th class="px-3 py-2 border border-gray-300">Disposición</th>
            <th class="px-3 py-2 border border-gray-300">Resolución</th>
        </tr>
    </thead>
    <tbody class="bg-gray-100">
        @foreach ($becarios as $becario)
            {{-- Fila principal --}}
            <tr>
                <td class="px-3 py-2 border border-gray-300">
                    {{ $becario->apellido }}, {{ $becario->nombre }}
                </td>
                <td class="px-3 py-2 border border-gray-300">
                    {{ $becario->dni }}
                </td>
                <td class="px-3 py-2 border border-gray-300 space-y-1">
                    @foreach ($becario->pivot->pdf_disposicion ?? [] as $pdf)
                        <a href="{{ Storage::url($pdf) }}" target="_blank" class="text-blue-600 underline block">
                            Ver Archivo
                        </a>
                    @endforeach
                </td>
                <td class="px-3 py-2 border border-gray-300 space-y-1">
                    @foreach ($becario->pivot->pdf_resolucion ?? [] as $pdf)
                        <a href="{{ Storage::url($pdf) }}" target="_blank" class="text-purple-600 underline block">
                            Ver Archivo
                        </a>
                    @endforeach
                </td>
            </tr>

            {{-- Fila secundaria con información adicional --}}
            <tr class="bg-gray-200">
                <td colspan="4" class="px-3 py-2 border border-gray-300">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <span class="font-semibold">Convocatoria:</span>
                            {{ $becario->pivot->convocatoria?->anio ?? '—' }}
                        </div>
                        <div>
                            <span class="font-semibold">Tipo de Beca:</span>
                            {{ $becario->pivot->tipo_beca ?? '—' }}
                        </div>
                        <div>
                            <span class="font-semibold">Plan de Trabajo:</span>
                            {{ $becario->plan_trabajo ? \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($becario->plan_trabajo)), 150) : '—' }}
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
