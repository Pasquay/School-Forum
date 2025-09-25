<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAROLINK | {{ $group->name }} Settings</title>
    <meta name='csrf-token' content='{{ csrf_token() }}'>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <style>
        /* Main */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: Arial, sans-serif;
                background-color: #f5f5f5;
                padding-top: 72px;
            }

            main {
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: flex-start;
                padding: 2rem;
                gap: 2rem;
            } 

            /* SUCCESS HEADER */
                .success-message {
                    background-color: #d4edda;
                    color: #155724;
                    margin-top: -0.5rem;
                    text-align: center;
                }

            /* ERROR HEADER */
                .error-message {
                    background-color: #f8d7da; 
                    color: #721c24; 
                    margin-top: -1rem;
                    text-align: center;
                }
                
                .error-message ul {
                    margin: 0; 
                    padding-left: 1rem;
                }
                
                .error-message p {
                    margin: 0;
                }
        /* MODERATOR SETTINGS */
            .moderator-settings {
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 1px 4px rgba(0,0,0,0.06);
                padding: 2rem;
                min-width: 350px;
            }
            .moderator-list, .member-list {
                list-style: none;
                padding: 0;
                margin-bottom: 1rem;
            }
            .moderator-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 0.5rem;
            }
            .demote-btn {
                background: #dc3545;
                color: #fff;
                border: none;
                padding: 0.3rem 0.8rem;
                border-radius: 4px;
                cursor: pointer;
            }
            .add-moderator-btn {
                margin-top: 1rem;
                background: #4a90e2;
                color: #fff;
                border: none;
                padding: 0.5rem 1.2rem;
                border-radius: 4px;
                cursor: pointer;
            }
            .modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0,0,0,0.3);
                display: none;
                align-items: center;
                justify-content: center;
                z-index: 1000;
            }
            .modal-content {
                background: #fff;
                padding: 2rem;
                border-radius: 8px;
                min-width: 320px;
            }
            .modal-actions {
                display: flex;
                justify-content: flex-end;
                gap: 1rem;
                margin-top: 1rem;
            }
    </style>
</head>
<body>
    @include('components.navbar', ['active' => ''])
    @include('components.success-header')
    @include('components.error-header')
    <div class="settings-navbar">
        NAVBAR SETTINGS
    </div>
    <main>
        <div class="moderator-settings">
            <h2>Moderators</h2>
            <ul class="moderator-list">
                @foreach($moderators as $moderator)
                    <li class="moderator-item">
                        <span>{{ $moderator->name }}</span>
                        <form action="/group/{{ $group->id}}/demote-moderator/{{ $moderator->id }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="demote-btn">Demote</button>
                        </form>
                    </li>
                @endforeach
            </ul>
            <button class="add-moderator-btn" onclick="openAddModeratorModal()">Add Moderator</button>
        </div>
    </main>

    <!-- Add Moderator Modal -->
        <div id="addModeratorModal" class="modal" style="display:none;">
            <div class="modal-content">
                <h3>Add Moderator</h3>
                <input type="text" id="memberSearch" placeholder="Search members..." oninput="filterMembers()">
                <form id="addModeratorForm" action="/group/{{ $group->id }}/add-moderators" method="POST">
                    @csrf
                    <ul id="memberList" class="member-list">
                        @foreach($members as $member)
                            <li>
                                <label>
                                    <input type="checkbox" name="moderators[]" value="{{ $member->id }}">
                                    {{ $member->name }}
                                </label>
                            </li>
                        @endforeach
                    </ul>
                    <div class="modal-actions">
                        <button type="button" onclick="closeAddModeratorModal()">Cancel</button>
                        <button type="submit">Add Moderator</button>
                    </div>
                </form>
            </div>
        </div>

</body>
<script>
    function openAddModeratorModal() {
        document.getElementById('addModeratorModal').style.display = 'flex';
    }
    function closeAddModeratorModal() {
        document.getElementById('addModeratorModal').style.display = 'none';
    }
    function filterMembers() {
        const search = document.getElementById('memberSearch').value.toLowerCase();
        document.querySelectorAll('#memberList li').forEach(li => {
            li.style.display = li.textContent.toLowerCase().includes(search) ? '' : 'none';
        });
    }
    document.getElementById('addModeratorForm').onsubmit = function() {
        closeAddModeratorModal();
    };
</script>
</html>