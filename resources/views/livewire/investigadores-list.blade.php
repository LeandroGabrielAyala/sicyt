@php
    // IDs de funciones a excluir: Director y Codirector
    $funcionesExcluir = \App\Models\Funcion::whereIn('nombre', ['Director', 'Co-director'])->pluck('id')->toArray();
@endphp

<div class="w-full text-sm text-left bg-[#0f172a] rounded-xl shadow-md overflow-hidden" style="border: 1px solid #ffffff1a; color: #8c9aaf;">
    <table class="w-full table-fixed border-collapse" style="border-color: #ffffff1a;">
        <thead style="color: #6670c5; background-color: #19213a;">
            <tr>
                <th class="px-3 py-2 border-b" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Integrantes</th>
                <th class="px-3 py-2 border-b" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Función</th>
                <th class="px-3 py-2 border-b" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Estado</th>
                <th class="px-3 py-2 border-b" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Disposición</th>
                <th class="px-3 py-2 border-b" style="border-color: #ffffff1a; font-weight: 600; user-select: none;">Resolución</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($proyecto->investigador->whereNotIn('pivot.funcion_id', $funcionesExcluir) as $investigador)
                <tr class="hover:bg-[#19213a] transition-colors duration-200" style="border-bottom: 1px solid #ffffff1a;">
                    <td class="px-3 py-2" style="border-color: #ffffff1a;">
                        {{ $investigador->apellido }}, {{ $investigador->nombre }}
                    </td>
                    <td class="px-3 py-2" style="border-color: #ffffff1a;">
                        {{ optional($investigador->pivot->funcion)->nombre ?? '—' }}
                    </td>
                    <td class="px-3 py-2" style="border-color: #ffffff1a;">
                        @if ($investigador->pivot->vigente)
                            <span class="text-green-500 font-medium">Vigente</span>
                        @else
                            <span class="text-red-500 font-medium">No Vigente</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 space-y-1" style="border-color: #ffffff1a;">
                        @foreach ($investigador->pivot->pdf_disposicion ?? [] as $pdf)
                            <a href="{{ Storage::url($pdf) }}" target="_blank" class="underline text-indigo-400 hover:text-indigo-600 block transition-colors duration-150">Ver Archivo</a>
                        @endforeach
                    </td>
                    <td class="px-3 py-2 space-y-1" style="border-color: #ffffff1a;">
                        @foreach ($investigador->pivot->pdf_resolucion ?? [] as $pdf)
                            <a href="{{ Storage::url($pdf) }}" target="_blank" class="underline text-purple-400 hover:text-purple-600 block transition-colors duration-150">Ver Archivo</a>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
