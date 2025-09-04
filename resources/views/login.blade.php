<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media | Login</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        #login, #register {
            background: white;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            color: #333;
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 1.75rem;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        input {
            padding: 0.875rem;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            font-size: 1rem;
            width: 100%;
            box-sizing: border-box;
            transition: all 0.2s ease;
        }

        input:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.15);
        }

        button {
            background-color: #4a90e2;
            color: white;
            padding: 0.875rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin-top: 1rem;
            transition: background-color 0.2s;
        }

        button:hover {
            background-color: #357abd;
        }

        p {
            text-align: center;
            color: #666;
            margin: 1rem 0 0.5rem 0;
            display: inline-block;
        }

        a {
            color: #4a90e2;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        a:hover {
            color: #357abd;
        }

        br {
            display: none;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div id="login" class=''>
        <h2>Login</h2>
        @if (session()->has('success'))
            <div style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center;">
                {{ session('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div style="display: flex; background-color: #f8d7da; color: #000000; padding: 0.5rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #f5c6cb; text-align: center; align-items: center; justify-content: center;">
                <p style='margin: 0;'>{{ session('error') }}</p>
            </div>
        @endif
        <form action="/login" method="post">
            @csrf
            <input type="text" name="login-email" placeholder="Email" required>
            <input type="password" name="login-password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="" id="showRegister">Sign up</a></p>
    </div>
    <div id="register" class='hidden'>
        <h2>Sign Up</h2>
        @if (session()->has('success'))
            <div style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center;">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="/register" method="post">
            @csrf
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email address" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="password_confirmation" placeholder="Confirm password" required>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="" id="showLogin">Login</a></p>
    </div>
</body>
<script>
    const showRegister = document.getElementById('showRegister');
    const showLogin = document.getElementById('showLogin');

    const login = document.getElementById('login');
    const register = document.getElementById('register');

    showRegister.addEventListener('click', (e) => {
        e.preventDefault();
        login.classList.add('hidden');
        register.classList.remove('hidden');
    });
    showLogin.addEventListener('click', (e) => {
        e.preventDefault();
        register.classList.add('hidden');
        login.classList.remove('hidden');
    });
</script>
</html>