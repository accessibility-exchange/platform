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
            <x-hearth-input class="secondary" type="submit" name="preview" :value="__('Preview page')" />
            <x-hearth-input class="secondary" type="submit" name="publish" :value="__('Publish page')" />
        </p>
    @else
        <p class="stack">
            <x-hearth-input type="submit" name="unpublish" :value="__('Unpublish page')" />
        </p>
    @endif
    @endcan
</div>
