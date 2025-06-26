<div x-data="{ openId: null }" class="w-full text-sm text-left bg-[#0f172a] rounded-xl shadow-md overflow-hidden" style="border: 1px solid #ffffff1a; color: #8c9aaf;">
    <table class="w-full table-fixed border-collapse" style="border-color: #ffffff1a;">
        <thead style="color: #6670c5; background-color: #19213a;">
            <tr>
                <th class="px-4 py-3 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">PI - Plan de Trabajo</th>
                <th class="px-4 py-3 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Convocatoria</th>
                <th class="px-4 py-3 border-b" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Vigente</th>
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
                        <b style="color: #6670c5;">{{ $proyecto->nro }} - </b> {{ $becario->plan_trabajo ? strip_tags(html_entity_decode($becario->plan_trabajo)) : '—' }}
                    </td>
                    <td class="px-4 py-3 border-r" style="border-color: #ffffff1a;">
                        {{ $proyecto->pivot->convocatoria?->tipoBeca?->nombre ?? '—' }} {{ $proyecto->pivot->convocatoria?->anio ?? '—' }}
                    </td>
                    <td class="px-4 py-3 border-r" style="border-color: #ffffff1a;">
                        {{ $proyecto->estado === 1 ? 'Vigente' : 'No vigente' }}
                    </td>



                    {{-- <td class="px-4 py-3" style="border-color: #ffffff1a;">
                        @foreach ($proyecto->pdf_disposicion ?? [] as $pdf)
                            <a href="{{ Storage::url($pdf) }}" target="_blank" class="underline block text-indigo-400 hover:text-indigo-600 transition-colors duration-150">Disposición</a>
                        @endforeach
                        @foreach ($proyecto->pdf_resolucion ?? [] as $pdf)
                            <a href="{{ Storage::url($pdf) }}" target="_blank" class="underline block text-purple-400 hover:text-purple-600 transition-colors duration-150">Resolución</a>
                        @endforeach
                    </td> --}}
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
                                <span style="color: #6670c5; font-weight: 600;">Tipo de Beca:</span><br>
                                {{ $proyecto->pivot->tipo_beca ? strip_tags(html_entity_decode($proyecto->pivot->tipo_beca)) : '—' }}
                            </div>
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
