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
            <div class="search-section">
                <input type="text" placeholder="Search groups..." id="group-search">
            </div>

            @if($groups->count()>0)
            @foreach($groups as $group)
            @include('components.group-info-manager', ['group' => $group])
            @endforeach
            @endif

        </div>
        <div class="right-side">
            Sticky group overview card
            Group Overview
            <div class="row-1">
                no. of groups joined
                no. of groups moderated
                no. of groups created
            </div>
            <div class="row-2">
                no. of educational groups
                no. of social groups
                no. of private groups
            </div>
            <div class="row-3">
                no. of groups starred
                no. of groups muted
            </div>
            maybe some chart.js shit here idk if naa pay extra space
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
<script src="{{ asset('js/group-manager.js') }}"></script>

</html>