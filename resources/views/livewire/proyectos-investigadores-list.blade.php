<table class="w-full text-sm text-left text-gray-700 border border-gray-300 rounded-md overflow-hidden">
    <thead class="bg-gray-400">
        <tr>
            <th class="px-3 py-2 border-b">Proyecto</th>
            <th class="px-3 py-2 border-b">Denominación</th>
            <th class="px-3 py-2 border-b">Función</th>
            <th class="px-3 py-2 border-b">Vigente</th>
            <th class="px-3 py-2 border-b">Disposición</th>
            <th class="px-3 py-2 border-b">Resolución</th>
        </tr>
    </thead>
    <tbody class="bg-gray-100">
        @foreach ($investigador->proyectos as $proyecto)
            <tr>
                <td class="px-3 py-2 border-b">
                    {{ $proyecto->nro }}
                </td>
                <td class="px-3 py-2 border-b">
                    {{ $proyecto->nombre }}
                </td>
                <td class="px-3 py-2 border-b">
                    {{ $proyecto->pivot->funcion->nombre ?? '—' }}
                </td>
                <td class="px-3 py-2 border-b">
                    @if ($proyecto->pivot->vigente)
                        <span class="text-green-600 font-medium">Vigente</span>
                    @else
                        <span class="text-red-600 font-medium">No Vigente</span>
                    @endif
                </td>
                <td class="px-3 py-2 border border-gray-300 space-y-1">
                    @foreach ($proyecto->pdf_disposicion ?? [] as $pdf)
                        <a href="{{ Storage::url($pdf) }}" target="_blank" class="text-blue-600 underline block">
                            Ver Archivo
                        </a>
                    @endforeach
                </td>
                <td class="px-3 py-2 border border-gray-300 space-y-1">
                    @foreach ($proyecto->pdf_resolucion ?? [] as $pdf)
                        <a href="{{ Storage::url($pdf) }}" target="_blank" class="text-purple-600 underline block">
                            Ver Archivo
                        </a>
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
