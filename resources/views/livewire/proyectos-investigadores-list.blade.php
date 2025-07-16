<div x-data="{ openId: null }" class="w-full text-sm text-left bg-[#0f172a] rounded-xl shadow-md overflow-hidden" style="border: 1px solid #ffffff1a; color: #8c9aaf;">
    <table class="w-full border-collapse" style="border-color: #ffffff1a;">
        <thead style="color: #6670c5; background-color: #19213a;">
            <tr>
                <th class="px-3 py-2 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Proyecto</th>
                <th class="px-3 py-2 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Función</th>
                <th class="px-3 py-2 border-b border-r" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Vigencia en PI</th>
                <th class="px-3 py-2 border-b" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Documentación</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($investigador->proyectos as $proyecto)
                <tr
                    class="cursor-pointer hover:bg-[#19213a] transition-colors duration-200"
                    style="border-bottom: 1px solid #ffffff1a; color: #8c9aaf;"
                    @click="openId = openId === {{ $proyecto->id }} ? null : {{ $proyecto->id }}"
                >
                    <td class="px-3 py-2 border-r" style="border-color: #ffffff1a;">{{ $proyecto->nro }}</td>
                    <td class="px-3 py-2 border-r" style="border-color: #ffffff1a;">{{ $proyecto->pivot->funcion->nombre ?? '—' }}</td>
                    <td class="px-3 py-2 border-r" style="border-color: #ffffff1a;">
                        @if ($proyecto->pivot->vigente)
                            <span class="text-green-500 font-medium">Vigente</span>
                        @else
                            <span class="text-red-500 font-medium">No Vigente</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 border-r space-y-1" style="border-color: #ffffff1a;">
                        @foreach ($proyecto->pivot->pdf_disposicion ?? [] as $pdf)
                            <a href="{{ Storage::url($pdf) }}" target="_blank" class="underline text-indigo-400 hover:text-indigo-600 block transition-colors duration-150">Disposición</a>
                        @endforeach
                        @foreach ($proyecto->pivot->pdf_resolucion ?? [] as $pdf)
                            <a href="{{ Storage::url($pdf) }}" target="_blank" class="underline text-purple-400 hover:text-purple-600 block transition-colors duration-150">Resolución</a>
                        @endforeach
                    </td>
                </tr>

                <tr x-show="openId === {{ $proyecto->id }}" x-transition class="bg-[#19213a]" style="border-bottom: 1px solid #ffffff1a;">
                    <td colspan="5" class="px-4 py-4 border-t space-y-4" style="border-color: #ffffff1a; color: #8c9aaf;">
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
                        </div>

                        <div class="mt-4">
                            <span style="color: #6670c5; font-weight: 600;">Denominación:</span><br>
                            {{ $proyecto->nombre ? strip_tags(html_entity_decode($proyecto->nombre)) : '—' }}
                        </div>

                        <div class="mt-4">
                            <span style="color: #6670c5; font-weight: 600;">Plan de trabajo:</span><br>
                            <div class="whitespace-pre-wrap">{{ $proyecto->pivot->plan_trabajo ?? 'Sin plan de trabajo registrado.' }}</div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
