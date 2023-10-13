<tr>
    <td>
        @if ($individual->isPublishable())
            <a href="{{ localized_route('individuals.show', $individual) }}"><strong>{{ $individual->name }}</strong></a>
        @else
            <strong>{{ $individual->name }}</strong>
        @endif
        <br />
        <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
    </td>
    <td>
        {{ __('Individual') }}
    </td>
    <td>
        @if ($individual->checkStatus('draft') && !$individual->isPublishable())
            {{ __('Draft') }}
        @elseif($individual->checkStatus('draft') && $individual->isPublishable())
            {{ __('Ready to publish') }}
        @elseif($individual->checkStatus('published'))
            {{ __('Published') }}
        @endif
    </td>
    <td class="">
        @if ($user->checkStatus('suspended'))
            <span class="text-error flex items-center gap-2">
                @svg('heroicon-o-no-symbol') <span class="font-semibold">{{ __('Suspended') }}</span>
            </span>
        @else
            @if ($user->checkStatus('pending'))
                {{ __('Pending approval') }}
            @elseif($user->checkStatus('approved'))
                {{ __('Approved') }}
            @endif
        @endif
    </td>
    <td>
        @if ($user->checkStatus('pending'))
            <form wire:submit="approve">
                <button class="secondary">{{ __('Approve') }}</button>
            </form>
        @else
            @if (!$user->checkStatus('suspended'))
                <form wire:submit="suspend">
                    <button class="secondary destructive">
                        @svg('heroicon-o-no-symbol')
                        {{ __('Suspend') }}
                    </button>
                </form>
            @else
                <form wire:submit="unsuspend">
                    <button class="secondary">{{ __('Unsuspend') }}</button>
                </form>
            @endif
        @endif
    </td>
</tr>
