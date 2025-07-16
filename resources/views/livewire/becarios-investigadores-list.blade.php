@php
    $becasComoDirector = $investigador->becariosComoDirector()->with(['becario', 'proyecto.investigadorDirector', 'proyecto.investigadorCodirector', 'convocatoria.tipoBeca'])->get();
    $becasComoCodirector = $investigador->becariosComoCodirector()->with(['becario', 'proyecto.investigadorDirector', 'proyecto.investigadorCodirector', 'convocatoria.tipoBeca'])->get();

    $becas = $becasComoDirector->merge($becasComoCodirector)->unique('id')->sortByDesc('vigente');
@endphp

<div x-data="{ openId: null }" class="w-full text-sm text-left bg-[#0f172a] rounded-xl shadow-md overflow-hidden" style="border: 1px solid #ffffff1a; color: #8c9aaf;">
    <table class="w-full border-collapse" style="border-color: #ffffff1a;">
        <thead style="color: #6670c5; background-color: #19213a;">
            <tr>
                <th class="px-3 py-2 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Becario</th>
                <th class="px-3 py-2 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">N° PI</th>
                <th class="px-3 py-2 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Convocatoria</th>
                <th class="px-3 py-2 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Tipo</th>
                <th class="px-3 py-2 border-b" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Vigencia</th>
            </tr>
        </thead>
        <tbody>
            @forelse($becas as $bp)
                <tr
                    class="cursor-pointer hover:bg-[#19213a] transition-colors duration-200"
                    style="border-bottom: 1px solid #ffffff1a; color: #8c9aaf;"
                    @click="openId = openId === {{ $bp->id }} ? null : {{ $bp->id }}"
                >
                    <td class="px-3 py-2 border-r" style="border-color: #ffffff1a;">
                        {{ $bp->becario->apellido }}, {{ $bp->becario->nombre }}
                    </td>
                    <td class="px-3 py-2 border-r" style="border-color: #ffffff1a;">
                        {{ $bp->proyecto->nro ?? '—' }}
                    </td>
                    <td class="px-3 py-2 border-r" style="border-color: #ffffff1a;">
                        {{ $bp->convocatoria->tipoBeca->nombre ?? '-' }} ({{ $bp->convocatoria->anio }})
                    </td>
                    <td class="px-3 py-2 border-r" style="border-color: #ffffff1a;">
                        {{ $bp->tipo_beca }}
                    </td>
                    <td class="px-3 py-2" style="border-color: #ffffff1a;">
                        {!! $bp->vigente ? '<span class="text-green-500 font-medium">Vigente</span>' : '<span class="text-red-500 font-medium">No Vigente</span>' !!}
                    </td>
                </tr>

                <!-- Detalle: Plan + Proyecto -->
                <tr x-show="openId === {{ $bp->id }}" x-transition class="bg-[#19213a]" style="border-bottom: 1px solid #ffffff1a;">
                    <td colspan="5" class="px-4 py-4 border-t space-y-4" style="border-color: #ffffff1a; color: #8c9aaf;">
                        <div class="mt-4 space-y-2">
                            <div>
                                <span style="color: #6670c5; font-weight: 600;">Plan de Trabajo de la Beca:</span><br>
                                {{ $bp->plan_trabajo }}
                            </div>

                            <div>
                                <span style="color: #6670c5; font-weight: 600;">Función del Investigador:</span><br>
                                {{ $bp->director_id === $investigador->id ? 'Director' : ($bp->codirector_id === $investigador->id ? 'Codirector' : '—') }}
                            </div>
                        </div>
                    </td>
                </tr>

                <!-- Detalle: Plan + Proyecto -->
                <tr x-show="openId === {{ $bp->id }}" x-transition class="bg-[#19213a]" style="border-bottom: 1px solid #ffffff1a;">
                    <td colspan="5" class="px-4 py-4 border-t space-y-4" style="border-color: #ffffff1a; color: #8c9aaf;">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div>
                                <span style="color: #6670c5; font-weight: 600;">Director PI:</span><br>
                                {{ optional($bp->proyecto->investigadorDirector->first())->apellido_nombre ?? '—' }}
                            </div>
                            <div>
                                <span style="color: #6670c5; font-weight: 600;">Codirector PI:</span><br>
                                {{ optional($bp->proyecto->investigadorCodirector->first())->apellido_nombre ?? '—' }}
                            </div>
                            <div>
                                <span style="color: #6670c5; font-weight: 600;">Vigente PI:</span><br>
                                {{ $bp->proyecto->estado === 1 ? 'Vigente' : 'No vigente' }}
                            </div>
                        </div>

                        <div class="mt-4">
                            <span style="color: #6670c5; font-weight: 600;">Denominación:</span><br>
                            {!! $bp->proyecto->nombre ? strip_tags(html_entity_decode($bp->proyecto->nombre)) : '—' !!}
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-3 py-4 text-center text-gray-400">No tiene becarios a cargo como Director ni Codirector.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
