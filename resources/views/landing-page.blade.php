<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Carolink - Connect & Grow</title>

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
            overflow-x: hidden;
            position: relative;
            scroll-behavior: smooth;
        }

        /* Floating background elements for visual interest */

        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 20% 80%, rgba(106, 142, 97, 0.05) 0%, transparent 50%), radial-gradient(circle at 80% 20%, rgba(19, 60, 6, 0.04) 0%, transparent 50%), radial-gradient(circle at 40% 40%, rgba(9, 43, 0, 0.03) 0%, transparent 50%), radial-gradient(circle at 60% 70%, rgba(0, 102, 0, 0.02) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
            z-index: -1;
        }

        /* Decorative elements for visual richness */

        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent 48%, rgba(106, 142, 97, 0.01) 49%, rgba(106, 142, 97, 0.01) 51%, transparent 52%), linear-gradient(-45deg, transparent 48%, rgba(19, 60, 6, 0.01) 49%, rgba(19, 60, 6, 0.01) 51%, transparent 52%);
            background-size: 60px 60px;
            animation: patternShift 30s linear infinite;
            pointer-events: none;
            z-index: -1;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Hero Section */

        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            text-align: center;
            position: relative;
            background: transparent;
            animation: heroFloat 6s ease-in-out infinite;
            overflow: hidden;
        }


        .hero::before {
            content: '';
            position: absolute;
            top: 10%;
            right: 10%;
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, rgba(106, 142, 97, 0.1), rgba(19, 60, 6, 0.05));
            border-radius: 50%;
            animation: floatSlow 8s ease-in-out infinite;
            z-index: -1;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: 15%;
            left: 8%;
            width: 150px;
            height: 150px;
            background: linear-gradient(45deg, rgba(9, 43, 0, 0.08), rgba(106, 142, 97, 0.04));
            border-radius: 50%;
            animation: floatSlow 10s ease-in-out infinite reverse;
            z-index: -1;
        }

        .hero-content {
            animation: fadeInUp 1s ease-out 0.2s both;
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(6rem, 15vw, 12.5rem);
            font-weight: 400;
            margin-bottom: 2rem;
            color: var(--color-dark-green);
            line-height: 0.9;
            letter-spacing: 0.02em;
            position: relative;
            display: inline-block;
            text-transform: uppercase;
        }


        .hero h1 .letter {
            display: inline-block;
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-right: 0.05em;
            text-shadow: 0 4px 8px rgba(45, 74, 43, 0.1);
            transform-origin: center bottom;
        }

        /* Enhanced individual letter hover effects */

        .hero h1 .letter:hover {
            transform: translateY(-8px) scale(1.05) rotate(2deg);
            text-shadow: 0 8px 16px rgba(45, 74, 43, 0.2);
            color: var(--color-medium-green);
        }

        /* Staggered animation on title hover */

        .hero h1:hover .letter:nth-child(1) {
            animation-delay: 0.1s;
        }

        .hero h1:hover .letter:nth-child(2) {
            animation-delay: 0.15s;
        }

        .hero h1:hover .letter:nth-child(3) {
            animation-delay: 0.2s;
        }

        .hero h1:hover .letter:nth-child(4) {
            animation-delay: 0.25s;
        }

        .hero h1:hover .letter:nth-child(5) {
            animation-delay: 0.3s;
        }

        .hero h1:hover .letter:nth-child(6) {
            animation-delay: 0.35s;
        }

        .hero h1:hover .letter:nth-child(7) {
            animation-delay: 0.4s;
        }

        .hero h1:hover .letter:nth-child(8) {
            animation-delay: 0.45s;
        }

        .hero p {
            /* Increased description text size and updated styling */
            font-size: 1.3rem;
            color: #666;
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            font-weight: 400;
            line-height: 1.7;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            /* Increased button size for better space utilization */
            padding: 1.2rem 2.5rem;
            border: none;
            border-radius: 6px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: var(--color-dark-green);
            color: var(--color-white);
            /* Added pulse animation to CTA buttons */
            animation: subtlePulse 4s ease-in-out infinite;
        }

        /* Updated login button in header to use white background */

        .nav-links .btn-primary {
            background: var(--color-white);
            color: var(--color-pakistan-green);
        }

        .nav-links .btn-primary:hover {
            background: var(--color-light);
            color: var(--color-pakistan-green);
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.25);
        }

        /* Features Section */

        .features {
            /* Made features section full viewport height */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            background: transparent;
            position: relative;
            /* Added decorative elements to features section */
            overflow: hidden;
        }

        /* Geometric decorative elements to features section */

        .features::before {
            content: '';
            position: absolute;
            top: 5%;
            left: 5%;
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, rgba(106, 142, 97, 0.06), transparent);
            transform: rotate(45deg);
            animation: rotateFloat 12s linear infinite;
            z-index: -1;
        }

        .features::after {
            content: '';
            position: absolute;
            bottom: 10%;
            right: 8%;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(19, 60, 6, 0.05), transparent);
            clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
            animation: floatSlow 9s ease-in-out infinite;
            z-index: -1;
        }

        .features .container {
            /* Added container styling for centered content */
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
            max-height: 800px;
        }

        .section-title {
            /* Updated section titles to use Playfair Display and increased size */
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1.5rem;
            color: var(--color-dark-green);
            letter-spacing: -0.02em;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.3rem;
            color: #666;
            margin-bottom: 4rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        .carousel-container {
            position: relative;
            max-width: 1000px;
            margin: 0 auto;
            overflow: hidden;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(9, 43, 0, 0.1);
        }

        .carousel-track {
            display: flex;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: transform;
        }

        .feature-card {
            min-width: 100%;
            text-align: center;
            padding: 2rem 1.5rem;
            background: transparent;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .feature-card:hover {
            transform: translateY(-5px) scale(1.02);
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 10px 40px rgba(9, 43, 0, 0.1);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--color-sage);
            border-radius: 12px;
            margin: 0 auto 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--color-white);
            position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-card:hover .feature-icon {
            transform: rotate(5deg) scale(1.1);
            background: var(--color-medium-green);
            box-shadow: 0 8px 25px rgba(106, 142, 97, 0.3);
        }

        .feature-icon.academic::before {
            content: '';
            width: 24px;
            height: 24px;
            border: 3px solid currentColor;
            border-radius: 2px;
            position: relative;
        }

        .feature-icon.academic::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 2px;
            background: currentColor;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 -6px 0 currentColor, 0 6px 0 currentColor;
        }

        .feature-icon.community::before {
            content: '';
            width: 20px;
            height: 20px;
            border: 3px solid currentColor;
            border-radius: 50%;
            position: relative;
        }

        .feature-icon.community::after {
            content: '';
            position: absolute;
            width: 32px;
            height: 16px;
            border: 3px solid currentColor;
            border-top: none;
            border-radius: 0 0 16px 16px;
            bottom: 8px;
            left: 50%;
            transform: translateX(-50%);
        }

        .feature-icon.updates::before {
            content: '';
            width: 24px;
            height: 24px;
            border: 3px solid currentColor;
            border-radius: 4px;
            position: relative;
        }

        .feature-icon.updates::after {
            content: '';
            position: absolute;
            width: 8px;
            height: 8px;
            background: currentColor;
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: pulse 2s infinite;
        }

        .feature-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--color-dark-green);
        }

        .feature-card p {
            font-size: 1.1rem;
            color: #666;
            max-width: 450px;
            line-height: 1.7;
        }

        .carousel-indicators {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 3rem;
        }

        .indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ddd;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .indicator:hover {
            transform: scale(1.3);
            background: var(--color-medium-green);
        }

        .indicator.active {
            background: var(--color-sage);
            transform: scale(1.4);
            box-shadow: 0 0 10px rgba(106, 142, 97, 0.5);
        }

        /* Login Modal */

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(45, 74, 43, 0.4);
            backdrop-filter: blur(12px);
            animation: fadeIn 0.3s ease-out;
        }

        .modal-content {
            background: var(--color-cream);
            margin: 8% auto;
            border-radius: 20px;
            width: 90%;
            max-width: 440px;
            box-shadow: 0 30px 100px rgba(45, 74, 43, 0.2);
            overflow: hidden;
            animation: modalSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--color-dark-green), var(--color-medium-green));
            color: var(--color-white);
            padding: 2.5rem 2.5rem 2rem;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            /* background: linear-gradient(90deg, var(--color-sage), var(--color-white), var(--color-sage)); */
        }

        .modal-header h2 {
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            font-size: 2rem;
            font-weight: 600;
            letter-spacing: -0.02em;
        }

        .close {
            color: var(--color-white);
            font-size: 1.5rem;
            font-weight: 300;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            line-height: 1;
            padding: 0.75rem;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.1) rotate(90deg);
        }

        .modal-body {
            padding: 3rem 2.5rem;
            background: var(--color-cream);
        }

        /* Enhanced form group styling with floating labels */

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
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
            padding: 0.75rem;
            border: 2px solid #e5e5e5;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--color-sage-green);
            box-shadow: 0 0 0 3px rgba(106, 142, 97, 0.1);
        }

        .login-btn {
            width: 100%;
            padding: 0.75rem;
            background: var(--color-dark-green);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .login-btn:hover {
            background: var(--color-pakistan-green);
            transform: translateY(-1px);
        }

        /* Google Sign-In Custom Button Styling */
        .gsi-material-button {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
            -webkit-appearance: none;
            background-color: #f2f2f2;
            background-image: none;
            border: none;
            -webkit-border-radius: 4px;
            border-radius: 4px;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            color: #1f1f1f;
            cursor: pointer;
            font-family: 'Roboto', arial, sans-serif;
            font-size: 14px;
            height: 40px;
            letter-spacing: 0.25px;
            outline: none;
            overflow: hidden;
            padding: 0 12px;
            position: relative;
            text-align: center;
            -webkit-transition: background-color .218s, border-color .218s, box-shadow .218s;
            transition: background-color .218s, border-color .218s, box-shadow .218s;
            vertical-align: middle;
            white-space: nowrap;
            width: 100%;
            max-width: 400px;
            min-width: min-content;
            margin-bottom: 1rem;
        }

        .gsi-material-button .gsi-material-button-icon {
            height: 20px;
            margin-right: 12px;
            min-width: 20px;
            width: 20px;
        }

        .gsi-material-button .gsi-material-button-content-wrapper {
            -webkit-align-items: center;
            align-items: center;
            display: flex;
            -webkit-flex-direction: row;
            flex-direction: row;
            -webkit-flex-wrap: nowrap;
            flex-wrap: nowrap;
            height: 100%;
            justify-content: center;
            position: relative;
            width: 100%;
        }

        .gsi-material-button .gsi-material-button-contents {
            -webkit-flex-grow: 0;
            flex-grow: 0;
            font-family: 'Roboto', arial, sans-serif;
            font-weight: 500;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: top;
        }

        .gsi-material-button .gsi-material-button-state {
            -webkit-transition: opacity .218s;
            transition: opacity .218s;
            bottom: 0;
            left: 0;
            opacity: 0;
            position: absolute;
            right: 0;
            top: 0;
        }

        .gsi-material-button:disabled {
            cursor: default;
            background-color: #ffffff61;
        }

        .gsi-material-button:disabled .gsi-material-button-state {
            background-color: #1f1f1f1f;
        }

        .gsi-material-button:disabled .gsi-material-button-contents {
            opacity: 38%;
        }

        .gsi-material-button:disabled .gsi-material-button-icon {
            opacity: 38%;
        }

        .gsi-material-button:not(:disabled):active .gsi-material-button-state,
        .gsi-material-button:not(:disabled):focus .gsi-material-button-state {
            background-color: #001d35;
            opacity: 12%;
        }

        .gsi-material-button:not(:disabled):hover {
            -webkit-box-shadow: 0 1px 2px 0 rgba(60, 64, 67, .30), 0 1px 3px 1px rgba(60, 64, 67, .15);
            box-shadow: 0 1px 2px 0 rgba(60, 64, 67, .30), 0 1px 3px 1px rgba(60, 64, 67, .15);
        }

        .gsi-material-button:not(:disabled):hover .gsi-material-button-state {
            background-color: #001d35;
            opacity: 8%;
        }

        /* Hide default Google Sign-In button */
        .g_id_signin {
            display: none !important;
        }

        /* Divider styling */
        .divider {
            position: relative;
            text-align: center;
            margin: 1.5rem 0;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e5e5e5;
            z-index: 1;
        }

        .divider span {
            background: var(--color-cream);
            color: #666;
            padding: 0 1rem;
            font-size: 0.9rem;
            position: relative;
            z-index: 2;
        }

        /* Google Login Button */
        .google-login-btn {
            width: 100%;
            padding: 0.75rem;
            background: white;
            color: #333;
            border: 2px solid #e5e5e5;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .google-login-btn:hover {
            border-color: #dadce0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-1px);
        }

        .google-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .forgot-password {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .forgot-password a {
            color: var(--color-sage-green);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: var(--color-dark-green);
        }

        .register-link {
            background: var(--color-sage-green);
            color: white !important;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .register-link:hover {
            background: var(--color-dark-green);
            color: white !important;
        }

        /* Added missing CSS for form toggle functionality */

        .hidden {
            display: none !important;
        }

        .form-container {
            width: 100%;
        }

        /* Footer */

        footer {
            /* Updated footer to use darker green color */
            background: var(--color-dark-green);
            color: var(--color-white);
            padding: 2rem 0;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            position: relative;
        }

        /* Decorative elements to footer */

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        }

        .footer-content {
            margin-bottom: 1rem;
        }

        .footer-content p {
            margin-bottom: 0.5rem;
        }

        .footer-content a {
            /* Updated footer link colors for dark background */
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 4px;
        }

        .footer-content a:hover {
            color: var(--color-white);
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .footer-bottom p {
            font-size: 0.85rem;
            /* Updated footer bottom text color */
            color: rgba(255, 255, 255, 0.6);
        }

        /* Added new animations for enhanced visual elements */

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }

            50% {
                opacity: 0.7;
                transform: translate(-50%, -50%) scale(1.1);
            }
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            33% {
                transform: translateY(-20px) rotate(1deg);
            }

            66% {
                transform: translateY(10px) rotate(-1deg);
            }
        }

        @keyframes heroFloat {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes textShimmer {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        @keyframes subtlePulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.02);
            }
        }

        @keyframes floatSlow {

            0%,
            100% {
                transform: translateY(0px) translateX(0px);
            }

            25% {
                transform: translateY(-15px) translateX(10px);
            }

            50% {
                transform: translateY(-5px) translateX(-5px);
            }

            75% {
                transform: translateY(-20px) translateX(15px);
            }
        }

        @keyframes rotateFloat {
            0% {
                transform: rotate(45deg) translateY(0px);
            }

            25% {
                transform: rotate(90deg) translateY(-10px);
            }

            50% {
                transform: rotate(135deg) translateY(5px);
            }

            75% {
                transform: rotate(180deg) translateY(-15px);
            }

            100% {
                transform: rotate(225deg) translateY(0px);
            }
        }

        @keyframes patternShift {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: 60px 60px;
            }
        }

        @keyframes letterFloat {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg) scale(1);
            }

            50% {
                transform: translateY(-12px) rotate(3deg) scale(1.05);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-30px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Responsive */

        @media (max-width: 768px) {
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
            }

            /* Adjusted mobile spacing for full-height sections */
            .hero,
            .features {
                padding: 2rem 0;
            }

            /* Adjusted mobile typography for better spacing */
            .hero h1 {
                font-size: clamp(4rem, 12vw, 8rem);
                /* Also reduced mobile letter spacing to match desktop */
                letter-spacing: 0.02em;
            }
        }
    </style>
</head>

<body>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <main>
        <!-- Added id to hero section for smooth scrolling -->
        <section class="hero" id="home">
            <div class="container">

                <!-- Changed hero title from "Your University Hub" to "Carolink" -->
                <h1>
                    <span class="letter">C</span><span class="letter">a</span><span class="letter">r</span><span class="letter">o</span><span class="letter">l</span><span class="letter">i</span><span class="letter">n</span><span class="letter">k</span>

                    <p>Access classes, assignments, announcements, and social features all in one place.</p>
                    <div class="cta-buttons">
                        <a href="#" class="btn btn-primary" id="heroLoginBtn">Get Started</a>
                    </div>
            </div>
            </div>
        </section>

        <section class="features" id="features">
            <div class="container">
                <h2 class="section-title">Why Choose Carolink?</h2>
                <p class="section-subtitle">Experience unified academic and social platform designed specifically for your university community.</p>

                <div class="carousel-container">
                    <div class="carousel-track" id="carouselTrack">
                        <div class="feature-card">
                            <div class="feature-icon academic"></div>
                            <h3>Academic Integration</h3>
                            <p>Access all your classes, assignments, grades, and academic announcements in one centralized platform. No more juggling multiple systems.</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon community"></div>
                            <h3>Social Community</h3>
                            <p>Connect with classmates, join study groups, and participate in university discussions. Build meaningful relationships within your academic community.</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon updates"></div>
                            <h3>Centralized Updates</h3>
                            <p>Never miss important announcements, deadlines, or university news. Everything you need to know is delivered to your personalized feed.</p>
                        </div>
                    </div>
                </div>

                <div class="carousel-indicators">
                    <div class="indicator active" data-slide="0"></div>
                    <div class="indicator" data-slide="1"></div>
                    <div class="indicator" data-slide="2"></div>
                </div>
            </div>
        </section>
    </main>

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Made header title dynamic for login/register -->
                <h2 id="modalTitle">Student Login</h2>
                <span class="close">&times;</span>
                <h2 id="modalTitle">Student Login</h2>
                <div class="modal-body">
                    <!-- Added login form container -->
                    <div id="loginContainer" class="form-container">

                        <div style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center;">
                            {{ session('success') }}
                        </div>
                        @endif @if (session()->has('error'))
                        <div style="display: flex; background-color: #f8d7da; color: #000000; padding: 0.5rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #f5c6cb; text-align: center; align-items: center; justify-content: center;">
                            <p style='margin: 0;'>{{ session('error') }}</p>
                        </div>
                        @endif
                        <form id="loginForm" action="/login" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="email">University Email</label>
                                <input type="email" id="email" name="login-email" placeholder="id@usc.edu.ph" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="login-password" placeholder="Enter your password" required>
                            </div>
                            <button type="submit" class="login-btn">Login to Carolink</button>

                            <!-- Google Sign-In -->
                            <div class="divider"><span>or</span></div>

                            <!-- Custom Google Sign-In Button -->
                            <button class="gsi-material-button" onclick="handleGoogleSignIn()">
                                <div class="gsi-material-button-state"></div>
                                <div class="gsi-material-button-content-wrapper">
                                    <div class="gsi-material-button-icon">
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" xmlns:xlink="http://www.w3.org/1999/xlink" style="display: block;">
                                            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                                            <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                                            <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                                            <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                                            <path fill="none" d="M0 0h48v48H0z"></path>
                                        </svg>
                                    </div>
                                    <span class="gsi-material-button-contents">Sign in with Google</span>
                                    <span style="display: none;">Sign in with Google</span>
                                </div>
                            </button>

                            <!-- Hidden default Google Sign-In for functionality -->
                            <div id="g_id_onload"
                                data-client_id="495352471012-51n88psp7q90qph631ai7hnvqhsmi3ve.apps.googleusercontent.com"
                                data-callback="handleCredentialResponse">
                            </div>
                            <div class="g_id_signin"
                                data-type="standard"
                                data-theme="outline"
                                data-size="large"
                                data-text="continue_with"
                                data-shape="rectangular"
                                data-logo_alignment="left">
                            </div>

                            <div class="forgot-password">
                                <a href="#" id="showRegisterLink">Register</a>
                                <a href="#" id="forgotPasswordLink">Forgot your password?</a>
                            </div>
                        </form>
                    </div>

                    <!-- Added register form container -->
                    <div id="registerContainer" class="form-container hidden">
                        @if ($errors->any())
                        <div style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                            <ul style="margin: 0; padding-left: 1rem;">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form id="registerForm" action="/register" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="registerName">Full Name</label>
                                <input type="text" id="registerName" name="name" placeholder="Enter your name" required>
                            </div>
                            <div class="form-group">
                                <label for="registerEmail">University Email</label>
                                <input type="email" id="registerEmail" name="email" placeholder="id@usc.edu.ph" required>
                            </div>
                            <div class="form-group">
                                <label for="registerPassword">Password</label>
                                <input type="password" id="registerPassword" name="password" placeholder="Create a password" required>
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword">Re-enter Password</label>
                                <input type="password" id="confirmPassword" name="password_confirmation" placeholder="Confirm your password" required>
                            </div>
                            <button type="submit" class="login-btn">Create Account</button>
                            <div class="forgot-password">
                                <a href="#" id="showLoginLink">Back to Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <footer id="contact">
            <div class="container">
                <div class="footer-content">
                    <p>
                        <!-- Updated footer links to scroll to sections -->
                        <a href="#home">Home</a>
                        <a href="#home">Home</a>
                        <a href="#contact">Contact</a>
                    </p>
                </div>
                <div class="footer-bottom">
                    <p>&copy; 2025 Carolink. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <script>
            let currentSlide = 0;
            const slides = document.querySelectorAll('.feature-card');
            const indicators = document.querySelectorAll('.indicator');
            const track = document.getElementById('carouselTrack');
            const totalSlides = slides.length;

            function updateCarousel() {
                track.style.transform = `translateX(-${currentSlide * 100}%)`;

                indicators.forEach((indicator, index) => {
                    indicator.classList.toggle('active', index === currentSlide);
                });
            }

            function nextSlide() {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateCarousel();
            }

            setInterval(nextSlide, 4000);

            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    currentSlide = index;
                    updateCarousel();
                });
            });

            const modal = document.getElementById('loginModal');
            const heroLoginBtn = document.getElementById('heroLoginBtn');
            const closeBtn = document.getElementsByClassName('close')[0];

            heroLoginBtn.onclick = function(e) {
                e.preventDefault();
                modal.style.display = 'block';
            }

            closeBtn.onclick = function() {
                modal.style.display = 'none';
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }

            document.getElementById('loginForm').onsubmit = function(e) {
                e.preventDefault();
                this.submit();
            }

            document.getElementById('registerForm').onsubmit = function(e) {
                e.preventDefault();
                const password = document.getElementById('registerPassword').value;
                const confirmPassword = document.getElementById('confirmPassword').value;

                if (password !== confirmPassword) {
                    alert('Passwords do not match!');
                    return;
                }
                this.submit();
            }

            document.getElementById('showRegisterLink').onclick = function(e) {
                e.preventDefault();
                document.getElementById('loginContainer').classList.add('hidden');
                document.getElementById('registerContainer').classList.remove('hidden');
                document.getElementById('modalTitle').textContent = 'Student Registration';
            }

            document.getElementById('showLoginLink').onclick = function(e) {
                e.preventDefault();
                document.getElementById('registerContainer').classList.add('hidden');
                document.getElementById('loginContainer').classList.remove('hidden');
                document.getElementById('modalTitle').textContent = 'Student Login';
            }

            // Google Sign-In callback function
            function handleCredentialResponse(response) {
                const responsePayload = decodeJwtResponse(response.credential);

                console.log("ID: " + responsePayload.sub);
                console.log('Full Name: ' + responsePayload.name);
                console.log("Email: " + responsePayload.email);

                // Send the user data to backend
                fetch('/google-login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            name: responsePayload.name,
                            email: responsePayload.email
                        })
                    })
                    .then(response => {
                        if (response.redirected) {
                            window.location.href = response.url;
                        } else {
                            return response.json();
                        }
                    })
                    .catch(error => {
                        alert('Google login failed.');
                        console.error(error);
                    });
            }

            // Function to handle custom Google button click
            function handleGoogleSignIn() {
                // Trigger the hidden Google Sign-In button
                const googleButton = document.querySelector('.g_id_signin');
                if (googleButton) {
                    // Try to find and click the actual Google button inside
                    const actualButton = googleButton.querySelector('[role="button"]');
                    if (actualButton) {
                        actualButton.click();
                    } else {
                        // Fallback: trigger Google Sign-In programmatically
                        google.accounts.id.prompt();
                    }
                }
            }

            // 4. Helper function to decode the JWT token from Google.
            function decodeJwtResponse(token) {
                var base64Url = token.split('.')[1];
                var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
                var jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
                    return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
                }).join(''));
                return JSON.parse(jsonPayload);
            }
        </script>
</body>

</html>