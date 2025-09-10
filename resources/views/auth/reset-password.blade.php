<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - Carolink</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --color-light: #EDEDED;
            --color-white: #FFFFFF;
            --color-dark-green: #2d4a2b;
            --color-medium-green: #133C06;
            --color-sage: #6A8E61;
            --color-sage-green: #6A8E61;
            --color-pakistan-green: #2d4a2b;
            --color-cream: #f5f3f0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: var(--color-dark-green);
            background: var(--color-cream);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .reset-container {
            background: var(--color-white);
            border-radius: 15px;
            box-shadow: 0 30px 100px rgba(45, 74, 43, 0.2);
            width: 100%;
            max-width: 500px;
            padding: 3rem 2.5rem;
            text-align: center;
        }

        .reset-container h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--color-dark-green);
            font-size: 2rem;
        }

        .reset-container p {
            font-size: 14px;
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--color-dark-green);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e5e5;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f0f0f0;
        }

        .form-group input:focus {
            outline: none;
            background-color: #e8e8e8;
            border-color: var(--color-sage-green);
            box-shadow: 0 0 0 2px rgba(106, 142, 97, 0.1);
        }

        .reset-btn {
            width: 100%;
            padding: 12px 45px;
            border-radius: 20px;
            border: 1px solid var(--color-dark-green);
            background-color: var(--color-dark-green);
            color: var(--color-white);
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: all 0.2s ease-in;
            cursor: pointer;
            margin: 20px 0;
        }

        .reset-btn:hover {
            background-color: var(--color-pakistan-green);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(45, 74, 43, 0.3);
        }

        .reset-btn:active {
            transform: scale(0.95);
        }

        .back-to-login {
            color: var(--color-sage-green);
            font-size: 14px;
            text-decoration: none;
            margin-top: 20px;
            transition: color 0.3s ease;
            display: inline-block;
        }

        .back-to-login:hover {
            color: var(--color-dark-green);
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 1rem;
            font-size: 14px;
        }

        .error-message ul {
            margin: 0;
            padding-left: 1rem;
            list-style-type: disc;
        }

        .error-message li {
            margin-bottom: 0.25rem;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 1rem;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h1>Reset Password</h1>
        <p>Enter your new password below. Make sure it's at least 8 characters long.</p>

        @if (session()->has('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
        @endif

        @if (session()->has('error'))
        <div class="error-message">
            {{ session('error') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="error-message">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="form-group">
                <label for="email">University Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', request('email')) }}" placeholder="id@usc.edu.ph" required>
            </div>

            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" placeholder="Enter new password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" required>
            </div>

            <button type="submit" class="reset-btn">Reset Password</button>
        </form>

        <a href="/" class="back-to-login">← Back to Login</a>
    </div>

    <script>
        document.querySelector('form').onsubmit = function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
        }
    </script>
</body>
</html>
