<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div>
        @if (blank($getState()))
            <span>Sin archivos</span>
        @else
            @foreach ((array) $getState() as $file)
                <span style="--c-50:var(--primary-50);--c-400:var(--primary-400);--c-600:var(--primary-600);" class="fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-primary mb-2">
                    <!--[if BLOCK]><![endif]-->        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

                        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
                    <!--[if ENDBLOCK]><![endif]-->

                    <span class="grid">
                        <span class="truncate">
                            <a href="{{ asset('storage/' . $file) }}" target="_blank" rel="noopener noreferrer">
                                {{ basename($file) }}
                            </a>
                        </span>
                    </span>

                    <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
                </span>
            @endforeach
        @endif
    </div>
</x-dynamic-component>