<div x-data="modal()">
    <button class="secondary" type="button" @click="showModal">{{ __('Remove') }}</button>
    <template x-teleport="body">
        <div class="modal-wrapper" x-show="showingModal">
            <div class="modal stack" @keydown.escape.window="hideModal">
                <h3>{{ __('Remove :member from your organization', ['member' => $member->name]) }}</h3>

                <p>
                    {{ __('Are you sure you want to remove :member from :organization? You cannot undo this.', ['member' => $member->name, 'organization' => $membershipable->name]) }}
                </p>

                <form action="{{ route('memberships.destroy', $member->membership->id) }}" method="POST">
                    @csrf
                    @method('delete')
                    <div class="repel">
                        <button class="secondary" type="button" aria-label="{{ __('Cancel') }}" @click="hideModal">
                            {{ __('Cancel') }}
                        </button>

                        <button class="secondary"
                            aria-label="{{ $member->id === $user->id ? __('Leave :membershipable', ['membershipable' => $membershipable->name]) : __('Remove :user from :membershipable', ['user' => $member->name, 'membershipable' => $membershipable->name]) }}">
                            {{ $member->id === $user->id ? __('Leave organization') : __('Remove') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </template>
</div>
