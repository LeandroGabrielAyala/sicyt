<div x-data="{ openId: null }" class="w-full text-sm text-left text-gray-700 border border-gray-300 rounded-md overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-400">
            <tr>
                <th class="px-3 py-2 border-b">P.I.</th>
                <th class="px-3 py-2 border-b">Director PI</th>
                <th class="px-3 py-2 border-b">Codirector PI</th>
                <th class="px-3 py-2 border-b">Documentación</th>
            </tr>
        </thead>
        <tbody class="bg-gray-100">
            @foreach ($becario->proyectos as $proyecto)
                <tr 
                    class="cursor-pointer hover:bg-gray-300"
                    @click="openId = openId === {{ $proyecto->id }} ? null : {{ $proyecto->id }}"
                >
                    <td class="px-3 py-2 border-b"><b>{{ $proyecto->nro }}:</b> {{ $proyecto->nombre }}</td>
                    <td class="px-3 py-2 border-b">{{ $proyecto->pivot->director?->apellido_nombre ?? '—' }}</td>
                    <td class="px-3 py-2 border-b">{{ $proyecto->pivot->codirector?->apellido_nombre ?? '—' }}</td>
                    <td class="px-3 py-2 border border-gray-300 space-y-1">
                        @foreach ($proyecto->pdf_disposicion ?? [] as $pdf)
                            <a href="{{ Storage::url($pdf) }}" target="_blank" class="text-blue-600 underline block">Disposición</a>
                        @endforeach
                        @foreach ($proyecto->pdf_resolucion ?? [] as $pdf)
                            <a href="{{ Storage::url($pdf) }}" target="_blank" class="text-purple-600 underline block">Resolución</a>
                        @endforeach
                    </td>
                </tr>

                <tr 
                    class="bg-gray-200"
                    x-show="openId === {{ $proyecto->id }}"
                    x-transition
                >
                    <td colspan="4" class="px-3 py-2 border border-gray-300">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 w-full">
                            <div class="col-span-2">
                                <span class="font-semibold">Plan de Trabajo:</span>
                                <div>{{ $becario->plan_trabajo ? strip_tags(html_entity_decode($becario->plan_trabajo)) : '—' }}</div>
                            </div>
                            <div>
                                <span class="font-semibold">Director Beca:</span><br>
                                {{ $proyecto->pivot->director?->apellido_nombre ?? '—' }}
                            </div>
                            <div>
                                <span class="font-semibold">Codirector Beca:</span><br>
                                {{ $proyecto->pivot->codirector?->apellido_nombre ?? '—' }}
                            </div>
                            <div></div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
