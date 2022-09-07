<div class="flow">
    <div aria-live="assertive">
        @if ($message)
            <p class="visually-hidden">
                {{ $message }}
            </p>
        @endif
    </div>
    <div role="region" aria-describedby="selectedResourcesDesc" tabindex="0">
        <table>
            <caption id="selectedResourcesDesc">{{ __('resource-select.selected_resources') }}</caption>
            <thead>
                <tr>
                    <th>{{ __('resource-select.resource_title') }}</th>
                    <th>{{ __('resource-select.resource_preview') }}</th>
                    <th>{{ __('resource-select.action_button') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($selectedResources as $i => $resource)
                    <tr>
                        <td>{{ $resource->title }}</td>
                        <td>
                            <a href="{{ localized_route('resources.show', str_replace(' ', '-', strtolower($resource->title))) }}"
                                target="_blank">
                                {{ __('resource-select.resource_link') }}
                                <span class="visually-hidden"> {{ $resource->title }} </span>
                            </a>
                        </td>
                        <td>
                            <button class="secondary" type="button" wire:click="removeResource({{ $i }})"
                                wire:key="remove-resource-{{ $i }}">
                                {{ __('resource-select.remove_resource') }}
                                <span class="visually-hidden"> {{ $resource->title }} </span>
                            </button>
                        </td>
                        <input name="resource_ids[]" type="hidden" value="{{ $resource->id }}" />
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div role="region" aria-describedby="availableResourcesDesc" tabindex="0">
        <table>
            <caption id="availableResourcesDesc">{{ __('resource-select.available_resources') }}</caption>
            <thead>
                <tr>
                    <th>{{ __('resource-select.resource_title') }}</th>
                    <th>{{ __('resource-select.resource_preview') }}</th>
                    <th>{{ __('resource-select.action_button') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($availableResources as $i => $resource)
                    <tr>
                        <td>{{ $resource->title }}</td>
                        <td>
                            <a href="{{ localized_route('resources.show', str_replace(' ', '-', strtolower($resource->title))) }}"
                                target="_blank">
                                {{ __('resource-select.resource_link') }}
                                <span class="visually-hidden"> {{ $resource->title }} </span>
                            </a>
                        </td>
                        <td>
                            <button class="secondary" type="button" wire:click="addResource({{ $i }})"
                                wire:key="add-resource-{{ $resource->title }}">
                                {{ __('resource-select.add_resource') }}
                                <span class="visually-hidden"> {{ $resource->title }} </span>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
