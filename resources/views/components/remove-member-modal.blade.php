<div x-data="modal()">
    <button class="secondary" type="button"
        @click="showModal">{{ $member->id === $user->id ? __('Leave') : __('Remove') }}</button>
    <template x-teleport="body">
        <div class="modal-wrapper" x-show="showingModal" @keydown.escape.window="hideModal">
            <div class="modal stack" @click.outside="hideModal">
                @if ($member->id === $user->id)
                    <h3>
                        {{ __('Leave this organization') }}
                    </h3>
                    <x-interpretation name="{{ __('Leave this organization', [], 'en') }}"
                        namespace="remove-member-modal" />
                @else
                    <h3>
                        {{ __('Remove :member from your organization', ['member' => $member->name]) }}
                        <x-interpretation name="{{ __('Remove member from your organization', [], 'en') }}"
                            namespace="remove-member-modal" />
                    </h3>
                @endif

                @if ($member->id === $user->id)
                    <p>
                        {{ __('Are you sure you want to leave :organization?', ['organization' => $membershipable->name]) }}
                    </p>
                @else
                    <p>
                        {{ __('Are you sure you want to remove :member from :organization? You cannot undo this.', ['member' => $member->name, 'organization' => $membershipable->name]) }}
                    </p>
                @endif

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
