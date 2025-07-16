@php
    $adsDirector = $investigador->adscriptosComoDirector()->with([
        'adscripto', 'proyecto.investigadorDirector', 'proyecto.investigadorCodirector', 'convocatoria'
    ])->get();

    $adsCodirector = $investigador->adscriptosComoCodirector()->with([
        'adscripto', 'proyecto.investigadorDirector', 'proyecto.investigadorCodirector', 'convocatoria'
    ])->get();

    $adscriptoProyectos = $adsDirector->merge($adsCodirector)->unique('id')->sortByDesc('vigente');
@endphp

<div x-data="{ openId: null }" class="w-full text-sm text-left bg-[#0f172a] rounded-xl shadow-md overflow-hidden" style="border: 1px solid #ffffff1a; color: #8c9aaf;">
    <table class="w-full border-collapse" style="border-color: #ffffff1a;">
        <thead style="color: #6670c5; background-color: #19213a;">
            <tr>
                <th class="px-3 py-2 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Adscripto</th>
                <th class="px-3 py-2 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">N° PI</th>
                <th class="px-3 py-2 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Convocatoria</th>
                <th class="px-3 py-2 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Función</th>
                <th class="px-3 py-2 border-b" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Vigencia</th>
            </tr>
        </thead>
        <tbody>
            @forelse($adscriptoProyectos as $ap)
                <tr
                    class="cursor-pointer hover:bg-[#19213a] transition-colors duration-200"
                    style="border-bottom: 1px solid #ffffff1a;"
                    @click="openId = openId === {{ $ap->id }} ? null : {{ $ap->id }}"
                >
                    <td class="px-3 py-2 border-r">{{ $ap->adscripto->apellido }}, {{ $ap->adscripto->nombre }}</td>
                    <td class="px-3 py-2 border-r">{{ $ap->proyecto->nro ?? '—' }}</td>
                    <td class="px-3 py-2 border-r">{{ $ap->convocatoria->anio ?? '—' }}</td>
                    <td class="px-3 py-2 border-r">
                        {{ $ap->director_id === $investigador->id ? 'Director' : 'Codirector' }}
                    </td>
                    <td class="px-3 py-2">
                        {!! $ap->vigente ? '<span class="text-green-500 font-medium">Vigente</span>' : '<span class="text-red-500 font-medium">No Vigente</span>' !!}
                    </td>
                </tr>

                <tr x-show="openId === {{ $ap->id }}" x-transition class="bg-[#19213a]" style="border-bottom: 1px solid #ffffff1a;">
                    <td colspan="6" class="px-4 py-4 space-y-4" style="color: #8c9aaf;">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <span class="font-semibold text-indigo-400">Director PI:</span><br>
                                {{ optional($ap->proyecto->investigadorDirector->first())->apellido_nombre ?? '—' }}
                            </div>
                            <div>
                                <span class="font-semibold text-indigo-400">Codirector PI:</span><br>
                                {{ optional($ap->proyecto->investigadorCodirector->first())->apellido_nombre ?? '—' }}
                            </div>
                            <div>
                                <span class="font-semibold text-indigo-400">Estado PI:</span><br>
                                {{ $ap->proyecto->estado === 1 ? 'Vigente' : 'No vigente' }}
                            </div>
                        </div>

                        <div>
                            <span class="font-semibold text-indigo-400">Denominación del Proyecto:</span><br>
                            {!! $ap->proyecto->nombre ? strip_tags(html_entity_decode($ap->proyecto->nombre)) : '—' !!}
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-3 py-4 text-center text-gray-400">No tiene adscriptos a cargo como Director ni Codirector.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
