<div class="w-full text-sm text-left bg-[#0f172a] rounded-xl shadow-md overflow-hidden" style="border: 1px solid #ffffff1a; color: #8c9aaf;">
    <table class="w-full table-fixed border-collapse" style="border-color: #ffffff1a;">
        <thead style="color: #6670c5; background-color: #19213a;">
            <tr>
                <th class="px-4 py-3 border-b border-r" style="border-color: #ffffff1a;">N° PI</th>
                <th class="px-4 py-3 border-b border-r" style="border-color: #ffffff1a;">Convocatoria</th>
                <th class="px-4 py-3 border-b border-r" style="border-color: #ffffff1a;">Director</th>
                <th class="px-4 py-3 border-b border-r" style="border-color: #ffffff1a;">Codirector</th>
                <th class="px-4 py-3 border-b" style="border-color: #ffffff1a;">Vigencia</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($adscripto->proyectos as $proyecto)
                <tr class="bg-[#19213a]" style="border-bottom: 1px solid #ffffff1a;">
                    <td class="px-4 py-3 border-r" style="border-color: #ffffff1a;">
                        <b style="color: #6670c5;">{{ $proyecto->nro }}</b>
                    </td>
                    <td class="px-4 py-3 border-r" style="border-color: #ffffff1a;">
                        {{ $proyecto->pivot->convocatoria?->anio ?? '—' }}
                    </td>
                    <td class="px-4 py-3 border-r" style="border-color: #ffffff1a;">
                        {{ $proyecto->pivot->director?->nombre_completo ?? '—' }}
                    </td>
                    <td class="px-4 py-3 border-r" style="border-color: #ffffff1a;">
                        {{ $proyecto->pivot->codirector?->nombre_completo ?? '—' }}
                    </td>
                    <td class="px-4 py-3" style="border-color: #ffffff1a;">
                        {{ $proyecto->pivot->vigente ? 'Vigente' : 'No Vigente' }}
                    </td>
                </tr>

                <tr class="bg-[#0f172a]" style="border-bottom: 1px solid #ffffff1a;">
                    <td colspan="6" class="px-4 py-4 border-t" style="border-color: #ffffff1a;">
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
                                <span style="color: #6670c5; font-weight: 600;">Vigencia del PI:</span><br>
                                {{ $proyecto->estado === 1 ? 'Vigente' : 'No vigente' }}
                            </div>
                        </div>

                        <div class="mt-6">
                            <span style="color: #6670c5; font-weight: 600;">Denominación del Proyecto:</span><br>
                            {{ $proyecto->nombre ? strip_tags(html_entity_decode($proyecto->nombre)) : '—' }}
                        </div>
                    </td>
                </tr>

                <tr class="bg-[#0f172a]" style="border-bottom: 1px solid #ffffff1a;">
                    <td colspan="6" class="px-4 py-4 border-t" style="border-color: #ffffff1a;">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="mt-4">
                                <span style="color: #6670c5; font-weight: 600;">Disposición del Adscripto:</span><br>
                                @forelse ($proyecto->pivot->convocatoria?->pdf_disposicion ?? [] as $pdf)
                                    <a href="{{ asset('storage/' . $pdf) }}" target="_blank" class="underline block text-blue-400 hover:text-blue-600 transition-colors duration-150">Ver archivo</a>
                                @empty
                                    <span class="text-gray-400">—</span>
                                @endforelse
                            </div>
                            <div class="mt-4">                                
                                <span style="color: #6670c5; font-weight: 600;">Resolución del Adscripto:</span><br>
                                @forelse ($proyecto->pivot->convocatoria?->pdf_resolucion ?? [] as $pdf)
                                    <a href="{{ asset('storage/' . $pdf) }}" target="_blank" class="underline block text-green-400 hover:text-green-600 transition-colors duration-150">Ver archivo</a>
                                @empty
                                    <span class="text-gray-400">—</span>
                                @endforelse
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-gray-400">No hay proyectos asociados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
