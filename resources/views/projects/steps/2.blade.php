<h2>{{ $step }}. {{ $steps[$step] }}</h2>

<progress id="step" max="100" value="{{ count($project->progress[2]) / 4  * 100 }}"></progress>

<ol role="list" class="substeps flow">
    <li class="substep">
        <p class="substep__description">
            {{ __('Learn how to engage the Deaf and disability communities.') }}<br />
            <a href="{{ localized_route('resources.index') }}">{{ __('Browse resources in the Resource Hub') }}</a>
        </p>
        <div class="substep__progress">
            @if(!$project->isComplete($step, 1))
            <form method="post" action="{{ localized_route('projects.update-progress', $project) }}">
                @csrf
                @method('PUT')

                <x-hearth-input type="hidden" name="step" :value="$step" />
                <x-hearth-input type="hidden" name="substep" value="1" />
                <x-hearth-button>{{ __('I’ve done this') }}</x-hearth-button>
            </form>
            @else
            <p class="substep__progress__status"><x-heroicon-s-check-circle class="icon" /> Done</p>
            <form method="post" action="{{ localized_route('projects.update-progress', $project) }}">
                @csrf
                @method('PUT')

                <x-hearth-input type="hidden" name="step" :value="$step" />
                <x-hearth-input type="hidden" name="substep" value="1" />
                <x-hearth-input type="hidden" name="undo" value="1" />
                <x-hearth-button>{{ __('Undo this step') }}</x-hearth-button>
            </form>
            @endif
        </div>
    </li>
    <li class="substep">
        <p class="substep__description">
            {{ __('Think about how to structure your consultation.') }}<br />
            <a href="{{ localized_route('resources.index') }}">{{ __('Browse case studies in the Resource Hub') }}</a>
        </p>
        <div class="substep__progress">
            @if(!$project->isComplete($step, 2))
            <form method="post" action="{{ localized_route('projects.update-progress', $project) }}">
                @csrf
                @method('PUT')

                <x-hearth-input type="hidden" name="step" :value="$step" />
                <x-hearth-input type="hidden" name="substep" value="2" />
                <x-hearth-button>{{ __('I’ve done this') }}</x-hearth-button>
            </form>
            @else
            <p class="substep__progress__status"><x-heroicon-s-check-circle class="icon" /> Done</p>
            <form method="post" action="{{ localized_route('projects.update-progress', $project) }}">
                @csrf
                @method('PUT')

                <x-hearth-input type="hidden" name="step" :value="$step" />
                <x-hearth-input type="hidden" name="substep" value="2" />
                <x-hearth-input type="hidden" name="undo" value="1" />
                <x-hearth-button>{{ __('Undo this step') }}</x-hearth-button>
            </form>
            @endif
        </div>
    </li>
    <li class="substep">
        <p class="substep__description">
            {{ __('Learn about how the disability community is currently engaging with your organization or sector.') }}<br />
            <a href="{{ localized_route('resources.index') }}">{{ __('Browse stories in the Resource Hub') }}</a>
        </p>
        <div class="substep__progress">
            @if(!$project->isComplete($step, 3))
            <form method="post" action="{{ localized_route('projects.update-progress', $project) }}">
                @csrf
                @method('PUT')
                <x-hearth-input type="hidden" name="step" :value="$step" />
                <x-hearth-input type="hidden" name="substep" value="3" />
                <x-hearth-button>{{ __('I’ve done this') }}</x-hearth-button>
            </form>
            @else
            <p class="substep__progress__status"><x-heroicon-s-check-circle class="icon" /> Done</p>
            <form method="post" action="{{ localized_route('projects.update-progress', $project) }}">
                @csrf
                @method('PUT')
                <x-hearth-input type="hidden" name="step" :value="$step" />
                <x-hearth-input type="hidden" name="substep" value="3" />
                <x-hearth-input type="hidden" name="undo" value="1" />
                <x-hearth-button>{{ __('Undo this step') }}</x-hearth-button>
            </form>
            @endif
        </div>
    </li>
    <li class="substep">
        <p class="substep__description">
            {{ __('Learn about different ways to meet access needs.') }}<br />
            <a href="{{ localized_route('resources.index') }}">{{ __('Browse resources in the Resource Hub') }}</a>
        </p>
        <div class="substep__progress">
            @if(!$project->isComplete($step, 4))
            <form method="post" action="{{ localized_route('projects.update-progress', $project) }}">
                @csrf
                @method('PUT')
                <x-hearth-input type="hidden" name="step" :value="$step" />
                <x-hearth-input type="hidden" name="substep" value="4" />
                <x-hearth-button>{{ __('I’ve done this') }}</x-hearth-button>
            </form>
            @else
            <p class="substep__progress__status"><x-heroicon-s-check-circle class="icon" /> Done</p>
            <form method="post" action="{{ localized_route('projects.update-progress', $project) }}">
                @csrf
                @method('PUT')
                <x-hearth-input type="hidden" name="step" :value="$step" />
                <x-hearth-input type="hidden" name="substep" value="4" />
                <x-hearth-input type="hidden" name="undo" value="1" />
                <x-hearth-button>{{ __('Undo this step') }}</x-hearth-button>
            </form>
            @endif
        </div>
    </li>
</ol>
