<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAROLINK | Profile Settings</title>
    <meta name='csrf-token' content='{{ csrf_token() }}'>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <style>
        /* MAIN */
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


            #profile-nav-link {
                color: #4a90e2;
            }

            #profile-nav-link:hover {
                color: #357abd;
            }

            main {
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: flex-start;
                padding: 2rem;
                gap: 2rem;
            }

        /* SETTINGS LAYOUT */
            .settings-container {
                flex: 1;
                width: 100%;
                max-width: none;
            }

            .settings-header {
                margin-bottom: 1rem;
                text-align: start;
            }

            .settings-header h1 {
                color: #333;
                font-size: 2rem;
                font-weight: bold;
                margin-bottom: 1rem;
                margin-left: 1.5rem;
                background: none;
                box-shadow: none;
                padding: 0;
            }

            .header-separator {
                width: 100%;
                height: 1px;
                background-color: #ddd;
                margin: 0 auto;
            }

            /* Responsive header */
            @media (max-width: 768px) {
                .settings-header h1 {
                    font-size: 1.5rem;
                    margin-bottom: 0.8rem;
                }
            }
        /* SETTINGS NAV */
            .settings-nav {
                background-color: white;
                width: 100%;
                max-width: none;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                padding: 1rem;
                display: flex;
                justify-content: flex-start;  /* Add this to align left */
                gap: 0.5rem;  /* Add gap instead of margins */
                transition: transform 0.2s ease;
            }

            .settings-nav button.first {
                margin-left: 0;  /* Keep this for consistency */
            }

            .settings-nav button {
                border: 0;
                cursor: pointer;
                color: #666;
                background-color: white;
                text-decoration: none;
                font-weight: 500;
                font-size: medium;
                transition: color 0.2s;
                padding: 0.75rem 1.5rem;  /* Increase vertical, reduce horizontal padding */
                margin: 0;  /* Remove margins since we're using gap */
                flex: none;  /* Remove flex: 1 to stop buttons from stretching */
                text-align: center;
                border-radius: 0.5rem;  /* Add border-radius to all buttons */
                white-space: nowrap;  /* Prevent text wrapping */
            }

            .settings-nav button:hover {
                color: #4a90e2;
                background-color: #f8f9fa;  /* Add subtle hover background */
            }

            .settings-nav button.active {
                border: 0;
                border-radius: 0.5rem;
                cursor: pointer;
                color: #4a90e2;
                background-color: #eaf4fb;
                text-decoration: none;
                font-size: medium;
                transition: all 0.2s ease;  /* Smooth all transitions */
            }

            .settings-nav button.active:hover {
                color: #357abd;  /* Darker blue on hover */
                background-color: #dae9f6;  /* Slightly darker background */
            }

        /* SETTINGS COLUMNS */
            .groups-column,
            .profile-column, 
            .preferences-column {
                width: 100%; /* Use full width */
                max-width: none; /* Remove max-width constraint */
                margin: 1.5rem 0 1.5rem 0;
                padding: 2rem;
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
            }

            .settings-placeholder {
                text-align: center;
                color: #666;
                font-style: italic;
                padding: 3rem 0;
            }
            /* Form Fields - Match Create Group Style */
                h3 {
                    margin: 0 0 0.4rem 0;
                }

                .form-group {
                    display: flex;
                    flex-direction: column;
                    gap: 0.5rem;
                    margin-bottom: 1rem;
                }
                
                .form-group label {
                    font-weight: 500;
                    color: #333;
                    font-size: 1rem;
                    margin-bottom: 0.25rem;
                }
                
                .form-group input,
                .form-group textarea {
                    padding: 0.75rem;
                    border: 1px solid #ddd;
                    border-radius: 6px;
                    font-size: 0.9rem;
                    background: #fff;
                    transition: border-color 0.2s;
                }
                
                .form-group input:focus,
                .form-group textarea:focus {
                    border-color: #4a90e2;
                    outline: none;
                }
                
                .form-group small {
                    color: #888;
                    font-size: 0.85rem;
                    margin-top: -0.25rem;
                }
            /* Save Changes Button Style */
                .sliding-btn {
                    background-color: #4a90e2;
                    color: #fff;
                    border: none;
                    border-radius: 6px;
                    padding: 0.75rem 1rem;
                    cursor: pointer;
                    box-shadow: 0 2px 4px rgba(74, 144, 226, 0.08);
                    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
                    align-self: flex-start;
                }
                
                .sliding-btn:hover,
                .sliding-btn:focus {
                    background-color: #357abd;
                    color: #fff;
                    box-shadow: 0 4px 8px rgba(74, 144, 226, 0.15);
                    outline: none;
                }
        /* RESPONSIVE */
            @media (max-width: 768px) {
                main {
                    flex-direction: column;
                    padding: 1rem; /* This provides the margin on mobile */
                }
                
                .settings-container {
                    width: 100%;
                }
                
                .settings-header,
                .settings-nav,
                .groups-column,
                .profile-column,
                .preferences-column {
                    padding: 1.5rem; /* Reduce padding on mobile */
                }
            }
    </style>
</head>
<body data-user-id='{{ Auth::id() }}'>
    @include('components.navbar', ['active' => 'profile'])
    @include('components.success-header')
    @include('components.error-header')
    <main>
        <div class="settings-container">
            <div class="settings-header">
                <h1>Settings</h1>
                <div class="header-separator"></div>
            </div>
            <div class="settings-nav">
                <button type="submit" data-tab="profile" class='active first'>Profile</button>
                <button type="submit" data-tab="preferences">Preferences</button>
                <button type="submit" data-tab="groups">Groups</button>
            </div>

            <div class="profile-column" id='profile-column'>
                <h2>Profile Settings</h2>
                <div class="header-separator"></div>
                <div class="profile-public-information">
                    <h3>Public Information</h3>
                    <form id="publicProfileForm" method="POST" action="{{ route('user.updatePublicProfile', ['id' => Auth::id()]) }}">
                        @csrf
                        <div class="form-group">
                            <label for="bio">Bio</label>
                            <textarea id="bio" name="bio" rows="3" maxlength="200" placeholder="Tell us about yourself...">{{ Auth::user()->bio ?? '' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="social_links">Social Links</label>
                            <input type="url" name="social_links[]" value="{{ Auth::user()->social_links[0] ?? '' }}" placeholder="https://yourprofile.com" />
                            <input type="url" name="social_links[]" value="{{ Auth::user()->social_links[1] ?? '' }}" placeholder="https://yourprofile.com" />
                            <input type="url" name="social_links[]" value="{{ Auth::user()->social_links[2] ?? '' }}" placeholder="https://yourprofile.com" />
                            <small>Paste up to 3 links to your social profiles.</small>
                        </div>
                        <button type="submit" class="sliding-btn">Save Changes</button>
                    </form>
                </div>
                <div class="header-separator"></div>
                <div class="profile-account-security">
                    <h3>Account & Security</h3>
                            -Change email<br>
                            -Change Password<br>
                            -Link Google<br>
                </div>
            </div>

            <div class="preferences-column" id='preferences-column' style='display:none;'>
                <h2>Preferences</h2>
                <div class="settings-placeholder">
                    Features:<br>
                        Preferences (Default startup page on login)<br>
                            -startup page (home/profile/chosen group)<br>
                            -muted communities<br>
                            -followed communities<br>
                            -starred communities
                </div>
            </div>

            <div class="groups-column" id='groups-column' style='display:none;'>
                <h2>Groups Settings</h2>
                <div class="settings-placeholder">
                    Features:<br>
                        manage groups
                </div>
            </div>
        </div>
    </main>
    @include('components.back-to-top-button')
</body>
<script>
    // VARIABLES
    const userID = document.body.dataset.userId;

    // NAVIGATION ELEMENTS
    const navButtons = document.querySelectorAll('.settings-nav button');
    const profileCol = document.querySelector('.profile-column');
    const preferencesCol = document.querySelector('.preferences-column');
    const groupsCol = document.querySelector('.groups-column');

    // NAVIGATION FUNCTION
    function showTab(tabName) {
        // Hide all columns
        groupsCol.style.display = 'none';
        profileCol.style.display = 'none';
        preferencesCol.style.display = 'none';
        
        // Remove active class from all buttons
        navButtons.forEach(btn => btn.classList.remove('active'));
        
        // Show selected column and activate button
        const activeButton = document.querySelector(`[data-tab="${tabName}"]`);
        
        switch(tabName) {
            case 'groups':
                groupsCol.style.display = 'flex';
                break;
            case 'profile':
                profileCol.style.display = 'flex';
                break;
            case 'preferences':
                preferencesCol.style.display = 'flex';
                break;
        }
        
        if (activeButton) {
            activeButton.classList.add('active');
        }
    }

    // NAVIGATION EVENT LISTENERS
    navButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const tabName = button.dataset.tab;
            showTab(tabName);
        });
    });

    // Initialize - show profile tab by default
    showTab('profile');
</script>
</html>