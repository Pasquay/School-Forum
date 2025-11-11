<div
    class="group-info-manager"
    id="group-info-manager-{{ $group->id }}"
    data-groupid="{{ $group->id }}">
    <input type="checkbox" class="group-select" id="group-select-{{ $group->id }}" value="{{ $group->id }}">
    <div class="group-info">
        @if($group->photo)
        <img src="{{ asset('storage/' . $group->photo) }}" alt="Group Photo">
        @else
        <div class="group-default-photo">
            {{ strtoupper(substr($group->name, 0, 1)) }}
        </div>
        @endif
        <p>{{ $group->name }}</p>
        <span class="group-role {{ $group->user_role }}">{{ $group->user_role }}</span>
    </div>
    <div class="right-actions">
        <form action="/group/toggleStar/{{ $group->id }}" method="post" class="star-toggle-form">
            @csrf
            <button class="star"
                @if(isset($group->notLoggedUser) && $group->notLoggedUser == 1)
                disabled
                @endif
                >
                <img
                    src="
                        @if($group->is_starred === 1)
                            {{ asset('/icons/star.png') }}
                        @else
                            {{ asset('/icons/star-alt.png') }}
                        @endif
                    "
                    alt="star"
                    class="star">
            </button>
        </form>
        <form action="/group/toggleMute/{{ $group->id }}" method="post" class="mute-toggle-form">
            @csrf
            <button class="mute"
                @if(isset($group->notLoggedUser) && $group->notLoggedUser == 1)
                disabled
                @endif
                >
                <img src="
                    @if($group->is_muted === 1)
                        {{ asset('/icons/mute.png') }}
                    @else
                        {{ asset('/icons/mute-alt.png') }}
                    @endif
                "
                    alt="mute" class="mute">
            </button>
        </form>
        @if($group->user_role === 'owner' || $group->user_role === 'moderator')
        <button class="manage-button">Manage</button>
        @else
        <form action="{{ route('group.leave.alt', ['id' => $group->id ]) }}" method="post" class="leave-group-form">
            @csrf
            <button type="button" class="leave-button">Leave</button>
        </form>
        @endif
    </div>
</div>
<style>
    /* MAIN */
    .group-info-manager {
        display: flex;
        align-items: center;
        background-color: white;
        gap: 0.75rem;
        padding: 0.5rem 1.2rem;
        border-bottom: 1px solid #f0f0f0;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .group-info-manager:last-child {
        border-bottom: none;
    }

    .group-info-manager:hover {
        background-color: #f8f9fa;
        border-radius: 6px;
    }

    /* GROUP INFO */
    .group-info-manager .group-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        min-width: 0;
    }

    .group-info-manager .group-info p {
        margin: 0;
        font-size: 1rem;
        font-weight: 500;
        line-height: 1.2;
    }

    /* GROUP PHOTO */
    .group-info-manager .group-default-photo {
        width: 32px;
        height: 32px;
        background-color: #2d4a2b;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        border-radius: 8px;
    }

    .group-info-manager img {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        flex-shrink: 0;
        object-fit: cover;
    }

    /* USER ROLE */
    .group-info-manager .group-role {
        display: inline-block;
        padding: 0.2em 0.85em;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        margin-left: 0.75rem;
        text-transform: capitalize;
        letter-spacing: 0.01em;
        border: none;
        min-width: 80px;
        text-align: center;
    }

    .group-info-manager .group-role.owner {
        background: #e6f3ff;
        color: #2176c7;
    }

    .group-info-manager .group-role.moderator {
        background: #f6eaff;
        color: #8e44ad;
    }

    .group-info-manager .group-role.member {
        background: #eafaf1;
        color: #218c5b;
    }

    /* RIGHT ACTIONS */
    .group-info-manager .right-actions {
        display: flex;
        align-items: center;
        gap: 0.2rem;
        margin-left: auto;
    }

    .group-info-manager .right-actions form,
    .group-info-manager .right-actions button {
        display: inline-flex;
        align-items: center;
        border: none;
        box-shadow: none;
    }

    /* STAR & MUTE BUTTON */
    .group-info-manager .right-actions button.star,
    .group-info-manager .right-actions button.mute {
        background: none;
        border: none;
        padding: 0.25rem;
        margin: 0 0.1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .group-info-manager .right-actions img.star,
    .group-info-manager .right-actions img.mute {
        width: 20px;
        height: 20px;
        object-fit: contain;
        display: block;
        background: none;
        border: none;
        box-shadow: none;
        padding: 0;
        margin: 0;
    }

    /* MANAGE  & LEAVEBUTTON */
    .group-info-manager .manage-button,
    .group-info-manager .leave-button {
        color: white;
        padding: 0.3rem 1rem;
        margin-left: 0.3rem;
        border-radius: 6px;
        width: 68px;
        text-align: center;
        border: none;
        cursor: pointer;
        font-size: 0.8rem;
        font-weight: 500;
        transition: background-color 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .group-info-manager .manage-button {
        background-color: #4a90e2;
    }

    .group-info-manager .manage-button:hover {
        background-color: #357abd;
    }

    .group-info-manager .leave-button {
        background-color: #dc3545;
    }

    .group-info-manager .leave-button:hover {
        background-color: #c82333;
    }

    /* BULK ACTIONS BAR */
</style>