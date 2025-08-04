@php
    $adscriptos = $proyecto->adscriptos()
        ->with(['carrera', 'titulo']) // Relación directa del modelo Adscripto
        ->get()
        ->sortByDesc(fn ($adscripto) => $adscripto->pivot?->vigente); // Orden manual usando el campo pivot
@endphp

<div x-data="{ openId: null }" class="w-full text-sm text-left bg-[#0f172a] rounded-xl shadow-md overflow-hidden" style="border: 1px solid #ffffff1a; color: #8c9aaf;">
    <table class="w-full border-collapse" style="border-color: #ffffff1a;">
        <thead style="color: #6670c5; background-color: #19213a;">
            <tr>
                <th class="px-3 py-2 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Adscripto</th>
                <th class="px-3 py-2 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">DNI</th>
                <th class="px-3 py-2 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Convocatoria</th>
                <th class="px-3 py-2 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Vigencia</th>
                <th class="px-3 py-2 border-b" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Documentación</th>
            </tr>
        </thead>
        <tbody>
            @forelse($adscriptos as $adscripto)
                <tr
                    class="cursor-pointer hover:bg-[#19213a] transition-colors duration-200"
                    @click="openId = openId === {{ $adscripto->id }} ? null : {{ $adscripto->id }}"
                    style="border-bottom: 1px solid #ffffff1a;"
                >
                    <td class="px-3 py-2 border-r">{{ $adscripto->apellido }}, {{ $adscripto->nombre }}</td>
                    <td class="px-3 py-2 border-r">{{ $adscripto->dni }}</td>
                    <td class="px-3 py-2 border-r">{{ $adscripto->pivot->convocatoria?->anio ?? '—' }}</td>
                    <td class="px-3 py-2">
                        {!! $adscripto->pivot->vigente
                            ? '<span class="text-green-500 font-medium">Vigente</span>'
                            : '<span class="text-red-500 font-medium">No Vigente</span>' !!}
                    </td>
                    <td class="px-3 py-2 space-x-2">
                        @foreach ($adscripto->pivot->convocatoria?->pdf_disposicion ?? [] as $pdf)
                            <a href="{{ asset('storage/' . $pdf) }}" target="_blank" class="text-indigo-400 underline">Disposición</a>
                            <br>
                        @endforeach
                        @foreach ($adscripto->pivot->convocatoria?->pdf_resolucion ?? [] as $pdf)
                            <a href="{{ asset('storage/' . $pdf) }}" target="_blank" class="text-emerald-400 underline">Resolución</a>
                        @endforeach
                    </td>
                </tr>

                <tr x-show="openId === {{ $adscripto->id }}" x-transition class="bg-[#19213a]" style="border-bottom: 1px solid #ffffff1a;">
                    <td colspan="5" class="px-4 py-4 space-y-4" style="color: #8c9aaf;">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <span style="color: #6670c5; font-weight: 600;">Director del Adscripto:</span><br>
                                {{ $adscripto->pivot->director?->apellido_nombre ?? '—' }}
                            </div>
                            <div>
                                <span style="color: #6670c5; font-weight: 600;">Codirector del Adscripto:</span><br>
                                {{ $adscripto->pivot->codirector?->apellido_nombre ?? '—' }}
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-3 py-4 text-center text-gray-400">No hay adscriptos asociados a este proyecto.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
