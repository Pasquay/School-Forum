<div
    class="group-info-minimal"
    id="group-info-minimal-{{ $group->id }}"
    data-type='{{ $group->pivot->role }}'
    data-groupid='{{ $group->id }}'>
    @if($group->photo)
    <img src="{{ asset('storage/' . $group->photo) }}" alt="Group photo">
    @else
    <div class="group-default-photo">
        {{ strtoupper(substr($group->name, 0, 1)) }}
    </div>
    @endif
    <p>{{ $group->name }}</p>
    <form
        action="/group/toggleStar/{{ $group->id }}"
        method="post">
        @csrf
        <button class="star"
            @if(isset($group->notLoggedUser) && $group->notLoggedUser == 1)
            disabled
            @endif
            >
            <img
                src="
                    @if($group->pivot->is_starred === 1)
                        {{ asset('/icons/star.png') }}
                    @else
                        {{ asset('/icons/star-alt.png') }}
                    @endif
                "
                alt="star"
                class="star">
        </button>
    </form>
</div>

<style>
    .group-info-minimal {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.65rem 0.5rem;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.15s ease;
        text-decoration: none;
        color: inherit;
    }

    .group-info-minimal:hover {
        background: #f8f9fa;
    }

    .group-info-minimal .group-default-photo {
        width: 32px;
        height: 32px;
        background: #2d4a2b;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        font-weight: 600;
        flex-shrink: 0;
        border-radius: 6px;
    }

    .group-info-minimal img {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        flex-shrink: 0;
        object-fit: cover;
    }

    .group-info-minimal p {
        flex: 1;
        margin: 0;
        font-size: 0.875rem;
        font-weight: 500;
        color: #333;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .group-info-minimal form {
        margin: 0;
    }

    .group-info-minimal .star {
        background: none;
        border: none;
        padding: 0.25rem;
        margin: 0;
        flex-shrink: 0;
        cursor: pointer;
        border-radius: 3px;
        transition: background 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .group-info-minimal .star:hover {
        background: rgba(255, 193, 7, 0.08);
    }

    .group-info-minimal .star img {
        width: 16px;
        height: 16px;
    }
</style>