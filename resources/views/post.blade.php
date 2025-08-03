<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media | Post</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
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

        .post-column {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .post {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
        }

        .post small {
            color: #666;
            display: block;
            margin-bottom: 0.5rem;
        }

        .post h2 {
            color: #333;
            font-size: 1.5rem;
            margin-top: 1rem;
            margin-bottom: 0.6rem;
        }

        .post p {
            color: #444;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .post p:last-child {
            margin-bottom: 0;
        }

        .post strong {
            color: #4a90e2;
        }

        .edit-indicator {
            color: #888;
            font-style: italic;
            margin-left: 0.5rem;
        }

        .back-button {
            color: #666;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            transition: color 0.2s;
        }

        .back-button:hover {
            color: #4a90e2;
        }

        .post-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .edit-button {
            color: #4a90e2;
            background: none;
            border: none;
            font-weight: 500;
            transition: color 0.2s;
            cursor: pointer;
            padding: 0;
            font-size: 1rem;
        }

        .edit-button:hover {
            color: #357abd;
        }

        .edit-button img {
            display: block;
            width: 20px;
            height: 20px;
            object-fit: contain;
        }

        .settings-button {
            color: #4a90e2;
            background: none;
            border: none;
            font-weight: 500;
            transition: color 0.2s;
            cursor: pointer;
            padding: 0;
            font-size: 1rem;
        }

        .settings-button:hover {
            color: #357abd;
        }

        .settings-button img {
            display: block;
            width: 16px;
            height: 16px;
            object-fit: contain;
        }

        .settings-container {
            position: relative;
            display: inline-block; /* Added to contain the dropdown */
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            top: calc(100% + 0.5rem); /* Added gap between button and dropdown */
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Increased opacity and spread */
            padding: 0.5rem;
            display: none;
            min-width: 120px;
            z-index: 1000; /* Added to ensure dropdown appears above other content */
        }

        .dropdown-item {
            width: 100%;
            padding: 0.5rem 1rem;
            background: none;
            border: none;
            text-align: left;
            font-size: 0.9rem;
            color: #666;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background-color: #f5f5f5;
            color: #4a90e2;
        }

        .show-dropdown {
            display: block;
        }

        .delete-form-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            width: 100%; /* Added to ensure full width */
        }

        .delete-cancel-btn,
        .delete-confirm-btn {
            flex: 1; /* Makes both buttons take up equal space */
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%; /* Ensures button fills its flex container */
        }

        .delete-cancel-btn {
            border: 2px solid #e1e1e1;
            background: white;
            color: #666;
        }

        .delete-cancel-btn:hover {
            border-color: #ccc;
            color: #333;
        }

        .delete-confirm-btn {
            border: none;
            background: #dc3545;
            color: white;
        }

        .delete-confirm-btn:hover {
            background: #c82333;
        }

        .edit-post-form {
            margin-top: 1rem;
        }

        .edit-post-form form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .edit-post-form input,
        .edit-post-form textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s ease;
        }

        .edit-post-form input:focus,
        .edit-post-form textarea:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.15);
        }

        .edit-post-form textarea {
            min-height: 120px;
            resize: vertical;
        }

        .edit-form-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            width: 100%;
        }

        .edit-cancel-btn,
        .edit-confirm-btn {
            flex: 1;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .edit-cancel-btn {
            border: 2px solid #e1e1e1;
            background: white;
            color: #666;
        }

        .edit-cancel-btn:hover {
            border-color: #ccc;
            color: #333;
        }

        .edit-confirm-btn {
            border: none;
            background: #4a90e2;
            color: white;
        }

        .edit-confirm-btn:hover {
            background: #357abd;
        }

        #vote-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        #vote-container form {
            margin: 0;
        }

        #vote-container button {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        #vote-container button:hover {
            transform: scale(1.1);
        }

        #vote-container img {
            width: 16px;
            height: 16px;
            display: block;
            object-fit: contain;
        }

        #vote-container p {
            margin: 0;
            min-width: 1.5rem;
            text-align: center;
            font-weight: 500;
            color: #666;
            font-size: 0.9rem;
        }

        .username-link {
            color: #4a90e2;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .username-link:hover {
            color: #357abd;
        }

        /* Update comment-column class */
        .comment-column {
            margin-top: 2rem;
            width: 95%;  /* Make comments column slightly narrower */
            margin-left: auto;  /* Center the narrower column */
            margin-right: auto;
        }

        /* Optional: Add a subtle visual separator */
        .post + .comment-column {
            padding-top: 0.5rem;  /* Reduced from 1rem */
            border-top: 1px solid #e1e1e1;
        }

        /* Add create comment form styles */
        .create-comment-form {
            margin-bottom: 1.5rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1.25rem;
        }

        .create-comment-form form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .create-comment-form textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 0.95rem;
            line-height: 1.5;
            min-height: 2.5rem;
            resize: vertical;
            transition: all 0.2s ease;
        }

        .create-comment-form textarea:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.15);
        }

        .comment {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1.25rem;
            margin-bottom: 1rem;
        }

        .comment:last-child {
            margin-bottom: 0;
        }

        .comment-top {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
        }

        .comment-content {
            color: #444;
            line-height: 1.5;
        }

        .comment-content p {
            margin: 0;
            font-size: 0.95rem;
        }

        .comment .edit-indicator {
            color: #888;
            font-style: italic;
            font-size: 0.85rem;
        }

        .comment .username-link {
            color: #4a90e2;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .comment .username-link:hover {
            color: #357abd;
        }
        
        .comment-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            position: relative;
        }

        .comment-metadata {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.25rem;
            font-size: 0.9rem;
            color: #666;
        }

        .comment-settings-container {
            position: relative;
            margin-left: 0.5rem;
        }

        .comment-settings-container .settings-button {
            background: none;
            border: none;
            padding: 0.25rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .comment-settings-container .settings-button img {
            width: 16px;
            height: 16px;
            display: block;
            object-fit: contain;
        }

        .comment-settings-container .dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 0.5rem;
            display: none;
            min-width: 120px;
            z-index: 1000;
            margin-top: 0.25rem;
        }

        .comment-settings-container .dropdown-menu.show-dropdown {
            display: block;
        }

        .comment-settings-container .dropdown-item {
            width: 100%;
            padding: 0.5rem 1rem;
            background: none;
            border: none;
            text-align: left;
            font-size: 0.85rem;
            color: #666;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .comment-settings-container .dropdown-item:hover {
            background-color: #f5f5f5;
            color: #4a90e2;
        }

        .username-link {
            color: #4a90e2;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .username-link:hover {
            color: #357abd;
        }

        .edit-indicator {
            color: #888;
            font-style: italic;
            font-size: 0.85rem;
        }
        
        /* Comment Edit Form - Full Width */
        .comment-edit-form {
            display: none;
            width: 100%;
            margin-top: 1rem;
        }

        .comment-edit-form form {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
            width: 100%;
        }

        .comment-edit-form textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 0.95rem;
            line-height: 1.5;
            min-height: 100px;
            resize: vertical;
            transition: border-color 0.2s ease;
        }

        .comment-edit-form textarea:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.15);
        }

        .comment-edit-form .edit-form-buttons {
            display: flex;
            flex-direction: row; /* Ensure buttons are in a row */
            gap: 1rem;
            margin-top: 1rem;
            width: 100%;
        }

        /* Comment Delete Form - Full Width with Side-by-Side Buttons */
        .comment-delete-form {
            display: none;
            flex-direction: column;
            width: 100%;
            margin-top: 0.2rem;
        }

        .comment-delete-form .delete-form-buttons {
            display: flex;
            flex-direction: row; /* Explicit row layout */
            margin-top: 1rem;
            width: 100%;
        }

        .comment-delete-form form {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .comment-delete-form .delete-buttons-container {
            display: flex;
            flex-direction: row;
            gap: 1rem;
            width: 100%;
        }

        /* Delete confirmation text styling */
        .comment-delete-form p {
            color: #444;
            margin-bottom: -1rem;
            font-size: 0.95rem;
            width: 100%;
        }

        /* Button styles (shared with post forms) */
        .edit-cancel-button,
        .delete-cancel-button {
            flex: 1;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid #e1e1e1;
            background: white;
            color: #666;
            width: 100%
        }

        .edit-confirm-button,
        .delete-confirm-button {
            flex: 1;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .edit-confirm-button {
            border: none;
            background: #4a90e2;
            color: white;
        }

        .delete-confirm-button {
            border: none;
            background: #dc3545;
            color: white;
        }

        .edit-cancel-button:hover,
        .delete-cancel-button:hover {
            border-color: #ccc;
            color: #333;
        }

        .edit-confirm-button:hover {
            background: #357abd;
        }

        .delete-confirm-button:hover {
            background: #c82333;
        }

        /* Comment Vote Container Styling */
        .comment-vote-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .comment-vote-container form {
            margin: 0;
        }

        .comment-vote-container button {
            background: none;
            border: none;
            padding: 0.25rem;
            cursor: pointer;
            transition: all 0.2s ease;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .comment-vote-container button:hover {
            transform: scale(1.1);
        }

        .comment-vote-container img {
            width: 16px;
            height: 16px;
            display: block;
            object-fit: contain;
            transition: opacity 0.2s ease;
        }

        .comment-vote-container p {
            margin: 0;
            min-width: 1.5rem;
            text-align: center;
            font-weight: 500;
            color: #666;
            font-size: 0.9rem;
            user-select: none;
        }

        .comment-vote-container button:active {
            transform: scale(0.95);
        }

        /* Reply Styling - Updated with white background and better spacing */
        .reply {
            background-color: white; /* Changed from #f8f9fa to white */
            border-radius: 6px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            padding: 1rem;
            margin-top: 0.75rem;
            margin-bottom: 1rem; /* Added bottom margin to prevent touching */
            margin-left: 2rem; /* Indent from left */
            margin-right: 0;
            max-width: 95%; /* Increased from 90% to make it wider */
            margin-left: auto; /* Push to the right */
            border-left: 3px solid #e1e1e1; /* Subtle left border to show it's a reply */
        }

        .reply:last-child {
            margin-bottom: 1rem; /* Ensure last reply also has bottom margin */
        }

        .reply-top {
            color: #666;
            font-size: 0.85rem; /* Slightly smaller than comment metadata */
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .reply-metadata {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.25rem;
            font-size: 0.85rem; /* Smaller than comment metadata */
            color: #666;
        }

        .reply-content {
            color: #444;
            line-height: 1.5;
        }

        .reply-content p {
            margin: 0;
            font-size: 0.9rem; /* Slightly smaller than comment content */
        }

        .reply .username-link {
            color: #4a90e2;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem; /* Smaller username link */
            transition: color 0.2s;
        }

        .reply .username-link:hover {
            color: #357abd;
        }

        .reply .edit-indicator {
            color: #888;
            font-style: italic;
            font-size: 0.8rem; /* Smaller edit indicator */
        }

        /* Comment hover effect - subtle and gentle */
        .comment {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .comment:hover {
            transform: translateY(-1px); /* Less movement than posts (-2px) */
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08); /* Softer shadow than posts */
        }

        /* Reply hover effect - even more subtle */
        .reply {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .reply:hover {
            transform: translateY(-0.5px); /* Very subtle movement */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06); /* Very gentle shadow */
        }
        
        /* Comment Bottom Section - Flex Layout */
        .comment-bottom {
            display: flex;
            align-items: center;
            gap: 1rem; /* Space between vote container and replies form */
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid #e1e1e1; /* Line separator */
        }

        /* Ensure vote container doesn't grow */
        .comment-vote-container {
            flex-shrink: 0;
        }   

        /* Style the replies form to match vote arrows */
        .comment-bottom form[action*="replies"] {
            margin: 0;
        }

        .comment-bottom form[action*="replies"] button {
            background: none;
            border: none;
            padding: 0.25rem;
            cursor: pointer;
            transition: all 0.2s ease;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem; /* Space between icon and text */
        }

        .comment-bottom form[action*="replies"] button:hover {
            transform: scale(1.1);
        }

        .comment-bottom form[action*="replies"] button:active {
            transform: scale(0.95);
        }
        
        /* Make chat icon same size as vote arrows */
        .comment-bottom form[action*="replies"] img {
            width: 18px;  /* Same as vote arrow icons */
            height: 18px; /* Same as vote arrow icons */
            margin-right: 2px;
            display: block;
            object-fit: contain;
            transition: opacity 0.2s ease;
        }

        .post-bottom {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid #e1e1e1; /* Move border here */
        }

        .commentCount {
            margin: 0 !important;
            color: #666;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 4px;
        }

        .create-reply-form {
            margin-bottom: 1.5rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1.25rem;
        }

        .create-reply-form textarea {
            width: 100%;
            height: 2.2rem;
            padding: 0.4rem 0.4rem;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 0.95rem;
            line-height: 1rem;
            min-height: 1rem;
            resize: vertical;
            transition: all 0.2s ease;
        }

        .create-reply-form textarea:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.15);
        }
        
        /* Reply Bottom Section Styling */
        .reply-bottom {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid #e1e1e1;
        }

        /* Reply Vote Container Styling */
        .reply-vote-container {
            display: flex;
            align-items: center;
            gap: 0.2rem;
            flex-shrink: 0;
        }

        .reply-vote-container form {
            margin: 0;
        }

        .reply-vote-container button {
            background: none;
            border: none;
            padding: 0.25rem;
            cursor: pointer;
            transition: all 0.2s ease;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .reply-vote-container button:hover {
            transform: scale(1.1);
        }

        .reply-vote-container button:active {
            transform: scale(0.95);
        }

        .reply-vote-container img {
            width: 16px;
            height: 16px;
            display: block;
            object-fit: contain;
            transition: opacity 0.2s ease;
        }

        .reply-vote-container p {
            margin: 0;
            min-width: 1.5rem;
            text-align: center;
            font-weight: 500;
            color: #666;
            font-size: 0.9rem;
            user-select: none;
        }

        /* Reply Edit Form */
        .reply-edit-form {
            display: flex;
            width: 100%;
            margin-top: 1rem;
        }

        .reply-edit-form form {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
            width: 100%;
        }

        .reply-edit-form textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 0.95rem;
            line-height: 1.5;
            min-height: 100px;
            resize: vertical;
            transition: border-color 0.2s ease;
        }

        .reply-edit-form textarea:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.15);
        }

        .reply-edit-form .edit-form-buttons {
            display: flex;
            flex-direction: row;
            gap: 1rem;
            margin-top: 1rem;
            width: 100%;
        }

        /* Reply Delete Form */
        .reply-delete-form {
            display: flex;
            flex-direction: column;
            width: 100%;
            margin-top: 0.2rem;
        }

        .reply-delete-form form {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .reply-delete-form p {
            color: #444;
            margin-bottom: 0.1rem;
            font-size: 0.9rem;
            width: 100%;
        }

        .reply-delete-form .delete-form-buttons {
            display: flex;
            flex-direction: row;
            margin-top: 1rem;
            width: 100%;
        }

        .reply-delete-form .delete-buttons-container {
            display: flex;
            flex-direction: row;
            gap: 1rem;
            width: 100%;
        }

        .reply-settings-container {
            position: relative;
            display: inline-block; /* or flex, as needed */
        }
        .dropdown-menu {
            position: absolute;
            top: 100%; /* below the button */
            right: 0;  /* align to the right edge */
            z-index: 100;
        }

        .share-button {
            font-size: 0.9rem;
            background-color: white;
            color: #333;
            margin-top: 40px;
            margin: 0;
            padding: 0;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .share-button:hover {
            transform:scale(1.1);
        }

        .share-button:active {
            transform: scale(1.0);
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/home" class="brand">Social Media</a>
        <div class="nav-links">
            <a href="/home" class="nav-link">Home</a>
            <a href="/groups" class="nav-link">Groups</a>
            <a href="/user/{{ Auth::id() }}" class="nav-link">Profile</a>
            <form action="/logout" method="POST" style="margin: 0">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </nav>
    @if(session()->has('success'))
        <div class="success-message">
            <div style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center;">
                {{ session('success') }}
            </div>
        </div>
    @endif
    <div class="post-column">
        <a href="/home" class="back-button">← Return</a>
        <div class="post" id='post-{{ $post->id }}'>
            <div class="post-top">
                <small>
                    <a href="/user/{{ $post->user_id }}" class="username-link">{{ '@' . $post->user->name }}</a>  |  {{ $post->created_at->format('F j, Y \a\t g:i a') }}.
                    @if($post->updated_at != $post->created_at)
                        <span class='edit-indicator'>Edited on {{ $post->updated_at->format('F j, Y \a\t g:i a') }}.</span>
                    @endif
                </small>
                @auth
                    @if (Auth::id() === $post->user_id)
                    <div class="settings-container">
                        <button class="settings-button" id='settings-button'>
                            <img src="{{ asset('storage/icons/dots.png') }}" alt='Settings' id='dots-icon'>
                        </button>
                        <div class="dropdown-menu" id='settings-dropdown-menu'>
                            <button class="dropdown-item" id='edit-post-button'>Edit</button>
                            <button class="dropdown-item" id='delete-post-button'>Delete</button>
                        </div>
                    </div>
                    @endif
                @endauth
            </div>
            <div id='post-content-container' class="post-content" style='display:block;'>
                <h2>{{ $post->title }}</h2>
                <p style='white-space: pre-wrap;'>{{ $post->content }}</p>
                <div class="post-bottom">
                    <div id="vote-container">
                        <form action="/post/upvote/{{ $post->id }}" method="POST">
                            @csrf
                            <button type="submit">
                                <img src="{{ asset('storage/icons/up-arrow' . ($post->userVote == 1 ? '-alt' : '') . '.png') }}" alt="upvote">
                            </button>
                        </form>
                        <p>{{ $post->votes }}</p>
                        <form action="/post/downvote/{{ $post->id }}" method="POST">
                            @csrf
                            <button type="submit">
                                <img src="{{ asset('storage/icons/down-arrow' . ($post->userVote == -1 ? '-alt' : '') . '.png') }}" alt="downvote">
                            </button>
                        </form>
                    </div>
                    @if($post->comments_count > 0)
                        <p class="commentCount">{{ $post->comments_count }} Comments</p>
                    @endif
                    <button type="button" class='share-button' id='post-share-button-{{ $post->id }}'>Share</button>
                </div>
            </div>
            <div id="edit-post-form" class="edit-post-form" style='display:none;'>
                <form action="/edit-post/{{ $post->id }}" method='POST'>
                    @csrf
                    <input 
                        type="text" 
                        name="edit-post-title" 
                        id="edit-post-title" 
                        value="{{ $post->title }}" 
                        placeholder="Post title..."
                        required
                    >
                    <textarea 
                        name="edit-post-content" 
                        id="edit-post-content" 
                        placeholder="Post content..." 
                        required
                    >{{ $post->content }}</textarea>
                    <div class="edit-form-buttons">
                        <button type="button" id="edit-post-cancel" class="edit-cancel-btn">Cancel</button>
                        <button type="submit" class="edit-confirm-btn">Save Changes</button>
                    </div>
                </form>
            </div>
            <div id="delete-post-form" style='display:none;'>
                <h2>{{ $post->title }}</h2>
                <p style='white-space:pre-wrap'>{{ $post->content }}</p>
                <form action="/delete-post/{{ $post->id }}" method='POST'>
                    @csrf
                    <div class="delete-form-buttons">
                        <button id='delete-post-cancel' class="delete-cancel-btn">Cancel</button>
                        <button type="submit" class="delete-confirm-btn">Delete Post</button>
                    </div>
                </form>
            </div>
        </div>

        <div id='comment-column' class='comment-column'>
            <div class="create-comment-form" id="create-comment-form">
                <form action="/post/{{ $post->id }}/create-comment" method="POST">
                    @csrf
                    <textarea name="create-comment-content" id="create-comment-content" placeholder="Share a comment..." required></textarea>
                </form>
            </div>
            @foreach($comments as $comment)
                <div class="comment" id='comment-{{ $comment->id }}'>
                    <div class='comment-top'>
                        <small class='comment-metadata'>
                            <a href="/user/{{ $comment->user_id }}" class="username-link">{{ '@' . $comment->user->name }}</a> | {{ $comment->created_at->format('F j, Y \a\t g:i a') }}.
                            @if($comment->updated_at != $comment->created_at)
                                <span class='edit-indicator'> Edited on {{ $comment->updated_at->format('F j, Y \a\t g:i a') }}</span>
                            @endif
                        </small>
                        @auth
                            @if(Auth::id() == $comment->user_id)
                                <div class="comment-settings-container">
                                    <button class="settings-button" id='settings-button-{{ $comment->id }}'>
                                        <img src="{{ asset('storage/icons/dots.png') }}" alt="Settings" id='dots-icon-{{ $comment->id }}'>
                                    </button>
                                    <div class="dropdown-menu" id='settings-dropdown-menu-{{ $comment->id }}'>
                                        <button class="dropdown-item" id='edit-comment-button-{{ $comment->id }}'>Edit</button>
                                        <button class="dropdown-item" id='delete-comment-button-{{ $comment->id }}'>Delete</button>
                                    </div>
                                </div>
                            @endif
                        @endauth
                    </div>
                    <div class="comment-content">
                        <p style='white-space: pre-wrap;'>{{ $comment->content }}</p>
                    </div>
                    <div class="comment-bottom">
                        <div class='comment-vote-container' id="vote-container-{{ $comment->id }}">
                            <form action="/comment/upvote/{{ $comment->id }}" method="POST">
                                @csrf
                                <button type="submit">
                                    <img src="{{ asset('storage/icons/up-arrow' . ($comment->userVote == 1 ? '-alt' : '') . '.png') }}" alt="Upvote">
                                </button>
                            </form>
                            <p>{{ $comment->votes }}</p>
                            <form action="/comment/downvote/{{ $comment->id }}" method="POST">
                                @csrf
                                <button type="submit">
                                    <img src="{{ asset('storage/icons/down-arrow' . ($comment->userVote == -1 ? '-alt' : '') . '.png') }}" alt="Downvote">
                                </button>
                            </form>
                        </div>
                        <form id='replies-form-{{ $comment->id }}' action="/comment/{{ $comment->id }}/replies" method='GET'>
                            @csrf
                            <button type="submit">
                                <img src="{{ asset('storage/icons/chat.png') }}" alt="">
                                @if($comment->replies_count > 0)
                                    Replies ({{$comment->replies_count}}) 
                                @else
                                    Replies
                                @endif
                            </button>
                        </form>
                        <button type="button" class='share-button' id='comment-share-button-{{ $comment->id }}'>Share</button>
                    </div>
                    <div class="comment-edit-form" style='display:none;'>
                        <form action="{{ $post->id }}/edit-comment/{{ $comment->id }}" method="POST">
                            @csrf
                            <textarea name="edit-comment-content-{{ $comment->id }}" 
                            id="edit-comment-content-{{ $comment->id }}"
                            placeholder="Comment content..."
                            required>{{ $comment->content }}</textarea>
                            <div class="edit-form-buttons">
                                <button type='button' class="edit-cancel-button">Cancel</button>
                                <button type="submit" class="edit-confirm-button">Save Changes</button>
                            </div>
                        </form>
                    </div>
                    <div class="comment-delete-form" style='display:none;'>
                        <p style='white-space:pre-wrap'>{{ $comment->content }}</p><br>
                        <div class="delete-form-buttons">
                            <form action="{{ $post->id }}/delete-comment/{{ $comment->id }}" method='POST' class='delete-buttons-container'>
                                @csrf
                                <button type='button' class="delete-cancel-button">Cancel</button>
                                <button type="submit" class="delete-confirm-button">Delete Comment</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="create-reply-form" id="create-reply-form-{{ $comment->id }}" style='display:none;'>
                    <form action="/post/{{ $post->id }}/comment/{{ $comment->id }}/create-reply" method='POST'>
                        @csrf
                        <textarea 
                            name="create-reply-content-{{ $comment->id }}" 
                            id="create-reply-content-{{ $comment->id }}"
                            placeholder="Write a reply..." 
                            required
                        ></textarea>
                    </form>
                </div>
                <div class="reply-container" id='reply-container-{{ $comment->id }}' style='display:none;'></div>
            @endforeach
        </div>
    </div>

    <div class="templates" style='display:none;'>
        <template id='reply-template'>
            <div class="reply">
                <div class="reply-top">
                    <small class="reply-metadata">
                        <a href="#" class="username-link">@</a>
                        <p></p>
                        <span class="edit-indicator" style='display:none;'></span>
                    </small>
                    <div class="reply-settings"></div>
                </div>
                <div class="reply-content">
                    <p style='white-space:pre-wrap;'>Content</p>
                </div>
                <div class='reply-bottom'>
                    <div class="reply-vote-container">
                        <form action="" method='POST'>
                            @csrf
                            <button type="submit">
                                <img src="" alt="Upvote">
                            </button>
                        </form>
                        <p class='reply-vote-count'>Vote count</p>
                        <form action="" method='POST'>
                            @csrf
                            <button type="submit">
                                <img src="" alt="Downvote">
                            </button>
                        </form>
                    </div>
                    <button type="button" class='share-button'>Share</button>
                </div>
                <div class="reply-edit-form" style='display:none;'>
                    <form action="" method='POST'>
                        @csrf
                        <textarea 
                            name="" 
                            id=""
                            placeholder='Reply content...'
                            required
                        ></textarea>
                        <div class="edit-form-buttons">
                            <button type='button' class="edit-cancel-button">Cancel</button>
                            <button type="submit" class="edit-confirm-button">Save Changes</button>
                        </div>
                    </form>
                </div>
                <div class="reply-delete-form" style='display:none;'>
                    <p style='white-space:pre-wrap'>Reply Content</p>
                    <div class="delete-form-buttons">
                        <form action="" method='POST' class='delete-buttons-container'>
                            @csrf
                            <button type="button" class='delete-cancel-button'>Cancel</button>
                            <button type="submit" class='delete-confirm-button'>Delete Reply</button>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </div>
</body>
<script>
window.currentUserId = {{ Auth::id() ?? 'null' }};

function formatDate(dateString){
    const date = new Date(dateString);
    return date.toLocaleString('en-us', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
}

// Post burger menu
document.addEventListener('DOMContentLoaded', () => {
    // Loading in replies
        const urlHash = window.location.hash;
        
        if(urlHash.startsWith('#reply-')){
            const replyId = urlHash.replace('#reply-', '');
            
            // Since replies aren't loaded yet, we need to load ALL replies and then find the target
            console.log(`Looking for reply ID: ${replyId}`);
            
            // Get all comments on the page
            const allComments = document.querySelectorAll('.comment');
            let targetCommentFound = false;
            
            // For each comment, try to load its replies
            allComments.forEach((comment, index) => {
                const commentId = comment.id.split('-')[1];
                const repliesForm = comment.querySelector(`#replies-form-${commentId}`);
                
                if(repliesForm){
                    // Add a delay for each comment to avoid overwhelming the server
                    setTimeout(() => {
                        console.log(`Loading replies for comment ${commentId}`);
                        repliesForm.dispatchEvent(new Event('submit'));
                    }, index * 200); // 200ms delay between each request
                }
            });
            
            // Check for the target reply multiple times with increasing delays
            const checkForTargetReply = (attempt = 1) => {
                console.log(`Checking for reply ${replyId}, attempt ${attempt}`);
                const targetReply = document.querySelector(`#reply-${replyId}`);
                
                if(targetReply){
                    console.log(`Found reply ${replyId}!`);
                    targetReply.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                    // Blue border highlight effect
                    targetReply.style.border = '2px solid #4a90e2';
                    targetReply.style.borderRadius = '8px';
                    setTimeout(() => {
                        targetReply.style.border = '';
                        targetReply.style.borderRadius = '6px'; // Reset to original border radius
                    }, 3000);
                    targetCommentFound = true;
                } else if (attempt < 5) {
                    // Try again with longer delay
                    setTimeout(() => checkForTargetReply(attempt + 1), 1000 * attempt);
                } else {
                    console.log(`Could not find reply ${replyId} after 5 attempts`);
                }
            };
            
            // Start checking after initial delay
            setTimeout(() => checkForTargetReply(), 2000);
        }
    
        // Post share buttons
        const postShareButton = document.querySelector(`#post-share-button-{{ $post->id }}`);
        postShareButton.addEventListener('click', (e) => {
            postUrl = `${window.location.origin}/post/{{ $post->id }}`;
            navigator.clipboard.writeText(postUrl);
            postShareButton.textContent = 'Copied!';
            setTimeout(() => {
                postShareButton.textContent = 'Share';
            }, 1200);
        });
    // Post Settings button functions
        const settingsButton = document.getElementById('settings-button');
        const dotsIcon = document.getElementById('dots-icon');
        const originalSrc = dotsIcon.src;
        const hoverSrc = originalSrc.replace('dots.png', 'dots-alt.png');
        const settingsDropdown = document.getElementById('settings-dropdown-menu');
        
        const editPostButton = document.getElementById('edit-post-button'); // Dropdown item
        const editPostCancel = document.getElementById('edit-post-cancel'); // edit form cancel button
        const deletePostButton = document.getElementById('delete-post-button'); // Dropdown item
        const deletePostCancel = document.getElementById('delete-post-cancel'); // delete form cancel button
        
        const postContentContainer = document.getElementById('post-content-container');
        const editPostForm = document.getElementById('edit-post-form');
        const deletePostForm = document.getElementById('delete-post-form');

        settingsButton.addEventListener('mouseenter', () => dotsIcon.src = hoverSrc);
        settingsButton.addEventListener('mouseleave', () => dotsIcon.src = originalSrc);
        settingsButton.addEventListener('click', (e) => {
            e.stopPropagation();
            settingsDropdown.classList.toggle('show-dropdown');
        });
        document.addEventListener('click', () => {
            if(settingsDropdown.classList.contains('show-dropdown')){
                settingsDropdown.classList.remove('show-dropdown');
            }
        });

    // Edit post
        editPostButton.addEventListener('click', (e) => {
            e.stopPropagation();
            postContentContainer.style.display = 'none';
            editPostForm.style.display = 'block';
            deletePostForm.style.display = 'none';
            if(settingsDropdown.classList.contains('show-dropdown')){
                settingsDropdown.classList.remove('show-dropdown');
            }
        });
        editPostCancel.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            postContentContainer.style.display = 'block';
            editPostForm.style.display = 'none';
            deletePostForm.style.display = 'none';
        });

    // Delete post
        deletePostButton.addEventListener('click', (e) => {
            e.stopPropagation();
            postContentContainer.style.display = 'none';
            editPostForm.style.display = 'none';
            deletePostForm.style.display = 'block';
            if(settingsDropdown.classList.contains('show-dropdown')){
                settingsDropdown.classList.remove('show-dropdown');
            }
        });
        deletePostCancel.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            postContentContainer.style.display = 'block';
            editPostForm.style.display = 'none';
            deletePostForm.style.display = 'none';
        });
    
});
 
document.addEventListener('DOMContentLoaded', () => {
// Comment Settings button functions
        const comments = document.getElementsByClassName('comment');
        Array.from(comments).forEach(comment => {
            const commentSettingsButton = comment.querySelector('.settings-button');
            if(commentSettingsButton){
                const commentId = commentSettingsButton.id.split('-')[2];
                const commentDotsIcon = commentSettingsButton.querySelector('img');
                const originalDotsIconSrc = commentDotsIcon.src;
                const hoverDotsIconSrc = originalDotsIconSrc.replace('dots.png', 'dots-alt.png');
                const commentDropdown = comment.querySelector('.dropdown-menu');

                // Settings icon
                commentSettingsButton.addEventListener('mouseenter', () => {
                    commentDotsIcon.src = hoverDotsIconSrc;
                });
                commentSettingsButton.addEventListener('mouseleave', () => {
                    commentDotsIcon.src = originalDotsIconSrc;
                });
                
            // Settings dropdown
                commentSettingsButton.addEventListener('click', (e) => {
                    e.stopPropagation();
                    commentDropdown.classList.toggle('show-dropdown');
                })
                document.addEventListener('click', () => {
                    if(commentDropdown.classList.contains('show-dropdown')){
                        commentDropdown.classList.remove('show-dropdown');
                    }
                })

            // Settings edit
                const commentContent = comment.querySelector('.comment-content');
                const commentBottom = comment.querySelector('.comment-bottom');
                const commentEditFormContainer = comment.querySelector('.comment-edit-form');
                const commentDeleteFormContainer = comment.querySelector('.comment-delete-form');

                const editButton = commentDropdown.querySelector(`#edit-comment-button-${commentId}`);
                editButton.addEventListener('click', () => {
                    commentContent.style.display = 'none';
                    commentBottom.style.display = 'none';
                    commentEditFormContainer.style.display = 'flex';
                    commentDeleteFormContainer.style.display = 'none';
                });
                const commentEditForm = commentEditFormContainer.querySelector('form');
                const editCancelButton = commentEditForm.querySelector('.edit-cancel-button');
                editCancelButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    commentContent.style.display = 'block';
                    commentBottom.style.display = 'flex';
                    commentEditFormContainer.style.display = 'none';
                    commentDeleteFormContainer.style.display = 'none';
                })

            // Settings delete
                const deleteButton = commentDropdown.querySelector(`#delete-comment-button-${commentId}`);
                deleteButton.addEventListener('click', () => {
                    commentContent.style.display = 'none';
                    commentBottom.style.display = 'none';
                    commentEditFormContainer.style.display = 'none';
                    commentDeleteFormContainer.style.display = 'flex';
                });
                const commentDeleteForm = commentDeleteFormContainer.querySelector('form');
                const deleteCancelButton = commentDeleteForm.querySelector('.delete-cancel-button');
                deleteCancelButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    commentContent.style.display = 'block';
                    commentBottom.style.display = 'flex';
                    commentEditFormContainer.style.display = 'none';
                    commentDeleteFormContainer.style.display = 'none';
                });
            }
        });
// Create Comment Form
    const createCommentContainer = document.getElementById("create-comment-form");
    const createCommentForm = createCommentContainer.querySelector('form');
    const createCommentInput = createCommentContainer.querySelector('textarea');

    createCommentInput.addEventListener('keydown', (e) => {
        if(e.key === 'Enter' && !e.shiftKey){
            e.preventDefault();
            createCommentForm.submit();
        }
    })
// Post Voting
    const post = document.querySelector('#post-{{ $post->id }}');
    const postVoteContainer = post.querySelector("#vote-container");
    const postUpvoteForm = postVoteContainer.querySelector("form:first-child");
    const postDownvoteForm = postVoteContainer.querySelector("form:last-child");
    const upArrow = postUpvoteForm.querySelector('img');
    const downArrow = postDownvoteForm.querySelector('img');
    const postVoteCount = postVoteContainer.querySelector('p')


    postVoteContainer.addEventListener('click', (e) => {
        e.stopPropagation();
    });
    
    // UPVOTE
        postUpvoteForm.addEventListener('submit', async(e) => {
            e.preventDefault();
            e.stopPropagation();

            try{
                const response = await fetch(postUpvoteForm.action, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json",
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    credentials: "same-origin",
                    body: new URLSearchParams({
                        _token: document.querySelector('meta[name="csrf-token"]').content
                    })
                });

                if(response.ok){
                    const data = await response.json();

                    postVoteCount.textContent = data.voteCount;

                    upArrow.src = (data.voteValue == 1) ?
                        "{{ asset('storage/icons/up-arrow-alt.png') }}" :
                        "{{ asset('storage/icons/up-arrow.png') }}" ;

                    if(data.voteValue == 1){
                        downArrow.src = "{{ asset('storage/icons/down-arrow.png') }}";
                    }
                }
            } catch (error){
                console.error('Error: ', error);
            }

        })
    // DOWNVOTE
        postDownvoteForm.addEventListener('submit', async(e) => {
            e.preventDefault();
            e.stopPropagation();

            try {
                const response = await fetch(postDownvoteForm.action, {
                    method: "POST",
                    headers:{
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json",
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    credentials: "same-origin",
                    body: new URLSearchParams({
                        _token: document.querySelector('meta[name="csrf-token"]').content,
                    })
                });

                if(response.ok){
                    const data = await response.json();

                    postVoteCount.textContent = data.voteCount;

                    downArrow.src = (data.voteValue == -1) ?
                        "{{ asset('storage/icons/down-arrow-alt.png') }}" :
                        "{{ asset('storage/icons/down-arrow.png') }}" ;

                    if(data.voteValue == -1){
                        upArrow.src = "{{ asset('storage/icons/up-arrow.png') }}";
                    }
                }
            } catch (error){
                console.error("Error: ", error);
            }
        });

// Comment Voting
    // const comments = document.getElementsByClassName('comment');
    Array.from(comments).forEach(comment => {
        const commentId = comment.id.split('-')[1];
        const commentVoteContainer = comment.querySelector(`#vote-container-${commentId}`);
        const commentUpvoteForm = commentVoteContainer.querySelector('form:first-child');
        const commentDownvoteForm = commentVoteContainer.querySelector('form:last-child');
        const upArrow = commentUpvoteForm.querySelector('img');
        const downArrow = commentDownvoteForm.querySelector('img');
        const commentVoteCount = commentVoteContainer.querySelector('p');

        commentVoteContainer.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        // UPVOTE
        commentUpvoteForm.addEventListener('submit', async(e) => {
            e.stopPropagation();
            e.preventDefault();

            try{
                const response = await fetch(commentUpvoteForm.action, {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    credentials: 'same-origin',
                    body: new URLSearchParams({
                        _token: document.querySelector('meta[name="csrf-token"]').content,
                    }),
                });

                if(response.ok){
                    const data = await response.json();

                    commentVoteCount.textContent = data.voteCount;

                    upArrow.src = (data.voteValue == 1) ?
                        "{{ asset('storage/icons/up-arrow-alt.png') }}" :
                        "{{ asset('storage/icons/up-arrow.png') }}" ;

                    if(data.voteValue == 1){
                        downArrow.src = "{{ asset('storage/icons/down-arrow.png') }}";
                    }
                }
            } catch (error){
                console.error("Error: ", error);
            }
        });
        // DOWNVOTE
        commentDownvoteForm.addEventListener('submit', async(e) => {
            e.preventDefault();
            e.stopPropagation();

            try {
                const response = await fetch(commentDownvoteForm.action, {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    credentials: 'same-origin',
                    body: new URLSearchParams({
                        _token: document.querySelector('meta[name="csrf-token"]').content,
                    }),
                });

                if(response.ok){
                    const data = await response.json();

                    commentVoteCount.textContent = data.voteCount;

                    downArrow.src = (data.voteValue == -1) ?
                        "{{ asset('storage/icons/down-arrow-alt.png') }}" :
                        "{{ asset('storage/icons/down-arrow.png') }}" ;

                    if(data.voteValue == -1){
                        upArrow.src = "{{ asset('storage/icons/up-arrow.png') }}";
                    }
                }
            } catch (error){
                console.error("Error: ", error);
            }
        })

        const commentShareButton = comment.querySelector('.share-button');
        commentShareButton.addEventListener('click', () => {
            const commentUrl = `${window.location.origin}/post/{{ $post->id }}#comment-${commentId}`;
            navigator.clipboard.writeText(commentUrl);
            console.log(commentShareButton);
            commentShareButton.textContent = 'Copied';
            setTimeout(() => {
                commentShareButton.textContent = 'Share';
            }, 1200);
        });
    
// Reply Button
        const repliesForm = comment.querySelector(`#replies-form-${commentId}`);
        const createReplyContainer = document.querySelector(`#create-reply-form-${commentId}`);
        const createReplyForm = createReplyContainer.querySelector('form');
        const replyTemplate = document.querySelector('#reply-template');
        const replyContainer = document.querySelector(`#reply-container-${commentId}`);
        // Replies Button
        repliesForm.addEventListener('submit', async(e) => {
            e.preventDefault();
            e.stopPropagation();

            if(replyContainer.getAttribute('data-expanded') === 'true'){
                replyContainer.style.display = 'none';
                createReplyContainer.style.display = 'none';
                replyContainer.setAttribute('data-expanded', 'false');
            } else {
                try {
                    if(!replyContainer.hasChildNodes()){
                        const response = await fetch(repliesForm.action, {
                            method: "GET",
                            headers: {
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin'
                        });
                        
                        if(response.ok){
                            const data = await response.json();
                            Array.from(data.replies).forEach(reply => {
                                const clone = replyTemplate.content.cloneNode(true);
                                
                            // clean the data
                                const createdAt = formatDate(reply.created_at);
                                const updatedAt = formatDate(reply.updated_at);

                            // apply the data
                                // reply top
                                clone.querySelector('.reply').id = `reply-${reply.id}`;
                                clone.querySelector('.username-link').href = `/user/${reply.user_id}`;
                                clone.querySelector('.username-link').textContent = `@${reply.user.name}`;
                                clone.querySelector('p').textContent = ` | ${createdAt}`;
                                const editIndicator = clone.querySelector('.edit-indicator');
                                if(reply.created_at != reply.updated_at){
                                    editIndicator.textContent = `Edited on ${updatedAt}`;
                                    editIndicator.style = 'inline';
                                }
                                if(reply.user_id == window.currentUserId){
                                    const settingsContainer = clone.querySelector('.reply-settings');
                                    settingsContainer.innerHTML = 
                                    `<div class='reply-settings-container'>
                                        <button class='settings-button' id='reply-settings-button-${reply.id}'>
                                            <img src='{{ asset('storage/icons/dots.png') }}' alt='Settings' id='reply-dots-icon-${reply.id}'>
                                        </button>
                                        <div class='dropdown-menu' id='reply-settings-dropdown-menu-${reply.id}'>
                                            <button class='dropdown-item' id='edit-reply-button-${reply.id}'>Edit</button>
                                            <button class='dropdown-item' id='delete-reply-button-${reply.id}'>Delete</button>
                                        </div>
                                    </div>`;
                                }
                                clone.querySelector('.reply-content p').textContent = reply.content;
                                // reply bottom
                                const cloneVote = clone.querySelector('.reply-bottom .reply-vote-container');
                                const replyVoteCount = cloneVote.querySelector('p');
                                const replyUpvoteForm = cloneVote.querySelector('form:first-child');
                                const replyDownvoteForm = cloneVote.querySelector('form:last-child');
                                const replyUpArrow = cloneVote.querySelector('form:first-child img');
                                const replyDownArrow = cloneVote.querySelector('form:last-child img');
                                replyUpvoteForm.action = `/reply/upvote/${reply.id}`;
                                replyUpArrow.src = (reply.userVote == 1) ?
                                "{{ asset('storage/icons/up-arrow-alt.png') }}" :
                                "{{ asset('storage/icons/up-arrow.png') }}" ;
                                replyVoteCount.textContent = reply.votes;
                                replyDownvoteForm.action = `/reply/downvote/${reply.id}`;
                                replyDownArrow.src = (reply.userVote == -1) ?
                                "{{ asset('storage/icons/down-arrow-alt.png') }}" :
                                "{{ asset('storage/icons/down-arrow.png') }}" ;
                                const replyShareButton = clone.querySelector('.share-button');
                                replyShareButton.id = `reply-share-button-${reply.id}`;



                                // reply edit form
                                const replyEditForm = clone.querySelector('.reply-edit-form form');
                                const replyEditInput = replyEditForm.querySelector('textarea');
                                replyEditForm.action = `/post/{{ $post->id }}/edit-reply/${reply.id}`;
                                replyEditInput.value = reply.content;
                                replyEditInput.name = `edit-reply-content-${reply.id}`;
                                replyEditInput.id = `edit-reply-content-${reply.id}`;
                                // reply delete form
                                const replyDeleteText = clone.querySelector('.reply-delete-form p');
                                const replyDeleteForm = clone.querySelector('.reply-delete-form form');
                                replyDeleteText.textContent = reply.content;
                                replyDeleteForm.action = `/post/{{ $post->id }}/delete-reply/${reply.id}`;                    
// Reply Voting
                            // UPVOTING
                                replyUpvoteForm.addEventListener('submit', async(e) => {
                                    e.stopPropagation();
                                    e.preventDefault();

                                    try{
                                        const response = await fetch(replyUpvoteForm.action, {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                                'Accept': 'application/json',
                                                'Content-Type': 'application/x-www-form-urlencoded',
                                            },
                                            credentials: 'same-origin',
                                            body: new URLSearchParams({
                                                _token: document.querySelector('meta[name="csrf-token"]').content
                                            })
                                        });

                                        if(response.ok){
                                            const data = await response.json();

                                            replyVoteCount.textContent = data.voteCount;

                                            replyUpArrow.src = (data.voteValue == 1) ?
                                                "{{ asset('storage/icons/up-arrow-alt.png') }}" :
                                                "{{ asset('storage/icons/up-arrow.png') }}" ;
                                            
                                            if(data.voteValue == 1){
                                                replyDownArrow.src = "{{ asset('storage/icons/down-arrow.png') }}";
                                            }
                                        }
                                    } catch(error){
                                        console.error("Error: ", error);
                                    }
                                }); 
                                
                            // DOWNVOTING
                                replyDownvoteForm.addEventListener('submit', async(e) => {
                                    e.stopPropagation();
                                    e.preventDefault();

                                    try{
                                        const response = await fetch(replyDownvoteForm.action, {
                                            method: "POST",
                                            headers: {
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                                'Accept': 'application/json',
                                                'Content-Type': 'application/x-www-form-urlencoded'
                                            },
                                            credentials: 'same-origin',
                                            body: new URLSearchParams({
                                                _token: document.querySelector('meta[name="csrf-token"]').content
                                            })
                                        });

                                        if(response.ok){
                                            const data = await response.json();

                                            replyVoteCount.textContent = data.voteCount;

                                            replyDownArrow.src = (data.voteValue == -1) ?
                                                "{{ asset('storage/icons/down-arrow-alt.png') }}" :
                                                "{{ asset('storage/icons/down-arrow.png') }}" ;

                                            if(data.voteValue == -1){
                                                replyUpArrow.src = "{{ asset('storage/icons/up-arrow.png') }}";
                                            }
                                        }
                                    } catch(error){
                                        console.error("Error: ", error);
                                    }
                                });
                            // Reply Share Button
                                replyShareButton.addEventListener('click', () => {
                                    replyUrl = `${window.location.origin}/post/{{ $post->id }}#reply-${reply.id}`;
                                    navigator.clipboard.writeText(replyUrl);
                                    replyShareButton.textContent = 'Copied!';
                                    setTimeout(() => {
                                        replyShareButton.textContent = 'Share';
                                    }, 1200);
                                });
                            // Appending
                                replyContainer.appendChild(clone);
// Reply Settings
                                if(reply.user_id == window.currentUserId){
                                    const clonedReply = replyContainer.querySelector('.reply:last-child');
                                    const clonedSettings = clonedReply.querySelector('.reply-settings-container');
                                    const clonedContent = clonedReply.querySelector('.reply-content');
                                    const clonedBottom = clonedReply.querySelector('.reply-bottom');
                                    const clonedEditContainer = clonedReply.querySelector('.reply-edit-form');
                                    const clonedDeleteContainer = clonedReply.querySelector('.reply-delete-form')
    // Reply Settings Dropdown
                                // Settings Button
                                    const clonedSettingsButton = clonedSettings.querySelector('.settings-button');
                                    const clonedSettingsButtonImg = clonedSettings.querySelector('.settings-button img');
                                    const clonedSettingsDropdown = clonedSettings.querySelector('.dropdown-menu');
                                    clonedSettingsButton.addEventListener('mouseenter', () => {
                                        clonedSettingsButtonImg.src = '{{ asset("storage/icons/dots-alt.png") }}';
                                    });
                                    clonedSettingsButton.addEventListener('mouseleave', () => {
                                        clonedSettingsButtonImg.src = '{{ asset("storage/icons/dots.png") }}';
                                    });
                                    clonedSettingsButton.addEventListener('click', (e) => {
                                        e.stopPropagation();
                                        clonedSettingsDropdown.classList.toggle('show-dropdown');
                                    });
                                    document.addEventListener('click', (e) => {
                                        if(clonedSettingsDropdown.classList.contains('show-dropdown')){
                                            clonedSettingsDropdown.classList.remove('show-dropdown');
                                        }
                                    })
                                // Edit Button
                                    const clonedSettingsEdit = clonedSettingsDropdown.querySelector(`#edit-reply-button-${reply.id}`);
                                    clonedSettingsEdit.addEventListener('click', () => {
                                        clonedContent.style.display = 'none';
                                        clonedBottom.style.display = 'none';
                                        clonedEditContainer.style.display = 'flex';
                                        clonedDeleteContainer.style.display = 'none';
                                    });
                                    const clonedEditCancel = clonedEditContainer.querySelector('.edit-cancel-button');
                                    clonedEditCancel.addEventListener('click', (e) => {
                                        e.preventDefault();
                                        e.stopPropagation();

                                        clonedContent.style.display = 'flex';
                                        clonedBottom.style.display = 'flex';
                                        clonedEditContainer.style.display = 'none';
                                        clonedDeleteContainer.style.display = 'none';
                                    })
                                // Delete Button
                                    const clonedSettingsDelete = clonedSettingsDropdown.querySelector(`#delete-reply-button-${reply.id}`);
                                    clonedSettingsDelete.addEventListener('click', () => {
                                        clonedContent.style.display = 'none';
                                        clonedBottom.style.display = 'none';
                                        clonedEditContainer.style.display = 'none';
                                        clonedDeleteContainer.style.display = 'flex';
                                    });
                                    const clonedDeleteCancel = clonedDeleteContainer.querySelector('.delete-cancel-button');
                                    clonedDeleteCancel.addEventListener('click', (e) => {
                                        e.preventDefault();
                                        e.stopPropagation();

                                        clonedContent.style.display = 'flex';
                                        clonedBottom.style.display = 'flex';
                                        clonedEditContainer.style.display = 'none';
                                        clonedDeleteContainer.style.display = 'none';
                                    })
                                }
                            });
                        }
                    }
                } catch (error){
                    console.error('Error: ', error);
                }
                replyContainer.style.display = 'block';
                createReplyContainer.style.display = 'block';
                replyContainer.setAttribute('data-expanded', 'true');
            }
        });
// Create Reply Form
        createReplyForm.addEventListener('keydown', (e) => {
            if(e.key === 'Enter' && !e.shiftKey){
                e.preventDefault();
                createReplyForm.submit();
            }
        })
    })
    
})
</script>
</html>