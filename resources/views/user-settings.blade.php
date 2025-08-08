<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media | Profile Settings</title>
    <meta name='csrf-token' content='{{ csrf_token() }}'>
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

            .navbar {
                background-color: white;
                padding: 1rem 2rem;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                display: flex;
                justify-content: space-between;
                align-items: center;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                z-index: 1000;
            }

            .brand {
                font-size: 1.5rem;
                font-weight: bold;
                color: #4a90e2;
                text-decoration: none;
                transition: color 0.2s; 
            }

            .brand:hover {
                color: #357abd; 
            }

            .nav-links {
                display: flex;
                gap: 2rem;
                align-items: center;
            }

            .nav-link {
                color: #666;
                text-decoration: none;
                font-weight: 500;
                transition: color 0.2s;
            }

            .nav-link:hover {
                color: #4a90e2;
            }

            #profile-nav-link {
                color: #4a90e2;
            }

            #profile-nav-link:hover {
                color: #357abd;
            }

            .logout-btn {
                background-color: #4a90e2;
                color: white;
                padding: 0.5rem 1rem;
                border-radius: 6px;
                border: none;
                cursor: pointer;
                font-weight: 500;
                transition: background-color 0.2s;
            }

            .logout-btn:hover {
                background-color: #357abd;
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
                width: 100%; /* Use full width */
                max-width: none; /* Remove max-width constraint */
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                padding: 1rem;
                display: flex;
                transition: transform 0.2s ease;
            }

            .settings-nav button.first {
                margin-left: 0;
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
                padding: 0.5rem 1rem;
                margin: 0 0.6rem;
                flex: 1;
                text-align: center;
            }

            .settings-nav button:hover {
                color: #4a90e2;
            }

            .settings-nav button.active {
                border: 0;
                border-radius: 0.5rem;
                cursor: pointer;
                color: #4a90e2;
                background-color: #eaf4fb;
                text-decoration: none;
                font-weight: 500;
                font-size: medium;
                transition: color 0.2s;
            }

            .settings-nav button.active:hover {
                color: #666;
                background-color: #e9eef3;
            }

        /* SETTINGS COLUMNS */
            .account-column,
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
                .account-column,
                .profile-column,
                .preferences-column {
                    padding: 1.5rem; /* Reduce padding on mobile */
                }
            }
    </style>
</head>
<body data-user-id='{{ Auth::id() }}'>
    @include('components.navbar')
    @include('components.success-header')
    @include('components.error-header')
    <main>
        <div class="settings-container">
            <div class="settings-header">
                <h1>Settings</h1>
                <div class="header-separator"></div>
            </div>
            <div class="settings-nav">
                <form action="#" method='GET' id='account-form'>
                    @csrf
                    <button type="submit" class='active first'>Account</button>
                </form>
                <form action="#" method='GET' id='profile-form'>
                    @csrf
                    <button type="submit">Profile</button>
                </form>
                <form action="#" method='GET' id='preferences-form'>
                    @csrf
                    <button type="submit">Preferences</button>
                </form>
            </div>

            <div class="account-column" id='account-column'>
                <h2>Account Settings</h2>
                <div class="settings-placeholder">
                    Features:<br>
                        Account (system security and acc details)<br>
                            -change email<br>
                            -change password<br>
                            -link account<br>
                                -google<br>
                                -facebook<br>
                            -delete account
                </div>
            </div>

            <div class="profile-column" id='profile-column' style='display:none;'>
                <h2>Profile Settings</h2>
                <div class="settings-placeholder">
                    Features:<br>
                        Profile (Whats publicly displayed in your page)<br>
                            -change username<br>
                            -change bio<br>
                            -change birthday<br>
                            -manage social links<br>
                            -show what groups you follow?
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
        </div>
    </main>
</body>
<script>
    // VARIABLES
        const userID = document.body.dataset.userId;

    // NAVIGATION
        const settingsNav = document.querySelector('.settings-nav');
        
        const accountForm = settingsNav.querySelector('#account-form');
        const accountBtn = settingsNav.querySelector('#account-form button');
        const accountCol = document.querySelector('.account-column');
        
        const profileForm = settingsNav.querySelector('#profile-form');
        const profileBtn = settingsNav.querySelector('#profile-form button');
        const profileCol = document.querySelector('.profile-column');

        const preferencesForm = settingsNav.querySelector('#preferences-form');
        const preferencesBtn = settingsNav.querySelector('#preferences-form button');
        const preferencesCol = document.querySelector('.preferences-column');

    // NAVIGATION EVENT LISTENERS
        // Account
        accountForm.addEventListener('submit', (e) => {
            e.preventDefault();
            if(!accountBtn.classList.contains('active')){
                accountBtn.classList.add('active');
                accountCol.style.display = 'flex';
            }
            profileBtn.classList.remove('active');
            profileCol.style.display = 'none';
            preferencesBtn.classList.remove('active');
            preferencesCol.style.display = 'none';
        });

        // Profile
        profileForm.addEventListener('submit', (e) => {
            e.preventDefault();
            accountBtn.classList.remove('active');
            accountCol.style.display = 'none';
            if(!profileBtn.classList.contains('active')){
                profileBtn.classList.add('active');
                profileCol.style.display = 'flex';
            }
            preferencesBtn.classList.remove('active');
            preferencesCol.style.display = 'none';
        });

        // Preferences
        preferencesForm.addEventListener('submit', (e) => {
            e.preventDefault();
            accountBtn.classList.remove('active');
            accountCol.style.display = 'none';
            profileBtn.classList.remove('active');
            profileCol.style.display = 'none';
            if(!preferencesBtn.classList.contains('active')){
                preferencesBtn.classList.add('active');
                preferencesCol.style.display = 'flex';
            }
        });
</script>
</html>