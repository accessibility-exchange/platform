<div class="steps stack">
    <h3>{{ __('Page sections') }}</h3>

    <ol class="progress stack">
        @foreach ($individual->steps() as $step => $value)
            @if ($step === 1)
                <li>
                    <a
                        href="{{ localized_route('individuals.edit', ['individual' => $individual]) }}">{{ $value['label'] }}</a>
                </li>
            @else
                @if ($value['edit'])
                    <li>
                        <a
                            href="{{ localized_route('individuals.edit', ['individual' => $individual, 'step' => $step]) }}">{{ $value['label'] }}</a>
                    </li>
                @endif
            @endif
        @endforeach
    </ol>

    @can('update', $individual)
        @if ($individual->checkStatus('draft'))
            <p class="stack">
                @can('publish', $individual)
                    <button class="secondary" name="preview" value="1">{{ __('Preview page') }}</button>
                @endcan
                <button class="secondary" name="publish" value="1"
                    @cannot('publish', $individual) @ariaDisabled aria-describedby="cannot-publish-explanation" @endcannot>{{ __('Publish page') }}</button>
                @cannot('publish', $individual)
                <p id="cannot-publish-explanation">
                    {{ __('You must attend an orientation session and fill in all the required information before you can publish your page.') }}
                </p>
            @endcannot
            </p>
        @else
            <p class="stack">
                <x-hearth-input name="unpublish" type="submit" :value="__('Unpublish page')" />
            </p>
        @endif
    @endcan
</div>
