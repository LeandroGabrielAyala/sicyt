<table class="filament-tables-table w-full text-sm border-collapse border border-gray-300">
    <thead class="bg-gray-100">
        <tr>
            <th class="border border-gray-300 px-3 py-2 text-left">Nombre Completo</th>
            <th class="border border-gray-300 px-3 py-2 text-left">Función</th>
            <th class="border border-gray-300 px-3 py-2 text-center">Inicio</th>
            <th class="border border-gray-300 px-3 py-2 text-center">Fin</th>
            <th class="border border-gray-300 px-3 py-2 text-center">Vigente</th>
            <th class="border border-gray-300 px-3 py-2 text-center">PDF Disposición</th>
            <th class="border border-gray-300 px-3 py-2 text-center">PDF Resolución</th>
        </tr>
    </thead>
    <tbody>
        @foreach($proyecto->investigador as $investigador)
            <tr class="hover:bg-gray-50">
                <td class="border border-gray-300 px-3 py-2 whitespace-nowrap">
                    {{ $investigador->nombre }} {{ $investigador->apellido }}
                </td>
                <td class="border border-gray-300 px-3 py-2 whitespace-nowrap">
                    {{ $investigador->pivot->funcion->nombre ?? 'N/A' }}
                </td>
                <td class="border border-gray-300 px-3 py-2 text-center whitespace-nowrap">
                    {{ optional($investigador->pivot->inicio)->format('d/m/Y') ?? '-' }}
                </td>
                <td class="border border-gray-300 px-3 py-2 text-center whitespace-nowrap">
                    {{ optional($investigador->pivot->fin)->format('d/m/Y') ?? '-' }}
                </td>
                <td class="border border-gray-300 px-3 py-2 text-center whitespace-nowrap">
                    @if($investigador->pivot->vigente)
                        <span class="text-green-600 font-semibold">Sí</span>
                    @else
                        <span class="text-red-600 font-semibold">No</span>
                    @endif
                </td>
                <td class="border border-gray-300 px-3 py-2 text-center whitespace-nowrap">
                    @if(!empty($investigador->pivot->pdf_disposicion))
                        @foreach($investigador->pivot->pdf_disposicion as $pdf)
                            @php
                                $filename = basename($pdf);
                                $url = \Illuminate\Support\Facades\Storage::url($pdf);
                            @endphp
                            <a href="{{ $url }}" target="_blank" class="text-blue-600 underline break-all" title="{{ $filename }}">{{ $filename }}</a><br>
                        @endforeach
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="border border-gray-300 px-3 py-2 text-center whitespace-nowrap">
                    @if(!empty($investigador->pivot->pdf_resolucion))
                        @foreach($investigador->pivot->pdf_resolucion as $pdf)
                            @php
                                $filename = basename($pdf);
                                $url = \Illuminate\Support\Facades\Storage::url($pdf);
                            @endphp
                            <a href="{{ $url }}" target="_blank" class="text-blue-600 underline break-all" title="{{ $filename }}">{{ $filename }}</a><br>
                        @endforeach
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
