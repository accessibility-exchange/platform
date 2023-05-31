@props(['level' => 2, 'name', 'model', 'href' => '', 'linkText' => $name])
<x-heading class="repel" :level="$level">
    {{ $name }}
    @can('update', $model)
        <a class="cta secondary" href="{{ $href }}">
            @svg('heroicon-o-pencil', 'mr-1')
            {{ safe_inlineMarkdown('Edit :!section', ['section' => '<span class="visually-hidden">' . htmlentities($linkText) . '</span>']) }}
        </a>
    @endcan
</x-heading>
