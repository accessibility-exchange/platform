<div class="steps flow">
    <h2>{{ __('Steps to publish') }}</h2>

    <ol class="progress flow">
        <li>
            <a href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember]) }}">{{ __('About you') }}</a>
        </li>
        <li>
            <a href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 2]) }}">{{ __('Interests') }}</a>
        </li>
        <li>
            <a href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 3]) }}">{{ __('Experiences') }}</a>
        </li>
        <li>
            <a href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 4]) }}">{{ __('Communication preferences') }}</a>
        </li>
        <li>
            <a href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 5]) }}">{{ __('Access and accomodations') }}</a>
        </li>
    </ol>


    @can('update', $communityMember)
    @if($communityMember->checkStatus('draft'))
    <form action="{{ localized_route('community-members.update-publication-status', $communityMember) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <x-hearth-input type="submit" name="publish" :value="__('Publish page')" />
    </form>
    <p class="field__hint">{{ __('Once you publish your page, Federally Regulated Organizations can find you and ask you to consult with them.') }}</p>
    @endif
    @endcan
</div>
