@php
    $becarios = $proyecto->becarios ?? collect();
@endphp

<div x-data="{ openId: null }" class="w-full text-sm text-left bg-[#0f172a] rounded-xl shadow-md overflow-hidden" style="border: 1px solid #ffffff1a; color: #8c9aaf;">
    <table class="w-full table-fixed border-collapse" style="border-color: #ffffff1a;">
        <thead style="color: #6670c5; background-color: #19213a;">
            <tr>
                <th class="px-3 py-2 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Becario</th>
                <th class="px-3 py-2 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">DNI</th>
                <th class="px-3 py-2 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Convocatoria</th>
                <th class="px-3 py-2 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Tipo</th>
                <th class="px-3 py-2 border-b" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Documentación</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($becarios as $becario)
                <tr 
                    class="cursor-pointer hover:bg-[#19213a] transition-colors duration-200" 
                    style="border-bottom: 1px solid #ffffff1a;" 
                    @click="openId = openId === {{ $becario->id }} ? null : {{ $becario->id }}"
                >
                    <td class="px-3 py-2 border-r" style="border-color: #ffffff1a;">
                        {{ $becario->apellido }}, {{ $becario->nombre }}
                    </td>
                    <td class="px-3 py-2 border-r" style="border-color: #ffffff1a;">
                        {{ $becario->dni }}
                    </td>
                    <td class="px-3 py-2 border-r" style="border-color: #ffffff1a;">
                        {{ $becario->pivot->convocatoria?->tipoBeca?->nombre ?? '—' }} {{ $becario->pivot->convocatoria?->anio ?? '—' }}
                    </td>
                    <td class="px-3 py-2 border-r" style="border-color: #ffffff1a;">
                        {{ $becario->pivot->tipo_beca ?? '—' }}
                    </td>
                    <td class="px-3 py-2" style="border-color: #ffffff1a;">
                        @foreach ($proyecto->pdf_disposicion ?? [] as $pdf)
                            <a href="{{ Storage::url($pdf) }}" target="_blank" class="underline text-indigo-400 hover:text-indigo-600 block transition-colors duration-150">Disposición</a>
                        @endforeach
                        @foreach ($proyecto->pdf_resolucion ?? [] as $pdf)
                            <a href="{{ Storage::url($pdf) }}" target="_blank" class="underline text-purple-400 hover:text-purple-600 block transition-colors duration-150">Resolución</a>
                        @endforeach
                    </td>
                </tr>

                {{-- Fila expandida con detalles --}}
                <tr x-show="openId === {{ $becario->id }}" x-transition class="bg-[#19213a]" style="border-bottom: 1px solid #ffffff1a;">
                    <td colspan="5" class="px-3 py-2 border-t" style="border-color: #ffffff1a; color: #8c9aaf;">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full">
                            <div>
                                <span style="color: #6670c5; font-weight: 600;">Director Beca:</span><br>
                                {{ $becario->pivot->director?->apellido_nombre ?? '—' }}
                            </div>
                            <div>
                                <span style="color: #6670c5; font-weight: 600;">Co-director Beca:</span><br>
                                {{ $becario->pivot->codirector?->apellido_nombre ?? '—' }}
                            </div>
                        </div><br>
                        <div class="mt-4">
                            <span style="color: #6670c5; font-weight: 600;">Plan de Trabajo:</span><br>
                            {{ $becario->plan_trabajo ? strip_tags(html_entity_decode($becario->plan_trabajo)) : '—' }}
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
