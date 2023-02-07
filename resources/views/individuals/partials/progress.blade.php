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

    @if ($individual->isConnector())
        <x-interpretation class="interpretation--start" name="{{ __('Page sections', [], 'en') }}"
            namespace="page_sections-connector" />
    @else
        <x-interpretation class="interpretation--start" name="{{ __('Page sections', [], 'en') }}" />
    @endif

    @can('update', $individual)
        @if ($individual->checkStatus('draft'))
            <p class="stack">
                @can('publish', $individual)
                    <button class="secondary" name="preview" value="1">{{ __('Preview page') }}</button>
                @endcan
                <button class="secondary" name="publish" value="1"
                    @cannot('publish', $individual) @ariaDisabled aria-describedby="cannot-publish-explanation" @endcannot>{{ __('Publish page') }}</button>
                @can('publish', $individual)
                    <x-interpretation class="interpretation--start" name="{{ __('Publish page', [], 'en') }}"
                        namespace="publish_page" />
                @else
                <p id="cannot-publish-explanation">
                    {!! Str::markdown(
                        __(
                            'You must attend an [orientation session](:url) and fill in all the required information before you can publish your page.',
                            ['url' => orientation_link(Auth::user()->context)],
                        ),
                    ) !!}
                </p>
                <x-interpretation class="interpretation--start" name="{{ __('Publish page', [], 'en') }}"
                    namespace="publish_page-disabled" />
            @endcan
            </p>
        @else
            <p class="stack">
                <x-hearth-input name="unpublish" type="submit" :value="__('Unpublish page')" />
            </p>
        @endif
    @endcan
</div>
