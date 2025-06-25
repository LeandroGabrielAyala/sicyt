@php
    // IDs de funciones a excluir: Director y Codirector
    $funcionesExcluir = \App\Models\Funcion::whereIn('nombre', ['Director', 'Co-director'])->pluck('id')->toArray();
@endphp

<table class="w-full text-sm text-left text-gray-700 border border-gray-300 rounded-md overflow-hidden">
    <thead class="bg-gray-400">
        <tr>
            <th class="px-3 py-2 border border-gray-300">Integrantes</th>
            <th class="px-3 py-2 border border-gray-300">Función</th>
            <th class="px-3 py-2 border border-gray-300">Estado</th>
            <th class="px-3 py-2 border border-gray-300">Disposición</th>
            <th class="px-3 py-2 border border-gray-300">Resolución</th>
        </tr>
    </thead>
    <tbody class="bg-gray-100">
        @foreach ($proyecto->investigador->whereNotIn('pivot.funcion_id', $funcionesExcluir) as $investigador)
            <tr>
                <td class="px-3 py-2 border border-gray-300">
                    {{ $investigador->apellido }}, {{ $investigador->nombre }}
                </td>
                <td class="px-3 py-2 border border-gray-300">
                    {{ optional($investigador->pivot->funcion)->nombre ?? '—' }}
                </td>
                <td class="px-3 py-2 border border-gray-300">
                    @if ($investigador->pivot->vigente)
                        <span class="text-green-600 font-medium">Vigente</span>
                    @else
                        <span class="text-red-600 font-medium">No Vigente</span>
                    @endif
                </td>
                <td class="px-3 py-2 border border-gray-300 space-y-1">
                    @foreach ($investigador->pivot->pdf_disposicion ?? [] as $pdf)
                        <a href="{{ Storage::url($pdf) }}" target="_blank" class="text-blue-600 underline block">
                            Ver Archivo
                        </a>
                    @endforeach
                </td>
                <td class="px-3 py-2 border border-gray-300 space-y-1">
                    @foreach ($investigador->pivot->pdf_resolucion ?? [] as $pdf)
                        <a href="{{ Storage::url($pdf) }}" target="_blank" class="text-purple-600 underline block">
                            Ver Archivo
                        </a>
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
