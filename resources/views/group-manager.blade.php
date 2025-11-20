<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAROLINK | Group Manager</title>
    <meta name='csrf-token' content='{{ csrf_token() }}'>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/group-manager.css') }}">
</head>

<body>
    @include('components.navbar', ['active' => ''])
    @include('components.success-header')
    @include('components.error-header')
    <main>
        <div class="left-side">
            <form id="group-search-form" method="GET" action="{{ route('groups.manager.search') }}">
                <div class="search-section">
                    <div class="search-bar-row">
                        <input type="text" placeholder="Search groups..." id="group-search" name="group-search">
                        <button id="search-btn">
                            <img src="{{ asset('/icons/search.png') }}" alt="Search">
                        </button>
                        <button id="toggle-filters-btn">
                            <img src="{{ asset('/icons/filter.png') }}" alt="Filters">
                        </button>
                    </div>
                    <div id="search-filters-panel" style="display: none;">
                        <div class="filters-sort-row">
                            <div class="filter-group">
                                <span class="filter-label">Filter by:</span>
                                <div class="filter-row">
                                    <span class="filter-label">Membership:</span>
                                    <label><input type="checkbox" name="membership[]" value="member"> Member</label>
                                    <label><input type="checkbox" name="membership[]" value="moderator"> Moderator</label>
                                    <label><input type="checkbox" name="membership[]" value="owner"> Owner</label>
                                </div>
                                <div class="filter-row">
                                    <span class="filter-label">Type:</span>
                                    <label><input type="checkbox" name="type[]" value="educational"> Educational</label>
                                    <label><input type="checkbox" name="type[]" value="social"> Social</label>
                                </div>
                                <div class="filter-row">
                                    <span class="filter-label">Status:</span>
                                    <label><input type="checkbox" name="status[]" value="public"> Public</label>
                                    <label><input type="checkbox" name="status[]" value="private"> Private</label>
                                </div>
                                <div class="filter-row">
                                    <span class="filter-label">Starred:</span>
                                    <label><input type="checkbox" name="starred[]" value="1"> Starred</label>
                                    <label><input type="checkbox" name="starred[]" value="0"> Unstarred</label>
                                </div>
                                <div class="filter-row">
                                    <span class="filter-label">Muted:</span>
                                    <label><input type="checkbox" name="muted[]" value="1"> Muted</label>
                                    <label><input type="checkbox" name="muted[]" value="0"> Unmuted</label>
                                </div>
                            </div>
                            <div class="sort-group">
                                <span class="filter-label">Sort by:</span>
                                <div class="sort-buttons">
                                    <button type="button" class="sort-btn active" data-sort="membership">Membership</button>
                                    <button type="button" class="sort-btn" data-sort="alphabetic">Alphabetic</button>
                                    <button type="button" class="sort-btn" data-sort="member_count">Member Count</button>
                                    <button type="button" class="sort-btn" data-sort="join_date">Join Date</button>
                                </div>
                            </div>
                        </div>
                        <div class="filter-actions">
                            <button type="button" id="clear-filters-btn">Clear Filters</button>
                        </div>
                    </div>
                </div>
            </form>

            <div id="group-bulk-actions">
                <div class="group-bulk-select">
                    <button id="select-all-button">Select all</button>
                    <button id="select-none-button">Select none</button>
                </div>
                <div class="bulk-actions" style="display:inline;">
                    <form id="bulk-star-form" action="{{ route('group.set.star') }}" method="post">
                        @csrf
                        <button type="submit" disabled style="cursor:default; opacity:0.5;">
                            <img src="{{ asset('/icons/star.png') }}" alt="">
                        </button>
                    </form>
                    <form id="bulk-unstar-form" action="{{ route('group.set.star') }}" method="post">
                        @csrf
                        <button type="submit" disabled style="cursor:default; opacity:0.5;">
                            <img src="{{ asset('/icons/star-alt.png') }}" alt="">
                        </button>
                    </form>
                    <form id="bulk-unmute-form" action="{{ route('group.set.mute') }}" method="post">
                        @csrf
                        <button type="submit" disabled style="cursor:default; opacity:0.5;">
                            <img src="{{ asset('/icons/mute-alt.png') }}" alt="">
                        </button>
                    </form>
                    <form id="bulk-mute-form" action="{{ route('group.set.mute') }}" method="post">
                        @csrf
                        <button type="submit" disabled style="cursor:default; opacity:0.5;">
                            <img src="{{ asset('/icons/mute.png') }}" alt="">
                        </button>
                    </form>
                    <form id="bulk-leave-form" action="#" method="post">
                        @csrf
                        <button type="button" class="leave-button" disabled style="cursor:default; opacity:0.5;">Leave</button>
                    </form>
                </div>
            </div>

            <div id="group-list-container">
                @if($groups->count()>0)
                    @foreach($groups as $group)
                        @include('components.group-info-manager', ['group' => $group])
                    @endforeach
                @endif
            </div>

            <div class="group-pagination">
                @if($groups->lastPage() > 1)
                    <nav class="pagination-nav">
                        {{-- Previous button --}}
                        @if($groups->currentPage() > 1)
                            <a href="{{ $groups->url($groups->currentPage() - 1) }}" class="pagination-btn">Prev</a>
                        @endif

                        {{-- Page numbers --}}
                        @for($i = 1; $i <= $groups->lastPage(); $i++)
                            @if($i == $groups->currentPage())
                                <span class="pagination-btn active">{{ $i }}</span>
                            @else
                                <a href="{{ $groups->url($i) }}" class="pagination-btn">{{ $i }}</a>
                            @endif
                        @endfor

                        {{-- Next button --}}
                        @if($groups->currentPage() < $groups->lastPage())
                            <a href="{{ $groups->url($groups->currentPage() + 1) }}" class="pagination-btn">Next</a>
                        @endif
                    </nav>
                @endif
            </div>

        </div>
        <div class="right-side">
            <div class="group-overview-header">
                <h2>Group Overview</h2>
            </div>
                <div class="group-overview-rows">
                    <div class="group-overview-row">
                        <div class="overview-card">
                            <div class="card-label">Groups Joined</div>
                            <div class="card-value">{{ $groupJoinedCount }}</div>
                        </div>
                        <div class="overview-card">
                            <div class="card-label">Groups Moderated</div>
                            <div class="card-value">{{ $groupModeratedCount }}</div>
                        </div>
                        <div class="overview-card">
                            <div class="card-label">Groups Created</div>
                            <div class="card-value">{{ $groupCreatedCount }}</div>
                        </div>
                    </div>
                    <div class="group-overview-row">
                        <div class="overview-card">
                            <div class="card-label">Educational Groups</div>
                            <div class="card-value">{{ $groupEducationalCount }}</div>
                        </div>
                        <div class="overview-card">
                            <div class="card-label">Social Groups</div>
                            <div class="card-value">{{ $groupSocialCount }}</div>
                        </div>
                        <div class="overview-card">
                            <div class="card-label">Private Groups</div>
                            <div class="card-value">{{ $groupPrivateCount }}</div>
                        </div>
                    </div>
                    <div class="group-overview-row">
                        <div class="overview-card">
                            <div class="card-label">Public Groups</div>
                            <div class="card-value">{{ $groupPublicCount }}</div>
                        </div>
                        <div class="overview-card">
                            <div class="card-label">Groups Starred</div>
                            <div class="card-value">{{ $groupStarredCount }}</div>
                        </div>
                        <div class="overview-card">
                            <div class="card-label">Groups Muted</div>
                            <div class="card-value">{{ $groupMutedCount }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="leave-confirmation-modal" style="display: none;">
        <div class="modal-content">
            <p id="modal-header"></p>
            <div id="group-list"></div>
            <div id="modal-actions">
                <button id="modal-leave-btn">Leave</button>
                <button id="modal-cancel-btn">Cancel</button>
            </div>
        </div>
    </div>

    @include('components.back-to-top-button')
</body>
<script>
    window.STAR_ICON = "{{ asset('/icons/star.png') }}";
    window.STAR_ALT_ICON = "{{ asset('/icons/star-alt.png') }}";
    window.MUTE_ICON = "{{ asset('/icons/mute.png') }}";
    window.MUTE_ALT_ICON = "{{ asset('/icons/mute-alt.png') }}";
</script>
<script src="{{ asset('js/group-manager.js') }}"></script>
</html>