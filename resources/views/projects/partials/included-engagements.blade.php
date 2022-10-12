<p><strong>{{ __('This estimate includes the following engagements:') }}</strong></p>
<ul>
    @foreach ($engagements as $engagement)
        <li>{{ $engagement->name }}</li>
    @endforeach
</ul>
