<p><strong>{{ __('This estimate includes the following engagements:') }}</strong></p>
<ul>
    @forelse($engagements as $engagement)
        <li>{{ $engagement->name }}</li>
    @empty
        <li>{{ __('None found.') }}</li>
    @endforelse
</ul>
