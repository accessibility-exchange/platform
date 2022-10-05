<div x-data="modal()">
    <button class="borderless" type="button" @click="showModal">{{ __('Block') }}</button>
    <template x-teleport="body">
        <div class="modal-wrapper" x-show="showingModal" @keydown.escape.window="hideModal">
            <div class="modal stack" @click.outside="hideModal">
                <h3>{{ __('Block :blockable', ['blockable' => $blockable->name]) }}</h3>

                <p>{{ __('When you block someone, you will not be able to:') }}</p>

                <ul>
                    <li>{{ __('access their page') }}</li>
                    @if (get_class($blockable) !== 'App\Models\Individual')
                        <li>{{ __('access their projects or engagements') }}</li>
                    @endif
                </ul>
                <p> {{ __('They will not know you have blocked them.') }}

                    @if (Auth::user()->individual)
                        @if (Auth::user()->individual->isConsultant() ||
                            Auth::user()->individual->isConnector() ||
                            Auth::user()->individual->isParticipant())
                            <p>{{ __('They will not be able to:') }}</p>

                            <ul>
                                @if (Auth::user()->individual->isConsultant() || Auth::user()->individual->isConnector())
                                    <li>{{ __('access your page') }}</li>
                                    <li>{{ __('see you in search results') }}</li>
                                @endif
                                @if (Auth::user()->individual->isParticipant())
                                    <li>{{ __('match you to their projects or engagements') }}</li>
                                @endif
                            </ul>
                        @endif
                    @endif

                <p><span class="font-semibold">{{ __('They will not know you have blocked them.') }}</span>
                <p>

                <p><strong>{{ __('Are you sure you want to block :blockable?', ['blockable' => $blockable->name]) }}</strong>
                </p>

                <form class="stack" action="{{ localized_route('block-list.block') }}" method="POST">
                    <p class="repel">
                        <button class="secondary" type="button" @click="hideModal">{{ __('Cancel') }}</button>
                        <button @click="hideModal();">{{ __('Block') }}</button>
                    </p>
                    @csrf
                    <x-hearth-input name="blockable_type" type="hidden" :value="get_class($blockable)" />
                    <x-hearth-input name="blockable_id" type="hidden" :value="$blockable->id" />
                </form>

            </div>
        </div>
    </template>
</div>
