<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Carolink - Connect & Grow</title>
    <!-- Updated font import to use modern serif font Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
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
            /* Made hero section full viewport height */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            text-align: center;
            position: relative;
            background: transparent;
            animation: heroFloat 6s ease-in-out infinite;
            /* Added decorative elements to hero section */
            overflow: hidden;
        }

        /* Decorative circles to hero section */

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
            /* Increased subtitle size */
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
            flex: 1;
            overflow-y: auto;
            max-height: calc(90vh - 120px);
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

        /* Sliding Form Styles */
        .sliding-container {
            /* border-radius: 15px; */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            overflow: visible;
            background: var(--color-cream);
            width: 95%;
            max-width: 900px;
            height: 600px;
            max-height: 90vh;
            box-shadow: 0 30px 100px rgba(45, 74, 43, 0.2);
            opacity: 0;
            animation: slidingContainerFadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) 0.1s forwards;
        }

        /* Modal Close Button - positioned on top right of modal */
        .modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            color: var(--color-dark-green);
            font-size: 1.8rem;
            font-weight: 300;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            line-height: 1;
            padding: 0.5rem;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 200;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        /* Close button styling when sign-in form is active (default state) */
        .sliding-container:not(.right-panel-active) .modal-close {
            background: var(--color-dark-green);
            color: var(--color-white);
            box-shadow: 0 2px 10px rgba(45, 74, 43, 0.3);
        }

        /* Close button styling when sign-up form is active */
        .sliding-container.right-panel-active .modal-close {
            background: rgba(255, 255, 255, 0.9);
            color: var(--color-dark-green);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .modal-close:hover {
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        /* Hover effects for different states */
        .sliding-container:not(.right-panel-active) .modal-close:hover {
            background: var(--color-medium-green);
            color: var(--color-white);
            box-shadow: 0 4px 15px rgba(45, 74, 43, 0.4);
        }

        .sliding-container.right-panel-active .modal-close:hover {
            background: rgba(255, 255, 255, 1);
            color: var(--color-medium-green);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        /* Session Messages */
        .session-message {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10000;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            animation: slideInDown 0.3s ease-out;
            max-width: 90%;
            width: auto;
            min-width: 280px;
            text-align: center;
            word-wrap: break-word;
            transition: all 0.3s ease;
        }

        .session-message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .session-message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .session-message ul {
            margin: 0;
            padding-left: 1rem;
            list-style-type: disc;
            text-align: left;
        }

        .session-message li {
            margin-bottom: 0.25rem;
        }

        .session-message.fade-out {
            opacity: 0;
            transform: translateX(-50%) translateY(-20px);
        }

        .sliding-form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        .sliding-form-container form {
            background-color: var(--color-white);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            height: 100%;
            text-align: center;
            position: relative;
        }

        .sliding-form-container h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            margin: 0 0 0 0;
            color: var(--color-dark-green);
            font-size: 2rem;
        }

        .sliding-form-container input {
            background-color: #f0f0f0;
            border: none;
            padding: 10px 12px;
            margin: 6px 0;
            width: 100%;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .sliding-form-container input:focus {
            outline: none;
            background-color: #e8e8e8;
            box-shadow: 0 0 0 2px var(--color-sage-green);
        }

        /* Inline Error Messages for sliding forms */
        .sliding-form-container .form-error {
            color: #dc3545;
            font-size: 11px;
            margin: 2px 0 6px 0;
            display: none;
            animation: fadeInError 0.3s ease-out;
            text-align: left;
            line-height: 1.3;
        }

        .sliding-form-container .form-error.show {
            display: block;
        }

        .sliding-form-container .form-error ul {
            margin: 0;
            padding-left: 1rem;
            list-style-type: disc;
        }

        .sliding-form-container .form-error li {
            margin-bottom: 2px;
        }

        .sliding-form-container .input-error {
            border: 1px solid #dc3545 !important;
            background-color: #fff5f5 !important;
        }

        .sliding-btn {
            border-radius: 20px;
            border: 1px solid var(--color-dark-green);
            background-color: var(--color-dark-green);
            color: var(--color-white);
            font-size: 12px;
            font-weight: bold;
            padding: 10px 35px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: all 0.2s ease-in;
            cursor: pointer;
            margin: 8px 0;
        }

        .sliding-btn:active {
            transform: scale(0.95);
        }

        .sliding-btn:focus {
            outline: none;
        }

        .sliding-btn:hover {
            background-color: var(--color-pakistan-green);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(45, 74, 43, 0.3);
        }

        .sliding-btn.ghost {
            background-color: transparent;
            border-color: var(--color-white);
            color: var(--color-white);
        }

        .sliding-btn.ghost:hover {
            background-color: var(--color-white);
            color: var(--color-dark-green);
        }

        .sign-in-container {
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .sliding-container.right-panel-active .sign-in-container {
            transform: translateX(100%);
        }

        .sign-up-container {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
        }

        .sliding-container.right-panel-active .sign-up-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: show 0.6s;
        }

        @keyframes show {

            0%,
            49.99% {
                opacity: 0;
                z-index: 1;
            }

            50%,
            100% {
                opacity: 1;
                z-index: 5;
            }
        }

        .sliding-overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: transform 0.6s ease-in-out;
            z-index: 100;
        }

        .sliding-container.right-panel-active .sliding-overlay-container {
            transform: translateX(-100%);
        }

        .sliding-overlay {
            background: linear-gradient(135deg, var(--color-dark-green), var(--color-medium-green));
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 0 0;
            color: var(--color-white);
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .sliding-container.right-panel-active .sliding-overlay {
            transform: translateX(50%);
        }

        .sliding-overlay-panel {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .sliding-overlay-panel h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            font-size: 1.8rem;
            margin: 0;
        }

        .sliding-overlay-panel p {
            font-size: 14px;
            font-weight: 400;
            line-height: 20px;
            letter-spacing: 0.5px;
            margin: 20px 0 30px;
        }

        .overlay-left {
            transform: translateX(-20%);
        }

        .sliding-container.right-panel-active .overlay-left {
            transform: translateX(0);
        }

        .overlay-right {
            right: 0;
            transform: translateX(0);
        }

        .sliding-container.right-panel-active .overlay-right {
            transform: translateX(20%);
        }

        .social-container {
            margin: 15px 0;
        }

        .social-btn {
            border: 1px solid #DDDDDD;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin: 0 0px;
            height: 50px;
            width: 50px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .social-btn:hover {
            background-color: #f8f9fa;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .social-btn svg {
            width: 24px;
            height: 24px;
        }

        .form-subtitle {
            font-size: 11px;
            color: #666;
            margin: 8px 0 15px 0;
        }

        .forgot-link {
            color: var(--color-sage-green);
            font-size: 12px;
            text-decoration: none;
            margin: 15px 0;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: var(--color-dark-green);
        }

        /* Forgot Password Modal Styles */
        .forgot-password-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--color-white);
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 40px;
            text-align: center;
            z-index: 150;
            border-radius: 15px;
        }

        .forgot-password-container.active {
            display: flex;
        }

        .forgot-password-container h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--color-dark-green);
            font-size: 2rem;
        }

        .forgot-password-container p {
            font-size: 14px;
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
            max-width: 350px;
        }

        .forgot-password-container input {
            background-color: #f0f0f0;
            border: none;
            padding: 12px 15px;
            margin: 8px 0 20px 0;
            width: 100%;
            max-width: 350px;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .forgot-password-container input:focus {
            outline: none;
            background-color: #e8e8e8;
            box-shadow: 0 0 0 2px var(--color-sage-green);
        }

        .forgot-password-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .back-to-login {
            color: var(--color-sage-green);
            font-size: 12px;
            text-decoration: none;
            margin-top: 20px;
            transition: color 0.3s ease;
            cursor: pointer;
        }

        .back-to-login:hover {
            color: var(--color-dark-green);
        }

        /* Email Sent Success Message */
        .email-sent-message {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--color-white);
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 40px;
            text-align: center;
            z-index: 160;
            border-radius: 15px;
        }

        .email-sent-message.active {
            display: flex;
        }

        .email-sent-message .success-icon {
            width: 80px;
            height: 80px;
            background: var(--color-sage-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            animation: successPulse 0.6s ease-out;
        }

        .email-sent-message .success-icon::after {
            content: '✓';
            color: white;
            font-size: 2.5rem;
            font-weight: bold;
        }

        .email-sent-message h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--color-dark-green);
            font-size: 2rem;
        }

        .email-sent-message p {
            font-size: 14px;
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
            max-width: 400px;
        }

        @keyframes successPulse {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Inline Error Messages */
        .form-error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            margin-bottom: 10px;
            display: none;
            animation: fadeInError 0.3s ease-out;
        }

        .form-error.show {
            display: block;
        }

        .form-error ul {
            margin: 0;
            padding-left: 1rem;
            list-style-type: disc;
        }

        .form-error li {
            margin-bottom: 3px;
        }

        .input-error {
            border-color: #dc3545 !important;
            background-color: #fff5f5 !important;
        }

        .form-success {
            color: #28a745;
            font-size: 12px;
            margin-top: 5px;
            margin-bottom: 10px;
            display: none;
            animation: fadeInError 0.3s ease-out;
        }

        .form-success.show {
            display: block;
        }

        @keyframes fadeInError {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        /* Spinner wowowowow */

        /* From Uiverse.io by bociKond */


        .spinner {
            width: 70.4px;
            height: 70.4px;
            --clr: rgb(247, 197, 159);
            --clr-alpha: rgb(247, 197, 159, .1);
            animation: spinner 1.6s infinite ease;
            transform-style: preserve-3d;
            margin-bottom: 1rem;
        }

        /* Loading overlay styles */
        #loadingOverlay {
            display: none !important;
        }

        #loadingOverlay.show {
            display: flex !important;
        }

        .spinner>div {
            background-color: var(--color-green-100);
            height: 100%;
            position: absolute;
            width: 100%;
            border: 3.5px solid var(--color-sage-green);
        }

        .spinner div:nth-of-type(1) {
            transform: translateZ(-35.2px) rotateY(180deg);
        }

        .spinner div:nth-of-type(2) {
            transform: rotateY(-270deg) translateX(50%);
            transform-origin: top right;
        }

        .spinner div:nth-of-type(3) {
            transform: rotateY(270deg) translateX(-50%);
            transform-origin: center left;
        }

        .spinner div:nth-of-type(4) {
            transform: rotateX(90deg) translateY(-50%);
            transform-origin: top center;
        }

        .spinner div:nth-of-type(5) {
            transform: rotateX(-90deg) translateY(50%);
            transform-origin: bottom center;
        }

        .spinner div:nth-of-type(6) {
            transform: translateZ(35.2px);
        }

        @keyframes spinner {
            0% {
                transform: rotate(45deg) rotateX(-25deg) rotateY(25deg);
            }

            50% {
                transform: rotate(45deg) rotateX(-385deg) rotateY(25deg);
            }

            100% {
                transform: rotate(45deg) rotateX(-385deg) rotateY(385deg);
            }
        }

        @keyframes spinner {
            0% {
                transform: rotate(45deg) rotateX(-25deg) rotateY(25deg);
            }

            50% {
                transform: rotate(45deg) rotateX(-385deg) rotateY(25deg);
            }

            100% {
                transform: rotate(45deg) rotateX(-385deg) rotateY(385deg);
            }
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

        @keyframes slidingContainerFadeIn {
            from {
                opacity: 0;
                transform: translate(-50%, -50%) translateY(-20px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translate(-50%, -50%) translateY(0) scale(1);
            }
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translate(-50%, -50%) translateY(-30px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translate(-50%, -50%) translateY(0) scale(1);
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

            /* Session message responsive adjustments */
            .session-message {
                max-width: 95%;
                min-width: 250px;
                padding: 0.8rem 1rem;
                font-size: 0.9rem;
                top: 15px;
            }
        }

        @media (max-width: 600px) {

            /* Mobile sliding form adjustments */
            .sliding-container {
                width: 95%;
                max-width: 95vw;
                height: 550px;
                max-height: 90vh;
                border-radius: 12px;
            }

            /* Session message mobile adjustments */
            .session-message {
                max-width: 98%;
                min-width: 200px;
                padding: 0.7rem 0.8rem;
                font-size: 0.85rem;
                top: 10px;
                left: 1%;
                right: 1%;
                transform: none;
                margin: 0 auto;
                word-break: break-word;
            }

            .session-message ul {
                padding-left: 0.8rem;
            }

            .modal-close {
                top: 15px;
                right: 15px;
                width: 36px;
                height: 36px;
                font-size: 1.5rem;
            }

            .sliding-form-container {
                width: 100% !important;
                left: 0 !important;
                transform: translateX(0) !important;
                opacity: 1 !important;
                z-index: 2 !important;
            }

            .sliding-container.right-panel-active .sign-in-container {
                transform: translateX(-100%) !important;
                opacity: 0;
                z-index: 1;
            }

            .sliding-container.right-panel-active .sign-up-container {
                transform: translateX(0) !important;
                opacity: 1;
                z-index: 2;
            }

            .sliding-overlay-container {
                display: none;
            }

            .sliding-form-container form {
                padding: 20px;
            }

            .sliding-form-container h1 {
                font-size: 1.5rem;
            }

            .sliding-form-container input {
                padding: 10px 12px;
                font-size: 14px;
                margin: 5px 0;
            }

            .sliding-btn {
                padding: 10px 30px;
                font-size: 11px;
            }

            .form-subtitle {
                font-size: 11px;
                margin: 8px 0 15px 0;
            }
        }

        /* For short screens (when window is dragged vertically) */
        @media (max-height: 600px) {
            .sliding-container {
                height: 400px;
                max-height: 95vh;
            }

            .sliding-form-container form {
                padding: 15px 30px;
            }

            .sliding-form-container h1 {
                font-size: 1.4rem;
                margin-bottom: 10px;
            }

            .sliding-form-container input {
                padding: 8px 12px;
                margin: 4px 0;
            }

            .sliding-btn {
                padding: 8px 25px;
                margin: 5px 0;
            }

            .forgot-password-container {
                padding: 20px;
            }

            .forgot-password-container h1 {
                font-size: 1.4rem;
                margin-bottom: 10px;
            }

            .forgot-password-container p {
                font-size: 12px;
                margin-bottom: 15px;
            }
        }

        /* For very short screens */
        @media (max-height: 400px) {
            .sliding-container {
                height: 350px;
                max-height: 98vh;
            }

            .sliding-form-container form {
                padding: 10px 20px;
            }

            .sliding-form-container h1 {
                font-size: 1.2rem;
                margin-bottom: 8px;
            }

            .sliding-form-container input {
                padding: 6px 10px;
                font-size: 14px;
                margin: 3px 0;
            }

            .sliding-btn {
                padding: 6px 20px;
                font-size: 11px;
                margin: 3px 0;
            }

            .form-subtitle {
                font-size: 10px;
                margin: 5px 0 10px 0;
            }

            .forgot-password-container {
                padding: 15px;
            }

            .forgot-password-container h1 {
                font-size: 1.2rem;
                margin-bottom: 8px;
            }

            .forgot-password-container p {
                font-size: 11px;
                margin-bottom: 12px;
            }

            .forgot-password-container input {
                padding: 6px 10px;
                margin: 3px 0 15px 0;
            }


        }
    </style>
</head>

<body>
    <!-- Session Messages -->
    @if (session()->has('success'))
    <div class="session-message success">
        {{ session('success') }}
    </div>
    @endif
    @if (session()->has('error'))
    <div class="session-message error">
        {{ session('error') }}
    </div>
    @endif
    @if ($errors->any())
    <div class="session-message error">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <main>
        <!-- Added id to hero section for smooth scrolling -->
        <section class="hero" id="home">
            <div class="container">
                <div class="hero-content">
                    <!-- Changed hero title from "Your University Hub" to "Carolink" -->
                    <h1>
                        <span class="letter">C</span><span class="letter">a</span><span class="letter">r</span><span class="letter">o</span><span class="letter">l</span><span class="letter">i</span><span class="letter">n</span><span class="letter">k</span>
                    </h1>
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

        <!-- Sliding Form Container -->
        <div class="sliding-container" id="slidingContainer">
            <!-- Close Button -->
            <span class="modal-close" onclick="document.getElementById('loginModal').style.display='none'">&times;</span>

            <!-- Sign In Form -->
            <div class="sliding-form-container sign-in-container">
                <form id="loginForm" action="/login" method="post">
                    @csrf
                    <h1>Student Login</h1>

                    <div class="social-container">
                        <button type="button" class="social-btn gsi-material-button" onclick="handleGoogleSignIn()">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" style="width: 20px; height: 20px;">
                                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                            </svg>
                        </button>
                    </div>
                    <span class="form-subtitle">or use your university email</span>

                    <input type="email" name="login-email" id="loginEmail" placeholder="id@usc.edu.ph" required />
                    <div class="form-error" id="loginEmailError"></div>

                    <input type="password" name="login-password" id="loginPassword" placeholder="Password" required />
                    <div class="form-error" id="loginPasswordError"></div>
                    <div class="form-error" id="loginGeneralError"></div>

                    <a href="#" class="forgot-link" id="forgotPasswordLink">Forgot your password?</a>
                    <button type="submit" class="sliding-btn">Sign In</button>

                </form>
            </div>

            <!-- Sign Up Form -->
            <div class="sliding-form-container sign-up-container">
                <form id="registerForm" action="/register" method="post">

                    @csrf
                    <h1>Create Account</h1>

                    <div class="social-container">
                        <button type="button" class="social-btn gsi-material-button" onclick="handleGoogleSignIn()">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" style="width: 20px; height: 20px;">
                                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                            </svg>
                        </button>
                    </div>
                    <span class="form-subtitle">or use your email for registration</span>

                    <input type="text" name="name" id="registerName" placeholder="Full Name" required />
                    <div class="form-error" id="registerNameError"></div>

                    <input type="email" name="email" id="registerEmail" placeholder="id@usc.edu.ph" required />
                    <div class="form-error" id="registerEmailError"></div>

                    <input type="password" id="registerPassword" name="password" placeholder="Password" required />
                    <div class="form-error" id="registerPasswordError"></div>

                    <input type="password" id="confirmPassword" name="password_confirmation" placeholder="Confirm Password" required />
                    <div class="form-error" id="confirmPasswordError"></div>
                    <div class="form-error" id="registerGeneralError"></div>

                    <button type="submit" class="sliding-btn">Sign Up</button>
                </form>
            </div>

            <!-- Forgot Password Form -->
            <div class="forgot-password-container" id="forgotPasswordContainer">
                <form id="forgotPasswordForm" action="{{ route('password.email') }}" method="post">
                    @csrf
                    <h1>Reset Password</h1>
                    <p>Enter your university email address and we'll send you a link to reset your password.</p>

                    <input type="email" name="email" id="forgotEmail" placeholder="id@usc.edu.ph" required />
                    <div class="form-error" id="forgotEmailError"></div>

                    <div class="forgot-password-buttons">
                        <button type="submit" class="sliding-btn">Send Reset Link</button>
                    </div>

                    <a href="#" class="back-to-login" id="backToLogin">← Back to Login</a>
                </form>
            </div>

            <!-- Email Sent Success Message -->
            <div class="email-sent-message" id="emailSentMessage">
                <div class="success-icon"></div>
                <h1>Email Sent!</h1>
                <p>We've sent a password reset link to your university email address. Please check your inbox and follow the instructions to reset your password.</p>
                <p><strong>Don't see the email?</strong> Check your spam folder or wait a few minutes for it to arrive.</p>

                <div class="forgot-password-buttons">
                    <button type="button" class="sliding-btn" id="backToLoginFromSuccess">Back to Login</button>
                </div>
            </div>

            <!-- Sliding Overlay -->
            <div class="sliding-overlay-container">
                <div class="sliding-overlay">
                    <div class="sliding-overlay-panel overlay-left">
                        <h1>Welcome Back!</h1>
                        <p>To keep connected with your university community, please login with your personal info</p>
                        <button class="sliding-btn ghost" id="signIn">Sign In</button>
                    </div>
                    <div class="sliding-overlay-panel overlay-right">
                        <h1>Hello, Student!</h1>
                        <p>Enter your details and start your journey with Carolink</p>
                        <button class="sliding-btn ghost" id="signUp">Sign Up</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden Google Sign-In Elements -->
        <div id="g_id_onload" style="display: none;"
            data-client_id="495352471012-51n88psp7q90qph631ai7hnvqhsmi3ve.apps.googleusercontent.com"
            data-callback="handleCredentialResponse">
        </div>
        <div class="g_id_signin" style="display: none;"
            data-type="standard"
            data-theme="outline"
            data-size="large"
            data-text="continue_with"
            data-shape="rectangular"
            data-logo_alignment="left">
        </div>
    </div>
    </div>



    <footer id="contact">
        <div class="container">
            <div class="footer-content">
                <p>
                    <a href="#home">Home</a>
                    <a href="#features">Features</a>
                    <a href="#contact">Contact</a>
                </p>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Carolink. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Auto-hide session messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const sessionMessages = document.querySelectorAll('.session-message');
            sessionMessages.forEach(message => {
                // Auto-hide after 5 seconds
                setTimeout(() => {
                    message.classList.add('fade-out');
                    // Remove from DOM after fade animation
                    setTimeout(() => {
                        message.remove();
                    }, 300);
                }, 5000);

                // Allow manual close by clicking on the message
                message.addEventListener('click', function() {
                    this.classList.add('fade-out');
                    setTimeout(() => {
                        this.remove();
                    }, 300);
                });

                // Make it more interactive - pause auto-hide on hover
                let autoHideTimeout;
                let removeTimeout;

                const startAutoHide = () => {
                    autoHideTimeout = setTimeout(() => {
                        message.classList.add('fade-out');
                        removeTimeout = setTimeout(() => {
                            message.remove();
                        }, 300);
                    }, 5000);
                };

                const cancelAutoHide = () => {
                    clearTimeout(autoHideTimeout);
                    clearTimeout(removeTimeout);
                    message.classList.remove('fade-out');
                };

                message.addEventListener('mouseenter', cancelAutoHide);
                message.addEventListener('mouseleave', startAutoHide);

                // Start initial auto-hide
                startAutoHide();
            });
        });

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
        const closeBtn = document.getElementsByClassName('modal-close')[0];

        // Modal controls
        heroLoginBtn.onclick = function(e) {
            e.preventDefault();
            modal.style.display = 'block';
        }

        if (closeBtn) {
            closeBtn.onclick = function() {
                modal.style.display = 'none';
            }
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Sliding form controls
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const slidingContainer = document.getElementById('slidingContainer');

        if (signUpButton && signInButton && slidingContainer) {
            signUpButton.addEventListener('click', () => {
                slidingContainer.classList.add("right-panel-active");
            });

            signInButton.addEventListener('click', () => {
                slidingContainer.classList.remove("right-panel-active");
            });
        }

        // Forgot Password controls
        const forgotPasswordLink = document.getElementById('forgotPasswordLink');
        const forgotPasswordContainer = document.getElementById('forgotPasswordContainer');
        const backToLoginLink = document.getElementById('backToLogin');
        const signInContainer = document.querySelector('.sign-in-container');
        const overlayContainer = document.querySelector('.sliding-overlay-container');
        const emailSentMessage = document.getElementById('emailSentMessage');
        const backToLoginFromSuccess = document.getElementById('backToLoginFromSuccess');

        if (forgotPasswordLink && forgotPasswordContainer && backToLoginLink && signInContainer) {
            forgotPasswordLink.addEventListener('click', (e) => {
                e.preventDefault();
                // Hide the sign-in form and overlay, show forgot password form
                signInContainer.style.display = 'none';
                if (overlayContainer) overlayContainer.style.display = 'none';
                forgotPasswordContainer.classList.add('active');
                // Remove any active panel state to show default styling
                slidingContainer.classList.remove("right-panel-active");
            });

            backToLoginLink.addEventListener('click', (e) => {
                e.preventDefault();
                // Hide forgot password form and show sign-in form and overlay
                forgotPasswordContainer.classList.remove('active');
                signInContainer.style.display = 'block';
                if (overlayContainer) overlayContainer.style.display = 'block';
            });
        }

        // Handle back to login from success message
        if (backToLoginFromSuccess) {
            backToLoginFromSuccess.addEventListener('click', (e) => {
                e.preventDefault();
                // Hide success message and show sign-in form and overlay
                if (emailSentMessage) emailSentMessage.classList.remove('active');
                signInContainer.style.display = 'block';
                if (overlayContainer) overlayContainer.style.display = 'block';
            });
        }

        // Form validation and utility functions
        function clearErrors() {
            const errors = document.querySelectorAll('.form-error.show');
            const errorInputs = document.querySelectorAll('.input-error');

            errors.forEach(error => {
                error.classList.remove('show');
                error.innerHTML = '';
            });

            errorInputs.forEach(input => {
                input.classList.remove('input-error');
            });
        }

        function showError(inputId, errorId, message) {
            const input = document.getElementById(inputId);
            const errorDiv = document.getElementById(errorId);

            if (input) input.classList.add('input-error');
            if (errorDiv) {
                errorDiv.innerHTML = message;
                errorDiv.classList.add('show');
            }
        }

        function validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function validatePassword(password) {
            const errors = [];
            if (password.length < 8) {
                errors.push('Must be at least 8 characters long');
            }
            if (!/[A-Z]/.test(password)) {
                errors.push('Must contain at least one uppercase letter');
            }
            if (!/[a-z]/.test(password)) {
                errors.push('Must contain at least one lowercase letter');
            }
            if (!/[0-9]/.test(password)) {
                errors.push('Must contain at least one number');
            }
            return errors;
        }

        // Form submissions with validation and AJAX
        document.getElementById('loginForm').onsubmit = function(e) {
            document.getElementById('loadingOverlay').classList.add('show');
            e.preventDefault();
            clearErrors();

            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            // Client-side validation
            let hasErrors = false;

            if (!email) {
                showError('loginEmail', 'loginEmailError', 'Email is required');
                hasErrors = true;
            } else if (!validateEmail(email)) {
                showError('loginEmail', 'loginEmailError', 'Please enter a valid email address');
                hasErrors = true;
            }

            if (!password) {
                showError('loginPassword', 'loginPasswordError', 'Password is required');
                hasErrors = true;
            }

            if (hasErrors) {
                document.getElementById('loadingOverlay').classList.remove('show');
                return;
            }

            // Show loading state
            submitBtn.textContent = 'Signing In...';
            submitBtn.disabled = true;

            // AJAX request
            fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        'login-email': email,
                        'login-password': password
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        // Handle HTTP errors (401, 422, etc.)
                        return response.json().then(data => {
                            throw new Error(data.message || 'Login failed');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        animateLoadingTransition(data.redirect || '/home');
                    } else {
                        // Show server errors
                        console.log('Server response:', data); // Debug log
                        if (data.errors) {
                            if (data.errors['login-email']) {
                                showError('loginEmail', 'loginEmailError', data.errors['login-email'][0]);
                            }
                            if (data.errors['login-password']) {
                                showError('loginPassword', 'loginPasswordError', data.errors['login-password'][0]);
                            }
                        } else if (data.message) {
                            showError('', 'loginGeneralError', data.message);
                        } else {
                            showError('', 'loginGeneralError', 'Login failed. Please try again.');
                        }
                        // Hide loading overlay for errors
                        document.getElementById('loadingOverlay').classList.remove('show');
                    }
                })
                .catch(error => {
                    console.error('Login error:', error);
                    // Show the actual error message from server if available
                    showError('', 'loginGeneralError', error.message || 'Network error. Please try again.');
                    // Hide loading overlay for errors
                    document.getElementById('loadingOverlay').classList.remove('show');
                })
                .finally(() => {
                    // Only reset button state, don't hide overlay (animation handles that)
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                });
        }

        document.getElementById('registerForm').onsubmit = function(e) {
            document.getElementById('loadingOverlay').classList.add('show');
            e.preventDefault();
            clearErrors();

            const name = document.getElementById('registerName').value;
            const email = document.getElementById('registerEmail').value;
            const password = document.getElementById('registerPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            // Client-side validation
            let hasErrors = false;

            if (!name) {
                showError('registerName', 'registerNameError', 'Full name is required');
                hasErrors = true;
            }

            if (!email) {
                showError('registerEmail', 'registerEmailError', 'Email is required');
                hasErrors = true;
            } else if (!validateEmail(email)) {
                showError('registerEmail', 'registerEmailError', 'Please enter a valid email address');
                hasErrors = true;
            }

            if (!password) {
                showError('registerPassword', 'registerPasswordError', 'Password is required');
                hasErrors = true;
            } else {
                const passwordErrors = validatePassword(password);
                if (passwordErrors.length > 0) {
                    showError('registerPassword', 'registerPasswordError',
                        '<ul>' + passwordErrors.map(err => '<li>' + err + '</li>').join('') + '</ul>');
                    hasErrors = true;
                }
            }

            if (!confirmPassword) {
                showError('confirmPassword', 'confirmPasswordError', 'Please confirm your password');
                hasErrors = true;
            } else if (password !== confirmPassword) {
                showError('confirmPassword', 'confirmPasswordError', 'Passwords do not match');
                hasErrors = true;
            }

            if (hasErrors) {
                document.getElementById('loadingOverlay').classList.remove('show');
                return;
            }

            // Show loading state
            submitBtn.textContent = 'Creating Account...';
            submitBtn.disabled = true;

            // AJAX request
            fetch('/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        password: password,
                        password_confirmation: confirmPassword
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message with animation
                        animateLoadingTransition('/home');
                    } else {
                        // Show server errors
                        if (data.errors) {
                            if (data.errors.name) {
                                showError('registerName', 'registerNameError', data.errors.name[0]);
                            }
                            if (data.errors.email) {
                                showError('registerEmail', 'registerEmailError', data.errors.email[0]);
                            }
                            if (data.errors.password) {
                                showError('registerPassword', 'registerPasswordError', data.errors.password[0]);
                            }
                        } else if (data.message) {
                            showError('', 'registerGeneralError', data.message);
                        }
                        // Hide loading overlay for errors
                        document.getElementById('loadingOverlay').classList.remove('show');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('', 'registerGeneralError', 'Wrong Email or Password!');
                    // Hide loading overlay for errors
                    document.getElementById('loadingOverlay').classList.remove('show');
                })
                .finally(() => {
                    // Only reset button state, don't hide overlay (animation handles that)
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                });
        }

        // Forgot Password form submission
        document.getElementById('forgotPasswordForm').onsubmit = function(e) {
            e.preventDefault();

            // Clear any previous errors
            clearErrors();

            // Validate email
            const email = document.getElementById('forgotEmail').value;
            if (!validateEmail(email)) {
                showError('forgotEmail', 'forgotEmailError', 'Please enter a valid university email address.');
                return;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Sending...';
            submitBtn.disabled = true;

            // Submit the form via AJAX
            const formData = new FormData(this);
            const formElement = this;

            fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data); // Debug log

                    // Reset button state first
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;

                    if (data.success) {
                        console.log('Success received, switching views...');

                        // Get elements fresh
                        const forgotContainer = document.getElementById('forgotPasswordContainer');
                        const successMessage = document.getElementById('emailSentMessage');

                        console.log('Forgot container found:', !!forgotContainer);
                        console.log('Success message found:', !!successMessage);

                        // Hide forgot password form
                        if (forgotContainer) {
                            forgotContainer.classList.remove('active');
                            console.log('Forgot container hidden');
                        }

                        // Show success message
                        if (successMessage) {
                            successMessage.classList.add('active');
                            console.log('Success message shown');
                        }

                        // Reset form after a small delay
                        setTimeout(() => {
                            formElement.reset();
                        }, 100);

                    } else {
                        console.log('Error in response:', data);
                        // Handle errors
                        if (data.errors && data.errors.email) {
                            showError('forgotEmail', 'forgotEmailError', data.errors.email[0]);
                        } else if (data.message) {
                            showError('forgotEmail', 'forgotEmailError', data.message);
                        } else {
                            showError('forgotEmail', 'forgotEmailError', 'An error occurred. Please try again.');
                        }
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                    showError('forgotEmail', 'forgotEmailError', 'An error occurred. Please try again.');
                });
        }

        // Google Sign-In callback function
        function handleCredentialResponse(response) {
            document.getElementById('loadingOverlay').classList.add('show');
            const responsePayload = decodeJwtResponse(response.credential);

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
                        animateLoadingTransition(response.url);
                    } else {
                        return response.json().then(data => {
                            if (data.success) {
                                animateLoadingTransition(data.redirect || '/home');
                            } else {
                                throw new Error(data.message || 'Google login failed');
                            }
                        });
                    }
                })
                .catch(error => {
                    document.getElementById('loadingOverlay').classList.remove('show');
                    alert('Google login failed.');
                    console.error(error);
                });
        }

        function handleGoogleSignIn() {
            const googleButton = document.querySelector('.g_id_signin');
            if (googleButton) {
                const actualButton = googleButton.querySelector('[role="button"]');
                if (actualButton) {
                    actualButton.click();
                } else {
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

        function animateLoadingTransition(nextUrl) {
            // Check if GSAP is available
            if (typeof gsap === 'undefined') {
                window.location.href = nextUrl;
                return;
            }

            // Make sure overlay is visible
            const overlay = document.getElementById('loadingOverlay');
            const spinner = document.querySelector('.spinner');
            const spinnerDivs = document.querySelectorAll('.spinner > div');
            const loadingText = overlay.querySelector('p');

            if (!overlay || !spinner || spinnerDivs.length === 0) {
                window.location.href = nextUrl;
                return;
            }

            // Ensure overlay is shown
            overlay.classList.add('show');

            // Create timeline for smooth sequence
            const tl = gsap.timeline();

            // First fade out the loading text and stop spinner rotation
            tl.to(loadingText, {
                    opacity: 0,
                    duration: 0.3,
                    ease: "power2.out"
                })
                // Stop the spinner rotation by setting animation-play-state to paused
                .set('.spinner', {
                    css: {
                        animationPlayState: 'paused'
                    }
                })
                // Then animate spinner lines spreading out
                .to('.spinner > div', {
                    x: (i) => (i - 2.5) * 80,
                    y: (i) => Math.sin(i) * 20,
                    rotation: (i) => i * 30,
                    opacity: 0.3,
                    duration: 1.5,
                    stagger: 0.15,
                    ease: "power2.out"
                }, "-=0.1")
                // Animate overlay background color change
                .to('#loadingOverlay', {
                    backgroundColor: "#f5f3f0",
                    duration: 1.2,
                    ease: "power2.inOut",
                    onComplete: () => {
                        window.location.href = nextUrl;
                    }
                }, "-=1");
        }
    </script>
    <div id="loadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background-color:rgba(255,255,255,0.9); z-index:9999; align-items:center; justify-content:center; flex-direction:column;">
        <div class="spinner">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
        <p style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; font-size: 16px; color: #333; margin-top: 20px; font-weight: 500;">Loading...</p>
    </div>
</body>

</html>