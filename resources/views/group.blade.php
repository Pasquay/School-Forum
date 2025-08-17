<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media | {{ $group->name }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                color: #4a90e2;  /* Changed from #333 to match the blue theme */
                text-decoration: none;
                transition: color 0.2s;  /* Added transition for hover effect */
            }

            .brand:hover {
                color: #357abd;  /* Added hover state to match other interactive elements */
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
        /* Group Info Top */
            /* Group Banner */
            /* Group Photo */
                .group-info.top .group-photo {
                    padding-right: 4px;
                }

                .group-info.top .group-default-photo {
                    width: 64px;
                    height: 64px;
                    background-color: #4a90e2;
                    color: white;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 2rem;
                    flex-shrink: 0;
                    border-radius: 50%;
                }

                .group-info.top .group-photo img {
                    width: 64px;
                    height: 64px;
                    border-radius: 8px;
                    flex-shrink: 0;
                    object-fit: cover;
                }
            /* Group Name */
        /*  */
        /*  */
        /*  */
        /*  */
    </style>
</head>
<body>
    @include('components.navbar')
    @include('components.success-header')
    @include('components.error-header')
    <main>
        <div class="group-info top" style="display:none">
            @if($group->banner)
                <img src="{{ asset('storage/' . $group->banner) }}" id="banner" alt="Group Banner">
            @endif
            @if($group->photo)
                <img src="{{ asset('storage/' . $group->photo) }}" id="photo" alt="Group Photo">
            @else
                <div class="group-default-photo">
                    {{ strtoupper(substr($group->name, 0, 1)) }}
                </div>
            @endif
            <p class="group-name">{{ $group->name }}</p>
        </div>
    </main>
    @include('components.back-to-top-button')
</body>
</html>