<div class="w-full text-sm text-left text-gray-700 border border-gray-300 rounded-md overflow-hidden">
    {{-- Tabla 1 --}}
    <table class="w-full">
        <thead class="bg-gray-400">
            <tr>
                <th class="px-3 py-2 border-b">Nro.</th>
                <th class="px-3 py-2 border-b">Denominación</th>
                <th class="px-3 py-2 border-b">Director PI</th>
                <th class="px-3 py-2 border-b">Codirector PI</th>
                <th class="px-3 py-2 border-b">Vigente</th>
                <th class="px-3 py-2 border-b">Dispo. & Resol.</th>
            </tr>
        </thead>
        <tbody class="bg-gray-100">
            @foreach ($becario->proyectos as $proyecto)
                <tr>
                    <td class="px-3 py-2 border-b">{{ $proyecto->nro }}</td>
                    <td class="px-3 py-2 border-b">{{ $proyecto->nombre }}</td>
                    <td class="px-3 py-2 border-b">{{ $proyecto->pivot->director?->apellido_nombre ?? '—' }}</td>
                    <td class="px-3 py-2 border-b">{{ $proyecto->pivot->codirector?->apellido_nombre ?? '—' }}</td>
                    <td class="px-3 py-2 border-b">
                        @if ($proyecto->estado == 1 || $proyecto->estado === true)
                            <span class="text-green-600 font-medium">Vigente</span>
                        @else
                            <span class="text-red-600 font-medium">No Vigente</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 border border-gray-300 space-y-1">
                        @foreach ($proyecto->pdf_disposicion ?? [] as $pdf)
                            <a href="{{ Storage::url($pdf) }}" target="_blank" class="text-blue-600 underline block">Ver</a>
                        @endforeach
                        @foreach ($proyecto->pdf_resolucion ?? [] as $pdf)
                            <a href="{{ Storage::url($pdf) }}" target="_blank" class="text-purple-600 underline block">Ver</a>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Tabla 2 --}}
    <table class="w-full border-t border-gray-300">
        <thead class="bg-gray-400">
            <tr>
                <th class="px-3 py-2 border-b">Plan de Trabajo</th>
                <th class="px-3 py-2 border-b">Director Beca</th>
                <th class="px-3 py-2 border-b">Co-director Beca</th>
            </tr>
        </thead>
        <tbody class="bg-gray-100">
            @foreach ($becario->proyectos as $proyecto)
                <tr>
                    <td class="px-3 py-2 border-b">
                        {{ strip_tags($becario->plan_trabajo) ?? '—' }}
                    </td>
                    <td class="px-3 py-2 border-b">
                        {{ $proyecto->pivot->director?->apellido_nombre ?? '—' }}
                    </td>
                    <td class="px-3 py-2 border-b">
                        {{ $proyecto->pivot->codirector?->apellido_nombre ?? '—' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
