

@markdown
{{ $individual->bio }}
@endmarkdown

<h3>{{ __('Languages :name uses', ['name' => $individual->first_name]) }}</h3>

<ul>
    @foreach($individual->all_languages as $language)
        <li>{{ get_language_exonym($language) }}</li>
    @endforeach
</ul>

@if($individual->isConsultant())
<h3>{{ __('As an Accessibility Consultant, :name can help with:', ['name' => $individual->firstName]) }}</h3>
<ul>
    @foreach($individual->consulting_services as $service)
    <li>{{ __('consulting-services.'.$service) }}</li>
    @endforeach
</ul>
@endif

@if($individual->isConnector())
<h3>{{ __('As a Community Connector, :name can connect to:', ['name' => $individual->firstName]) }}</h3>
@endif
