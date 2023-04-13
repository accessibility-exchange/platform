@extends('engagements.manage-participants')

@section('title')
    {{ __('Access needs') }}
@endsection

@section('content')
    <h2>{{ __('Access needs') }}</h2>

    <p>{{ __('This is a summary of the access needs for your confirmed participants.') }}</p>

    @if (!$engagement->meetingTypesIncludes('in_person'))
        <div class="my-16">
            <h3>{{ __('Baseline access needs') }}</h3>
            <p>{{ __('Gender neutral, barrier-free washrooms') }}</p>
        </div>
    @endif
    <div class="my-16">

        <h3 id="needs">{{ __('Participant access needs') }}</h3>
        <div role="region" aria-labelledby="needs" tabindex="0">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('Access needs') }}</th>
                        <th>{{ __('Participant') }}</th>
                    </tr>
                </thead>
                @foreach ($anonymizableAccessNeeds as $accessNeed)
                    <tr>
                        <td>{{ $accessNeed->name }}</td>
                        <td>{{ __('Anonymous participant') }}</td>
                    </tr>
                @endforeach
                @foreach ($accessNeeds as $accessNeed)
                    @unless($accessNeed->is($additionalConcerns))
                        <tr>
                            <td>{{ $accessNeed->name }}</td>
                            <td>
                                <ul role="list">
                                    @foreach ($participants as $participant)
                                        @if ($participant->accessSupports->contains($accessNeed))
                                            <li>
                                                @if ($participant->pivot->share_access_needs)
                                                    <a
                                                        href="{{ localized_route('engagements.manage-participants', $engagement) }}#participant-{{ $participant->id }}">{{ $participant->name }}</a>
                                                @else
                                                    {{ __('Anonymous participant') }}
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endunless
                @endforeach
                @foreach ($otherAccessNeeds as $otherAccessNeed)
                    <tr>
                        <td>{{ $otherAccessNeed }}</td>
                        <td>
                            <ul role="list">
                                @foreach ($participants as $participant)
                                    @if ($participant->other_access_need === $otherAccessNeed)
                                        <li>
                                            @if ($participant->pivot->share_access_needs)
                                                <a
                                                    href="{{ localized_route('engagements.manage-participants', $engagement) }}#participant-{{ $participant->id }}">{{ $participant->name }}</a>
                                            @else
                                                {{ __('Anonymous participant') }}
                                            @endif
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    @if ($accessNeeds->contains($additionalConcerns))
        <div class="my-20">
            <h3>{{ __('Participants who have additional concerns or needs to be discussed') }}</h3>
            <ul class="link-list" role="list">
                @foreach ($participants as $participant)
                    @if ($participant->accessSupports->contains($additionalConcerns) && $participant->pivot->share_access_needs)
                        <li>
                            <a
                                href="{{ localized_route('engagements.manage-participants', $engagement) }}#participant-{{ $participant->id }}">{{ $participant->name }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endif

    <x-hearth-alert :title="__('Have trouble meeting the access needs of your participants?')" x-show="true" :dismissable="false">
        <p>{{ __('Please reach out to us and we can try to help.') }}</p>
        {!! contact_information() !!}
    </x-hearth-alert>

    <p class="my-12">
        <a class="cta secondary" href="{{ localized_route('engagements.manage-participants', $engagement) }}">
            @svg('heroicon-o-arrow-left') {{ __('Back') }}
        </a>
    </p>
@endsection
