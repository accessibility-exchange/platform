<div class="steps stack">
    <h3>{{ __('Page sections') }}</h3>

    <ol class="progress stack">
        @foreach ($individual->steps() as $step => $value)
            @if ($step === 1)
                <li>
                    <a
                        href="{{ localized_route('individuals.edit', ['individual' => $individual]) }}">{{ __('individual.edit-steps.' . $value['edit']) }}</a>
                </li>
            @else
                @if ($value['edit'])
                    <li>
                        <a
                            href="{{ localized_route('individuals.edit', ['individual' => $individual, 'step' => $step]) }}">{{ __('individual.edit-steps.' . $value['edit']) }}</a>
                    </li>
                @endif
            @endif
        @endforeach
    </ol>

    @can('update', $individual)
        @if ($individual->checkStatus('draft'))
            <p class="stack">
                <button class="secondary" name="preview" value="1">{{ __('Preview page') }}</button>
                @if ($individual->isPublishable())
                    <button class="secondary" name="publish" value="1">{{ __('Publish page') }}</button>
                @endif
            </p>
        @else
            <p class="stack">
                <x-hearth-input name="unpublish" type="submit" :value="__('Unpublish page')" />
            </p>
        @endif
    @endcan
</div>
