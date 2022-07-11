<div class="steps stack">
    <h3>{{ __('Page sections') }}</h3>

    <ol class="progress stack">
        @foreach($individual->editSteps() as $step => $key)
        @if($step === 1)
            <li>
                <a href="{{ localized_route('individuals.edit', ['individual' => $individual]) }}">{{ __('individual.edit-steps.'.$key) }}</a>
            </li>
        @else
        <li>
            <a href="{{ localized_route('individuals.edit', ['individual' => $individual, 'step' => $step]) }}">{{ __('individual.edit-steps.'.$key) }}</a>
        </li>
        @endif
        @endforeach
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
