<div x-data="modal()">
    <button class="secondary" type="button"
        @click="showModal">{{ $member->id === $user->id ? __('Leave') : __('Remove') }}</button>
    <template x-teleport="body">
        <div class="modal-wrapper" x-show="showingModal">
            <div class="modal stack" @keydown.escape.window="hideModal">
                <h3>
                    @if ($member->id === $user->id)
                        {{ __('Leave this organization') }}
                    @else
                        {{ __('Remove :member from your organization', ['member' => $member->name]) }}
                    @endif
                </h3>
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
