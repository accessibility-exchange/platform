<div class="steps stack">
    <h3>{{ __('Page sections') }}</h3>

    <ol class="progress stack">
        <li>
            <a href="{{ localized_route('individuals.edit', ['individual' => $individual]) }}">{{ __('About you') }}</a>
        </li>
        <li>
            <a href="{{ localized_route('individuals.edit', ['individual' => $individual, 'step' => 2]) }}">{{ __('Experiences') }}</a>
        </li>
        <li>
            <a href="{{ localized_route('individuals.edit', ['individual' => $individual, 'step' => 3]) }}">{{ __('Interests') }}</a>
        </li>
        <li>
            <a href="{{ localized_route('individuals.edit', ['individual' => $individual, 'step' => 4]) }}">{{ __('Communication and meeting preferences') }}</a>
        </li>
    </ol>

    @can('update', $individual)
    @if($individual->checkStatus('draft'))
        <p class="stack">
            <button class="secondary" name="preview" value="1">{{ __('Preview page') }}</button>
            <button class="secondary" name="publish" value="1">{{ __('Publish page') }}</button>
        </p>
    @else
        <p class="stack">
            <x-hearth-input type="submit" name="unpublish" :value="__('Unpublish page')" />
        </p>
    @endif
    @endcan
</div>
