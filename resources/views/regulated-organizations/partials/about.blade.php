<h3>{{ __('About the organization') }}</h3>

{{ safe_nl2br($regulatedOrganization->getTranslation('about', $language)) }}

<h3>{{ __('Service areas') }}</h3>

<ul class="tags" role="list">
    @foreach ($regulatedOrganization->display_service_areas as $region)
        <li class="tag">{{ $region }}</li>
    @endforeach
</ul>

@if ($regulatedOrganization->accessibility_and_inclusion_links)
    <h3>{{ __('Accessibility and inclusion') }}</h3>
    <div class="grid">
        @foreach ($regulatedOrganization->accessibility_and_inclusion_links as $item)
            @if ($item['title'] && $item['url'])
                <x-card level="3">
                    <x-slot name="title">
                        <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                    </x-slot>
                    <p><strong>{{ __('Format:') }}</strong> {{ __('Website') }}</p>
                </x-card>
            @endif
        @endforeach
    </div>
@endif
