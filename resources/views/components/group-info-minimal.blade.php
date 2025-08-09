<div class="group-info-minimal" id="group-info-minimal-{{ $group->id }}">
    @if($group->photo)
        <img src="" alt="Photo">
    @else
        <div class="group-default-photo">
            {{ strtoupper(substr($group->name, 0, 1)) }}
        </div>
    @endif
    <p>{{ $group->name }}</p>
    <button class="star">
        <img 
            src="
                @if($group->pivot->is_starred === 1)
                    {{ asset('storage/icons/star.png') }}
                @else
                    {{ asset('storage/icons/star-alt.png') }}
                @endif
            " 
            alt="star" 
            class="star"
        >
    </button>
</div>

<style>
    /* Main */
        .group-info-minimal {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .group-info-minimal:last-child {
            border-bottom: none;
        }

        .group-info-minimal:hover {
            background-color: #f8f9fa;
            border-radius: 6px;
            margin: 0 -0.5rem;
            padding: 0.75rem 0.5rem;
        }
    /* Group Photo */
        .group-info-minimal .group-default-photo {
            width: 32px;
            height: 32px;
            background-color: #4a90e2;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
            border-radius: 8px;
        }

        .group-info-minimal img {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            flex-shrink: 0;
            object-fit: cover;
        }
    /* Star */
        .group-info-minimal .star {
            background: none;
            border: none;
            padding: 0;
            margin-left: auto;
            flex-shrink: 0;
            cursor: pointer;
        }

        .group-info-minimal .star img {
            width: 16px;
            height: 16px;
        }
</style>