<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAROLINK | Profile Settings</title>
    <meta name='csrf-token' content='{{ csrf_token() }}'>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-settings.css') }}">
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
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                            <label for="reset-password">Reset Password</label>
                            <small>Tip: Use a strong, unique password with a minimum of 8 characters for your account.</small>
                            <button type="submit" class="sliding-btn" name="reset-password" style="background:#ff9800; margin: 0;">Send Password Reset Email</button>
                        </div>
                    </form>
                </div>
                <div class="form-group bottom">
                    <label>Link Google Account</label>
                    <small>Linking is only available if your account is not already connected to Google.</small>
                    @if(Auth::user()->google_id)
                    <button class="sliding-btn" style="background:#e0e0e0; color:#888; cursor:not-allowed;" disabled>
                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" style="height:1em;vertical-align:middle;margin-right:0.5em;">
                        Google Account Linked
                    </button>
                    @else
                    <form method="POST" action="{{ route('user.linkGoogle') }}">
                        @csrf
                        <button type="submit" class="sliding-btn" style="background:#fff; color:#444; border:1px solid #ddd;">
                            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" style="height:1em;vertical-align:middle;margin-right:0.5em;">
                            Link Google Account
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </main>
    @include('components.back-to-top-button')
</body>

</html>