<form id="create-project" action="{{ localized_route('projects.store', $entity) }}" method="POST" novalidate>
    @csrf

    <x-translatable-textarea name="goals" :label="__('What are your goals for this project?')" />

    <x-translatable-textarea name="scope" :label="__('What communities does this project hope to engage, and how will they be impacted?')" />

    <x-translatable-textarea name="out_of_scope" :label="__('What is out of scope for your project?')" />

    <x-translatable-textarea name="timeline" :label="__('Project timeline')" />

    <x-hearth-button>{{ __('Create project') }}</x-hearth-button>
</form>
