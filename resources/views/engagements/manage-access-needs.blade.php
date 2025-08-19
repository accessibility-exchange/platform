@extends('engagements.manage-participants')

@section('title')
    {{ __('Access needs') }}
@endsection

@section('content')
    <h2>{{ __('Access needs') }}</h2>
    <x-interpretation name="{{ __('Access needs', [], 'en') }}" />

    <p>{{ __('This is a summary of the access needs for your confirmed participants.') }}</p>

    @if ($engagement->meetingTypesIncludes(App\Enums\MeetingType::InPerson->value))
        <div class="my-16">
            <h3 class="h4">{{ __('Baseline access needs') }}</h3>
            <x-interpretation name="{{ __('Baseline access needs', [], 'en') }}" />
            <p>{{ __('Gender neutral, barrier-free washrooms') }}</p>
        </div>
    @endif

    @if (count($generalAccessNeeds) >= 1)
        <x-expander class="expander--large" :summary="__('General access needs')" level="3" expanded>
            <x-interpretation name="{{ __('General access needs', [], 'en') }}" />
            <div role="region" aria-labelledby="general_access_needs" tabindex="0">
                <table class="table--fixed_layout">
                    <thead>
                        <tr>
                            <th>{{ __('Access needs') }}</th>
                            <th>{{ __('Participant') }}</th>
                        </tr>
                    </thead>
                    @foreach ($generalAccessNeeds as $generalAccessNeed)
                        <tr>
                            <td>{{ $generalAccessNeed->name }}</td>
                            @if ($generalAccessNeed->anonymizable)
                                <td>{{ __('Anonymous participant') }}</td>
                            @else
                                <td>
                                    <ul role="list">
                                        @foreach ($participants as $participant)
                                            @if ($participant->accessSupports->contains($generalAccessNeed))
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
                            @endif
                        </tr>
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
        </x-expander>
    @endif

    @if (count($meetingAccessNeeds) >= 1)
        <x-expander class="expander--large" :summary="__('For meeting in real time')" level="3" expanded>
            <x-interpretation name="{{ __('For meeting in real time', [], 'en') }}" />
            <div role="region" aria-labelledby="for-meeting-in-real-time" tabindex="0">
                <table class="table--fixed_layout">
                    <thead>
                        <tr>
                            <th>{{ __('Access needs') }}</th>
                            <th>{{ __('Participant') }}</th>
                        </tr>
                    </thead>
                    @foreach ($meetingAccessNeeds as $meetingAccessNeed)
                        <tr>
                            <td>{{ $meetingAccessNeed->name }}</td>
                            @if ($meetingAccessNeed->anonymizable)
                                <td>{{ __('Anonymous participant') }}</td>
                            @else
                                <td>
                                    <ul role="list">
                                        @foreach ($participants as $participant)
                                            @if ($participant->accessSupports->contains($meetingAccessNeed))
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
                            @endif
                        </tr>
                    @endforeach
                </table>
            </div>
        </x-expander>
    @endif

    @if (count($inPersonAccessNeeds) >= 1)
        <x-expander class="expander--large" :summary="__('For in-person meetings')" level="3" expanded>
            <x-interpretation name="{{ __('For in-person meetings', [], 'en') }}" />
            <div role="region" aria-labelledby="for-in-person-meetings" tabindex="0">
                <table class="table--fixed_layout">
                    <thead>
                        <tr>
                            <th>{{ __('Access needs') }}</th>
                            <th>{{ __('Participant') }}</th>
                        </tr>
                    </thead>
                    @foreach ($inPersonAccessNeeds as $inPersonAccessNeed)
                        <tr>
                            <td>{{ $inPersonAccessNeed->name }}</td>
                            @if ($inPersonAccessNeed->anonymizable)
                                <td>{{ __('Anonymous participant') }}</td>
                            @else
                                <td>
                                    <ul role="list">
                                        @foreach ($participants as $participant)
                                            @if ($participant->accessSupports->contains($inPersonAccessNeed))
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
                            @endif
                        </tr>
                    @endforeach
                </table>
            </div>
        </x-expander>
    @endif

    @if (count($documentAccessNeeds) >= 1)
        <x-expander class="expander--large" :summary="__('For engagement documents')" level="3" expanded>
            <x-interpretation name="{{ __('For engagement documents', [], 'en') }}" />
            <div role="region" aria-labelledby="for-engagement-documents" tabindex="0">
                <table class="table--fixed_layout">
                    <thead>
                        <tr>
                            <th>{{ __('Access needs') }}</th>
                            <th>{{ __('Participant') }}</th>
                        </tr>
                    </thead>
                    @foreach ($documentAccessNeeds as $documentAccessNeed)
                        <tr>
                            <td>{{ $documentAccessNeed->name }}</td>
                            @if ($documentAccessNeed->anonymizable)
                                <td>{{ __('Anonymous participant') }}</td>
                            @else
                                <td>
                                    <ul role="list">
                                        @foreach ($participants as $participant)
                                            {{-- @if ($participant->id == 7)
                                            @dd($participant->accessSupports->contains($documentAccessNeed), $participant->pivot->share_access_needs)
                                            @endif --}}
                                            @if ($participant->accessSupports->contains($documentAccessNeed))
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
                            @endif
                        </tr>
                    @endforeach
                </table>
            </div>
        </x-expander>
    @endif

    @if ($accessNeeds->contains($additionalConcerns))
        <div class="my-20">
            <h3 class="h4">{{ __('Participants who have additional concerns or needs to be discussed') }}</h3>
            <x-interpretation
                name="{{ __('Participants who have additional concerns or needs to be discussed', [], 'en') }}" />
            <ul class="link-list" role="list">
                @foreach ($participants as $participant)
                    @if ($participant->accessSupports->contains($additionalConcerns))
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
        </div>
    @endif

    <x-hearth-alert :title="__('Have trouble meeting the access needs of your participants?')" x-show="true" :dismissable="false">
        <x-interpretation name="{{ __('Have trouble meeting the access needs of your participants?', [], 'en') }}" />
        <p>{{ __('Please reach out to us and we can try to help.') }}</p>
        @include('partials.contact-information')
    </x-hearth-alert>

    <p class="my-12">
        <a class="cta secondary" href="{{ localized_route('engagements.manage-participants', $engagement) }}">
            @svg('heroicon-o-arrow-left') {{ __('Back') }}
        </a>
    </p>
@endsection
