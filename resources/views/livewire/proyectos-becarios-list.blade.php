<div x-data="{ openId: null }" class="w-full text-sm text-left bg-[#0f172a] rounded-xl shadow-md overflow-hidden" style="border: 1px solid #ffffff1a; color: #8c9aaf;">
    <table class="w-full table-fixed border-collapse" style="border-color: #ffffff1a;">
        <thead style="color: #6670c5; background-color: #19213a;">
            <tr>
                <th class="px-4 py-3 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">PI</th>
                <th class="px-4 py-3 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Convocatoria</th>
                <th class="px-4 py-3 border-b" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Tipo</th>
                <th class="px-4 py-3 border-b" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Vigente</th>
                <th class="px-4 py-3 border-b" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Resolución</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($becario->proyectos as $proyecto)
                <tr 
                    class="cursor-pointer hover:bg-[#19213a] transition-colors duration-200" 
                    style="color: #8c9aaf; border-bottom: 1px solid #ffffff1a;"
                    @click="openId = openId === {{ $proyecto->id }} ? null : {{ $proyecto->id }}"
                >
                    <td class="px-4 py-3 border-r" style="border-color: #ffffff1a;">
                        <b style="color: #6670c5;">{{ $proyecto->nro }}</b>
                    </td>
                    <td class="px-4 py-3 border-r" style="border-color: #ffffff1a;">
                        {{ $proyecto->pivot->convocatoria?->tipoBeca?->nombre ?? '—' }} {{ $proyecto->pivot->convocatoria?->anio ?? '—' }}
                    </td>
                    <td class="px-4 py-3 border-r" style="border-color: #ffffff1a;">
                        {{ $proyecto->pivot->tipo_beca ? strip_tags(html_entity_decode($proyecto->pivot->tipo_beca)) : '—' }}
                    </td>
                    <td class="px-4 py-3 border-r" style="border-color: #ffffff1a;">
                        {{ $proyecto->pivot->vigente ? '✔️ Vigente' : '❌ No vigente' }}
                    </td>
                    <td class="px-4 py-3 border-r" style="border-color: #ffffff1a;">
                        {{ $proyecto->pivot->convocatoria?->resolucion ?? '—' }}
                    </td>
                </tr>

                <tr x-show="openId === {{ $proyecto->id }}" x-transition class="bg-[#19213a]" style="border-bottom: 1px solid #ffffff1a;">
                    <td colspan="4" class="px-4 py-4 border-t" style="border-color: #ffffff1a; color: #8c9aaf;">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <span style="color: #6670c5; font-weight: 600;">Director Beca:</span><br>
                                {{ $proyecto->pivot->director?->apellido_nombre ?? '—' }}
                            </div>
                            <div>
                                <span style="color: #6670c5; font-weight: 600;">Codirector Beca:</span><br>
                                {{ $proyecto->pivot->codirector?->apellido_nombre ?? '—' }}
                            </div>
                            <div>
                                <span style="color: #6670c5; font-weight: 600;">Documentación:</span><br>
                                @foreach ($proyecto->pivot->convocatoria?->pdf_disposicion ?? [] as $pdf)
                                    <a href="{{ Storage::url($pdf) }}" target="_blank" class="underline block text-blue-400 hover:text-blue-600 transition-colors duration-150">Disposición</a>
                                @endforeach
                                @foreach ($proyecto->pivot->convocatoria?->pdf_resolucion ?? [] as $pdf)
                                    <a href="{{ Storage::url($pdf) }}" target="_blank" class="underline block text-green-400 hover:text-green-600 transition-colors duration-150">Resolución</a>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-4">
                            <span style="color: #6670c5; font-weight: 600;">Plan de Trabajo:</span><br>
                            {{ $becario->plan_trabajo ? strip_tags(html_entity_decode($becario->plan_trabajo)) : '—' }}
                        </div>
                    </td>
                </tr>

                <tr x-show="openId === {{ $proyecto->id }}" x-transition class="bg-[#19213a]" style="border-bottom: 1px solid #ffffff1a;">
                    <td colspan="4" class="px-4 py-4 border-t" style="border-color: #ffffff1a; color: #8c9aaf;">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <span style="color: #6670c5; font-weight: 600;">Director PI:</span><br>
                                {{ $proyecto->investigadorDirector->first()?->apellido_nombre ?? '—' }}
                            </div>
                            <div>
                                <span style="color: #6670c5; font-weight: 600;">Codirector PI:</span><br>
                                {{ $proyecto->investigadorCodirector->first()?->apellido_nombre ?? '—' }}
                            </div>
                            <div>
                                <span style="color: #6670c5; font-weight: 600;">Vigente PI:</span><br>
                                {{ $proyecto->estado === 1 ? 'Vigente' : 'No vigente' }}
                            </div>
                        </div><br>
                        <div class="mt-4">
                            <span style="color: #6670c5; font-weight: 600;">Denominación:</span><br>
                            {{ $proyecto->nombre ? strip_tags(html_entity_decode($proyecto->nombre)) : '—' }}
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
