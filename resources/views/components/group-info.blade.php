@php
use Illuminate\Support\Facades\DB;
@endphp
<div
    class="group-info"
    id="group-info-{{ $group->id }}"
    data-groupid="{{ $group->id }}">
    <div class="group-photo">
        @if($group->photo)
        <img src="{{ asset('storage/' . $group->photo) }}" alt="Group photo">
        @else
        <div class="group-default-photo">
            {{ strtoupper(substr($group->name, 0, 1)) }}
        </div>
        @endif
    </div>

    <div class="group-details">
        <p class="group-name">{{ $group->name }}</p>
        <p class="description">{{ Str::limit($group->description, 80) }}</p>
        <div class="member-details">
            <p class="member-count">
                @if($group->member_count > 1)
                {{ $group->member_count }} Members
                @else
                {{ $group->member_count }} Member
                @endif
            </p>
            <p class="online-member-count">
                @if($group->posts_count == 1)
                {{ $group->posts_count }} Post
                @else
                {{ $group->posts_count }} Posts
                @endif
            </p>
        </div>
    </div>

    <div class="join-leave">
        @php
        $userMembership = DB::table('group_members')
        ->where('group_id', $group->id)
        ->where('user_id', Auth::id())
        ->exists();
        @endphp
        <form action="" method="POST">
            @csrf
            @if($group->owner_id === Auth::id())
            <button type="button" class="manage-button" onclick="window.location.href='{{ url('/group/' . $group->id . '?settings=1') }}'">Manage</button>
            @elseif($userMembership)
            <button type="submit" class="leave-button">Leave</button>
            @elseif(!$group->is_private)
            <button type="submit" class="join-button">Join</button>
            @else
            <button type="submit" class="request-button"
                @if($group->requested)
                disabled
                @endif
                >Request to Join</button>
            @endif
        </form>
    </div>
</div>
<style>
    /* Group Info Card */
    .group-info {
        cursor: pointer;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .group-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    /* Group Photo */
    .group-info .group-photo {
        padding-right: 4px;
    }

    .group-info .group-default-photo {
        width: 64px;
        height: 64px;
        background-color: #2d4a2b;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        flex-shrink: 0;
        border-radius: 8px;
    }

    .group-info .group-photo img {
        width: 64px;
        height: 64px;
        border-radius: 8px;
        flex-shrink: 0;
        object-fit: cover;
    }

    /* Group Details */
    .group-info .group-details {
        flex: 1;
        min-width: 0;
    }

    .group-info .group-name {
        margin: 0 0 0.5rem 0;
        color: #333;
        font-size: 1.2rem;
        font-weight: 600;
        line-height: 1.3;
    }

    .group-info .description {
        margin: 0 0 1rem 0;
        color: #666;
        font-size: 0.9rem;
        line-height: 1.4;
    }

    .group-info .member-details {
        display: flex;
        gap: 2rem;
        font-size: 0.9rem;
        color: #666;
    }

    .group-info .member-count,
    .group-info .online-member-count {
        margin: 0;
    }

    /* Join/Leave/Manage/Request Button */
    .group-info .join-leave {
        flex-shrink: 0;
        display: flex;
        align-items: flex-start;
    }

    .group-info .join-button,
    .group-info .leave-button {
        background-color: #4a90e2;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        width: 68px;
        text-align: center;
        border: none;
        cursor: pointer;
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .group-info .join-button {
        background-color: #28a745;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        width: 68px;
        text-align: center;
        border: none;
        cursor: pointer;
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .group-info .join-button:hover {
        background-color: #218838;
    }

    .group-info .leave-button {
        background-color: #dc3545;
    }

    .group-info .leave-button:hover {
        background-color: #c82333;
    }

    .group-info .manage-button {
        background-color: #2d4a2b;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        width: 68px;
        text-align: center;
        border: none;
        cursor: pointer;
        font-weight: 500;
        transition: background-color 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .group-info .manage-button:hover {
        background-color: #357abd;
    }

    .group-info .request-button {
        background-color: #f57c00;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        width: 68px;
        min-width: 68px;
        max-width: 120px;
        text-align: center;
        border: none;
        cursor: pointer;
        font-weight: 500;
        line-height: 1.2;
        transition: background-color 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .group-info .request-button:hover {
        background-color: #ef6c00;
    }
</style>