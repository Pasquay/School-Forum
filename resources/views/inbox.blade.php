<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

            main {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 2rem;
                gap: 2rem;
            } 
    </style>
</head>
<body>
    @include('components.navbar', ['active' => 'inbox'])
    @include('components.success-header')
    @include('components.error-header')
    <main>
        <div class="">
            NAVBAR FOR SORTING MESSAGE TYPES HERE
        </div>
        <div class="message-container">
            WHERE MESSAGES ARE STORED
            <div class="unread-messages">
                <h2>UNREAD MESSAGES</h2>
                @foreach($unreadMessages as $message)
                    @include('components.inbox-message', ['message' => $message])
                @endforeach
            </div>

            <div class="read-messages">
                <h2>READ MESSAGES</h2>
                @foreach($readMessages as $message)
                    @include('components.inbox-message', ['message' => $message])
                @endforeach
            </div>
        </div>
    </main>
</body>
</html>