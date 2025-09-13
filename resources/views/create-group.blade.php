<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media | Create Group</title>
    <meta name='csrf-token' content='{{ csrf_token() }}'>
    <style>
        /* Main */
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

            main {
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: flex-start;
                padding: 2rem;
                gap: 2rem;
            } 

            /* SUCCESS HEADER */
                .success-message {
                    background-color: #d4edda;
                    color: #155724;
                    margin-top: -0.5rem;
                    text-align: center;
                }

            /* ERROR HEADER */
                .error-message {
                    background-color: #f8d7da; 
                    color: #721c24; 
                    margin-top: -1rem;
                    text-align: center;
                }
                
                .error-message ul {
                    margin: 0; 
                    padding-left: 1rem;
                }
                
                .error-message p {
                    margin: 0;
                }
        /* Left */
            .left-side {
                flex: 1 1 800px;
                max-width: 800px;
            }

        /* Form */
            .create-group-form {
                width: 100%;
                max-width: none;
                padding: 2rem;
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
            }
            /* Fields */
                .form-field {
                    display: flex;
                    flex-direction: column;
                    gap: 0.5rem;
                    margin-bottom: 1.5rem;
                }

                .form-field label {
                    font-weight: 500;
                    color: #333;
                    font-size: 1rem;
                    margin-bottom: 0.25rem;
                }

                .form-field input,
                .form-field textarea,
                .form-field select {
                    padding: 0.75rem;
                    border: 1px solid #ddd;
                    border-radius: 6px;
                    font-size: 0.9rem;
                    transition: border-color 0.2s, box-shadow 0.2s;
                    background-color: white;
                }

                .form-field input:focus,
                .form-field textarea:focus,
                .form-field select:focus {
                    outline: none;
                    border-color: #4a90e2;
                    box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
                }

                .form-field input[type="file"] {
                    padding: 0.5rem;
                    cursor: pointer;
                }

                .form-field textarea {
                    min-height: 100px;
                    resize: vertical;
                    font-family: inherit;
                }

                .form-field label.required::after {
                    content: " *";
                    color: #dc3545;
                }

                .file-input-wrapper {
                    position: relative;
                    display: inline-block;
                    width: 100%;
                }

                .file-input-wrapper input[type="file"] {
                    position: absolute;
                    opacity: 0;
                    width: 100%;
                    height: 100%;
                    cursor: pointer;
                }

                .file-input-label {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.5rem;
                    padding: 0.75rem;
                    border: 2px dashed #ddd;
                    border-radius: 6px;
                    background-color: #f8f9fa;
                    color: #666;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    font-size: 0.9rem;
                    min-height: 50px;
                    width: 100%;             
                    max-width: 100%;         
                    word-break: break-all;   
                    white-space: normal;     
                    text-align: center;      
                    flex-direction: column;  
                }

                .file-input-label:hover {
                    border-color: #4a90e2;
                    background-color: #f0f7ff;
                    color: #4a90e2;
                }

                .file-input-label.has-file {
                    border-color: #28a745;
                    background-color: #f0fff4;
                    color: #28a745;
                    border-style: solid;
                }

                .file-input-icon {
                    font-size: 1.2rem;
                }

                .file-name {
                    font-weight: 500;
                    word-break: break-all;
                    white-space: normal;
                    overflow-wrap: break-word;
                    width: 100%;
                    /* max-width: 200px; */
                    text-align: center;
                    /* overflow: hidden; */
                    /* text-overflow: ellipsis; */
                }

                .file-size {
                    font-size: 0.8rem;
                    opacity: 0.7;
                }

                /* Row 3 specific styling */
                .row-3 {
                    display: flex;
                    flex-direction: row;
                    gap: 1rem;
                }

                .row-3 .form-field {
                    flex: 1;
                    margin-bottom: 0;
                }
                
                .row-6 .form-field p {
                    margin-bottom: 0.3rem;
                }

                .rule-item p {
                    font-weight: 500;
                    color: #333;
                    font-size: 1rem;
                }

                .rule-item .rule-title,
                .rule-item .rule-description {
                    padding: 0.75rem;
                    border: 1px solid #ddd;
                    border-radius: 6px;
                    font-size: 0.9rem;
                    transition: border-color 0.2s, box-shadow 0.2s;
                    background-color: white;
                    width: 100%;
                }

                .rule-item .rule-title:focus,
                .rule-item .rule-description:focus {
                    outline: none;
                    border-color: #4a90e2;
                    box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
                }

                .rule-.item .rule-description {
                    min-height: 80px;
                    resize: vertical;
                    font-family: inherit;
                }

                .remove-rule-button {
                    background-color: #dc3545;
                    color: white;
                    border: none;
                    padding: 0.4rem 0.8rem;
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 0.8rem;
                    height: fit-content;
                    align-self: flex-start;
                    white-space: nowrap;
                    width: 100%;
                }

                .remove-rule-button:hover {
                    background-color: #c82333;
                }

                .add-rule-button {
                    background-color: #28a745;
                    color: white;
                    border: none;
                    padding: 0.6rem 1.2rem;
                    margin-top: 0.5rem;
                    border-radius: 6px;
                    cursor: pointer;
                    font-weight: 500;
                    font-size: 0.9rem;
                }

                .add-rule-button:hover {
                    background-color: #218838;
                }

                .row-7 .form-field p {
                    margin-bottom: 0.3rem;
                }

                .resource-item p {
                    font-weight: 500;
                    color: #333;
                    font-size: 1rem;
                }

                .resource-item .resource-title,
                .resource-item .resource-description {
                    padding: 0.75rem;
                    border: 1px solid #ddd;
                    border-radius: 6px;
                    font-size: 0.9rem;
                    transition: border-color 0.2s, box-shadow 0.2s;
                    background-color: white;
                    width: 100%;
                }

                .resource-item .resource-title:focus,
                .resource-item .resource-description:focus {
                    outline: none;
                    border-color: #4a90e2;
                    box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
                }

                .resource-item .resource-description {
                    min-height: 80px;
                    resize: vertical;
                    font-family: inherit;
                }

                .remove-resource-button {
                    background-color: #dc3545;
                    color: white;
                    border: none;
                    padding: 0.4rem 0.8rem;
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 0.8rem;
                    height: fit-content;
                    align-self: flex-start;
                    white-space: nowrap;
                    width: 100%;
                }

                .remove-resource-button:hover {
                    background-color: #c82333;
                }

                .add-resource-button {
                    background-color: #28a745;
                    color: white;
                    border: none;
                    padding: 0.6rem 1.2rem;
                    margin-top: 0.5rem;
                    border-radius: 6px;
                    cursor: pointer;
                    font-weight: 500;
                    font-size: 0.9rem;
                }

                .add-resource-button:hover {
                    background-color: #218838;
                }

                .switch {
                    position: relative;
                    display: inline-block;
                    width: 44px;
                    height: 24px;
                }

                .switch input {
                    opacity: 0;
                    width: 0;
                    height: 0;
                }

                .slider {
                    position: absolute;
                    cursor: pointer;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-color: #ccc;
                    transition: .4s;
                    border-radius: 24px;
                }

                .slider:before {
                    position: absolute;
                    content: "";
                    height: 18px;
                    width: 18px;
                    left: 3px;
                    bottom: 3px;
                    background-color: white;
                    transition: .4s;
                    border-radius: 50%;
                }

                input:checked + .slider {
                    background-color: #4a90e2;
                }

                input:focus + .slider {
                    box-shadow: 0 0 1px #4a90e2;
                }

                input:checked + .slider:before {
                    transform: translateX(20px);
                }

                .switch-label {
                    font-size: 0.9rem;
                    color: #333;
                    cursor: pointer;
                }

                .row-8 button[type="submit"] {
                    background-color: #4a90e2;
                    color: white;
                    border: none;
                    padding: 0.6rem 1.2rem;
                    border-radius: 6px;
                    cursor: pointer;
                    font-weight: 500;
                    font-size: 0.9rem;
                    width: 100%;
                }
                
                .row-8 button[type="submit"]:hover {
                    background-color: #357abd;
                }

            /* Layout */
                .form-row {
                    display: flex;
                    flex-direction: row;
                    gap: 1rem;
                    align-items: flex-end;
                }
                
                .row-1 .form-field {
                    width: 100%;
                }

                .row-2 {
                    display: flex;
                    flex-direction: column;
                    gap: 0.5rem;
                }
                
                .row-2 .form-field {
                    width: 100%;
                    margin-bottom: 0;
                }
                
                .row-2 .form-field textarea {
                    min-height: 120px;
                    resize: vertical;
                    font-family: inherit;
                    line-height: 1.5;
                }

                .row-3.form-row {
                    margin-top: 1.5rem;
                }

                .row-4 {
                    margin-top: 1.5rem;
                    margin-bottom: 0;
                }

                .row-4 .form-field {
                    width: 100%;
                }

                .row-5 {
                    margin-top: -1rem;
                }
                
                .row-6,
                .row-7 {
                    display: flex;
                    flex-direction: column;
                    width: 100%;
                    background-color: #f8f9fa;
                    border-radius: 8px;
                    border: 1px solid #ddd;
                    padding: 1.5rem;
                    margin-bottom: 1rem;
                }

                .row-6 .form-field,
                .row-7 .form-field {
                    width: 100%;
                    margin-bottom: 0;
                }

                .rules-container,
                .resources-container {
                    display: flex;
                    flex-direction: column;
                    gap: 1rem;
                }

                .rule-item,
                .resource-item {
                    border: 1px solid #ddd;
                    border-radius: 6px;
                    padding: 1rem;
                    background-color: white;
                    display: flex;
                    flex-direction: column;
                    gap: 0.5rem;
                    align-items: flex-start;
                    width: 100%;
                    transition: transform 0.2s ease, box-shadow 0.2s ease;
                }

                .rule-item:hover,
                .resource-item:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
                }

                .add-rule-button,
                .add-resource-button {
                    background-color: #28a745;
                    color: white;
                    border: none;
                    padding: 0.6rem 1.2rem;
                    margin-top: 0.5rem;
                    border-radius: 6px;
                    cursor: pointer;
                    font-weight: 500;
                    font-size: 0.9rem;
                }

                .add-rule-button:hover,
                .add-resource-button:hover {
                    background-color: #218838;
                }

                .switch-container {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    width: 100%;
                    gap: 0.75rem;
                    margin-top: 0.5rem;
                }

                .row-8 {
                    margin-top: 0rem;
                }

                .row-8 .form-field {
                    width: 100%;
                    margin-bottom: 0;
                }
        /* Right */
            .right-side {
                flex: 0 0 340px;
                max-width: 340px;
                min-width: 340px;
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                align-items: flex-start;
                position: sticky;
                top: 88px;
                height: fit-content;
                max-height: calc(100vh - 88px);
                overflow-y: auto;
            }

        /* Preview */
            .group-preview {
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.10);
                padding: 1.5rem;
                margin-bottom: 2rem;
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
                width: 100%;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .group-preview:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            }

            /* Preview Name */
            .name-preview {
                margin: 0 0 0.25rem 0;
                color: #333;
                font-size: 1.2rem;
                font-weight: 600;
                line-height: 1.3;
            }

            /* Preview Description */
            .description-preview {
                margin: 0;
                color: #666;
                font-size: 0.9rem;
                line-height: 1.4;
                width: 100%;
                margin-bottom: 0;
            }

            /* Preview Image */
            .preview-image {
                width: 80px;
                height: 80px;
                background-color: #f8f9fa;
                border: 2px dashed #ddd;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                position: absolute;
                flex-shrink: 0;
                left: 0.5rem;
                top: -60px; /* Moved up more to overlap banner more */
                z-index: 2;
            }
            
            .preview-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 50%;
            }
            
            .preview-image .placeholder-text {
                color: #666;
                font-size: 0.7rem;
                text-align: center;
                font-style: italic;
                padding: 0.2rem;
            }
            
            .preview-image.has-image {
                border: none;
                background-color: transparent;
            }

            /* Preview Banner */
            .preview-banner {
                width: 100%;
                height: 80px;
                background-color: #f8f9fa;
                border: 2px dashed #ddd;
                border-radius: 6px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 0;
                overflow: hidden;
                position: relative;
            }

            .preview-banner img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 4px;
            }

            .preview-banner .placeholder-text {
                color: #666;
                font-size: 0.8rem;
                text-align: center;
                font-style: italic;
            }

            .preview-banner.has-image {
                border: none;
                background-color: transparent;
            }

            .preview-top {
                display: flex;
                align-items: flex-start;
                gap: 1rem;
                width: 100%;
                position: relative;
                margin-top: 0.5rem; /* Add space after banner */
            }

            .preview-name-container {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: flex-start; /* Changed from center */
                margin-left: 100px;
                margin-top: -8px;
            }

            /* Preview Rules */
            .preview-rules {
                width: 100%;
                display: flex;
                flex-direction: column;
                margin-top: 0;
                gap: 0.75rem;
            }

            .preview-rule-item {
                background-color: #f8f9fa;
                border: 1px solid #e9ecef;
                border-radius: 6px;
                padding: 0.75rem;
            }

            .rules-title-preview {
                margin: 0 0 0.25rem 0;
                color: #333;
                font-size: 0.9rem;
                font-weight: 600;
            }

            .rules-description-preview {
                margin: 0;
                color: #666;
                font-size: 0.8rem;
                line-height: 1.3;
                word-wrap: break-word;        /* Add this */
                word-break: break-all;        /* Add this */
                overflow-wrap: break-word;    /* Add this */
            }

            /* Preview Resources */
            .preview-resources {
                width: 100%;
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }

            .preview-resource-item {
                background-color: #f8f9fa;
                border: 1px solid #e9ecef;
                border-radius: 6px;
                padding: 0.75rem;
            }

            .resource-title-preview {
                margin: 0 0 0.25rem 0;
                color: #333;
                font-size: 0.9rem;
                font-weight: 600;
            }

            .resource-description-preview {
                margin: 0;
                color: #666;
                font-size: 0.8rem;
                line-height: 1.3;
                word-wrap: break-word;        /* Add this */
                word-break: break-all;        /* Add this */
                overflow-wrap: break-word;    /* Add this */
            }

            /* Preview Join Button */
            .group-preview button {
                background-color: #4a90e2;
                color: white;
                padding: 0.5rem 1rem;
                border-radius: 6px;
                width: 100%;
                text-align: center;
                border: none;
                cursor: pointer;
                font-weight: 500;
                transition: background-color 0.2s;
                align-self: flex-end;
                margin-top: auto;
            }

            .group-preview button:hover {
                background-color: #357abd;
            }

            /* Section Headers */
            .preview-section-header {
                color: #333;
                font-size: 1rem;
                font-weight: 600;
                border-bottom: 1px solid #e9ecef;
                padding-bottom: 0.25rem;
                width: 100%;
            }

            /* Also add to the form input fields for consistency */
                .rule-item .rule-description,
                .resource-item .resource-description {
                    padding: 0.75rem;
                    border: 1px solid #ddd;
                    border-radius: 6px;
                    font-size: 0.9rem;
                    transition: border-color 0.2s, box-shadow 0.2s;
                    background-color: white;
                    width: 100%;
                    min-height: 80px;
                    resize: vertical;
                    font-family: inherit;
                    word-wrap: break-word;        /* Add this */
                    word-break: break-all;        /* Add this */
                    overflow-wrap: break-word;    /* Add this */
                }
    </style>
</head>
<body>
    @include('components.navbar')
    @include('components.success-header')
    @include('components.error-header')
    <main>
        <div class="left-side">
            <div class="create-group-form">
                <form action="/groups/create-submit" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row-1 form-row">
                        <div class="form-field">
                            <label for="name">Group Name: </label>
                            <input type="text" name="name" id="name" required>
                        </div>
                    </div>

                    <div class="row-2 form-row">
                        <div class="form-field">
                            <label for="description">Description: </label>
                            <textarea name="description" id="description" required></textarea>
                        </div>
                    </div>

                    <div class="row-3 form-row">
                        <div class="form-field">
                            <label for="photo">Group Photo:</label>
                            <div class="file-input-wrapper">
                                <input type="file" name="photo" id="photo" accept=".jpeg,.jpg,.png,.webp">
                                <label for="photo" class="file-input-label" id="photo-label">
                                    <span class="file-text">Choose photo or drag here</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-field">
                            <label for="banner">Group Banner:</label>
                            <div class="file-input-wrapper">
                                <input type="file" name="banner" id="banner" accept=".jpeg,.jpg,.png,.webp">
                                <label for="banner" class="file-input-label" id="banner-label">
                                    <span class="file-text">Choose banner or drag here</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row-4 form-row">
                        <div class="form-field">
                            <div class="switch-container">
                                <label for="is_private" class="switch-label">Private Group</label>
                                <label class="switch">
                                    <input type="checkbox" name="is_private" id="is_private" value="1">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    @if(Auth::user()->role === 'staff')
                        <div class="row-5 from-row">
                            <div class="form-field">
                                <div class="switch-container">
                                    <label for="type" class="switch-label">Group Type: Social/Academic</label>
                                    <label class="switch">
                                        <input type="checkbox" name="type" id="type" value="1">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row-6 form-row">
                        <div class="form-field">
                            <p>Group Rules:</p>
                            <div class="rules-container">
                                <div class="rule-item">
                                    <p>Rule #1</p>
                                    <input type="text" class="rule-title" name="rules[0][title]" id="rules[0][title]" placeholder='Title' required>
                                    <textarea class="rule-description" name="rules[0][description]" id="rules[0][description]" placeholder='Description' required></textarea>
                                    <button type="button" class="remove-rule-button">Remove</button>
                                </div>
                            </div>
                            <button type="button" class="add-rule-button">Add Rule</button>
                        </div>
                    </div>

                    <div class="row-7 form-row">
                        <div class="form-field">
                            <p>Group Resources:</p>
                            <div class="resources-container">
                                <button type="button" class="add-resource-button">Add Resource</button>
                            </div>
                        </div>
                    </div>

                    <div class="row-8 form-row">
                        <div class="form-field">
                            <button type="submit">Create Group</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="right-side">
            <div class="group-preview">
                <div class="preview-banner">
                    <p class="placeholder-text">Banner</p>
                </div>
                <div class="preview-top">
                    <div class="preview-image">
                        <p class="placeholder-text">Photo</p>
                    </div>
                    <div class="preview-name-container">
                        <p class="name-preview">Group Name</p>
                    </div>
                </div>
                <p class="description-preview">Group description will appear here...</p>
                
                <p class="preview-section-header">Rules</p>
                <div class="preview-rules">
                    <div class="preview-rule-item">
                        <p class="rules-title-preview"><strong>Rule Title</strong></p>
                        <p class="rules-description-preview">Rule description...</p>
                    </div>
                </div>
                
                <p class="preview-section-header">Resources</p>
                <div class="preview-resources">
                    <p style="color: #666; font-style: italic;">No resources added</p>
                </div>
                
                <button type="button">Join</button>
            </div>
        </div>
    </main>
    @include('components.back-to-top-button')
</body>
<script>
    let ruleIndex = 1;
    let resourceIndex = 0; // Changed from 1 to 0

    // File input handling
        document.getElementById('photo').addEventListener('change', function(e) {
            handleFileInput(e, 'photo-label');
            updatePhotoPreview(e);
        });
        
        document.getElementById('banner').addEventListener('change', function(e) {
            handleFileInput(e, 'banner-label');
            updateBannerPreview(e);
        });
        
        function handleFileInput(event, labelId) {
            const file = event.target.files[0];
            const label = document.getElementById(labelId);
            const fileText = label.querySelector('.file-text');
            
            if (file) {
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(1); // Size in MB
                
                label.classList.add('has-file');
                fileText.innerHTML = `
                    <span class="file-name">${fileName}</span>
                    <span class="file-size">(${fileSize} MB)</span>
                `;
            } else {
                label.classList.remove('has-file');
                const isPhoto = labelId.includes('photo');
                fileText.textContent = isPhoto ? 'Choose photo or drag here' : 'Choose banner or drag here';
            }
        }

    // Add Rule Button
        document.querySelector('.add-rule-button').addEventListener('click', function() {
            const rulesContainer = document.querySelector('.rules-container');
            const newRule = document.createElement('div');
            newRule.classList.add('rule-item');
            newRule.innerHTML = `
                <p>Rule #${ruleIndex + 1}</p>
                <input type="text" class="rule-title" name="rules[${ruleIndex}][title]" id="rules[${ruleIndex}][title]" placeholder="Title">
                <textarea class="rule-description" name="rules[${ruleIndex}][description]" id="rules[${ruleIndex}][description]" placeholder="Description"></textarea>
                <button type="button" class="remove-rule-button">Remove</button>
            `;
            rulesContainer.appendChild(newRule);
            ruleIndex++;
            updateRuleNumbers();
            updateRemoveButtons();
        });

    // Add Resource Button
        document.querySelector('.add-resource-button').addEventListener('click', function() {
            const resourcesContainer = document.querySelector('.resources-container');
            const addButton = document.querySelector('.add-resource-button');
            
            const newResource = document.createElement('div');
            newResource.classList.add('resource-item');
            newResource.innerHTML = `
                <p>Resource #${resourceIndex + 1}</p>
                <input type="text" class="resource-title" name="resources[${resourceIndex}][title]" id="resources[${resourceIndex}][title]" placeholder="Title">
                <textarea class="resource-description" name="resources[${resourceIndex}][description]" id="resources[${resourceIndex}][description]" placeholder="Description"></textarea>
                <button type="button" class="remove-resource-button">Remove</button>
            `;
            
            resourcesContainer.insertBefore(newResource, addButton);
            resourceIndex++;
            updateResourceNumbers();
        });

    // Remove Rule Button (Event Delegation)
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-rule-button')) {
                const ruleItems = document.querySelectorAll('.rule-item');
                
                // Prevent removal if only 1 rule exists
                if (ruleItems.length <= 1) {
                    alert('You must have at least one rule.');
                    return;
                }
                
                const ruleItem = e.target.closest('.rule-item');
                ruleItem.remove();
                updateRuleNumbers();
                updateRemoveButtons();
                updateRulesPreview(); // Add this line
            }
        });

    // Remove Resource Button (Event Delegation)
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-resource-button')) {
                const resourceItem = e.target.closest('.resource-item');
                resourceItem.remove();
                updateResourceNumbers();
                updateResourcesPreview(); // Add this line
            }
        });

    // Update Rule Numbers
        function updateRuleNumbers() {
            const ruleItems = document.querySelectorAll('.rule-item');
            ruleItems.forEach((item, index) => {
                const numberParagraph = item.querySelector('p');
                numberParagraph.textContent = `Rule #${index + 1}`;
                
                const titleInput = item.querySelector('.rule-title');
                const descriptionTextarea = item.querySelector('.rule-description');
                
                titleInput.name = `rules[${index}][title]`;
                titleInput.id = `rules[${index}][title]`;
                descriptionTextarea.name = `rules[${index}][description]`;
                descriptionTextarea.id = `rules[${index}][description]`;
            });
        }

    // Update Resource Numbers
        function updateResourceNumbers() {
            const resourceItems = document.querySelectorAll('.resource-item');
            resourceItems.forEach((item, index) => {
                const numberParagraph = item.querySelector('p');
                numberParagraph.textContent = `Resource #${index + 1}`;
                
                const titleInput = item.querySelector('.resource-title');
                const descriptionTextarea = item.querySelector('.resource-description');
                
                titleInput.name = `resources[${index}][title]`;
                titleInput.id = `resources[${index}][title]`;
                descriptionTextarea.name = `resources[${index}][description]`;
                descriptionTextarea.id = `resources[${index}][description]`;
            });
        }

    // Update Remove Button States
        function updateRemoveButtons() {
            const ruleItems = document.querySelectorAll('.rule-item');
            const removeButtons = document.querySelectorAll('.remove-rule-button');
            
            removeButtons.forEach(button => {
                if (ruleItems.length <= 1) {
                    button.disabled = true;
                    button.style.opacity = '0.5';
                    button.style.cursor = 'not-allowed';
                } else {
                    button.disabled = false;
                    button.style.opacity = '1';
                    button.style.cursor = 'pointer';
                }
            });
        }

    // Initialize button states on page load
        updateRemoveButtons();
    // Preview Update
        function updatePreview() {
            updateNamePreview();
            updateDescriptionPreview();
            updateRulesPreview();
            updateResourcesPreview();
            updatePrivacyPreview();
            // Remove updateImagePreview() call since we handle images separately now
        }

        function updateNamePreview() {
            const name = document.getElementById('name').value || 'Group Name';
            document.querySelector('.name-preview').textContent = name;
        }

        function updateDescriptionPreview() {
            const description = document.getElementById('description').value || 'Group description will appear here...';
            document.querySelector('.description-preview').textContent = description;
        }

        function updateRulesPreview() {
            const previewRules = document.querySelector('.preview-rules');
            const ruleItems = document.querySelectorAll('.rule-item');
            
            // Clear existing preview rules
            previewRules.innerHTML = '';
            
            ruleItems.forEach((item, index) => {
                const title = item.querySelector('.rule-title').value || `Rule ${index + 1}`;
                const description = item.querySelector('.rule-description').value || 'Rule description...';
                
                const previewRuleItem = document.createElement('div');
                previewRuleItem.classList.add('preview-rule-item');
                previewRuleItem.innerHTML = `
                    <p class="rules-title-preview"><strong>${title}</strong></p>
                    <p class="rules-description-preview">${description}</p>
                `;
                previewRules.appendChild(previewRuleItem);
            });
        }

        function updateResourcesPreview() {
            const previewResources = document.querySelector('.preview-resources');
            const resourceItems = document.querySelectorAll('.resource-item');
            
            // Clear existing preview resources
            previewResources.innerHTML = '';
            
            if (resourceItems.length === 0) {
                previewResources.innerHTML = '<p style="color: #666; font-style: italic;">No resources added</p>';
                return;
            }
            
            resourceItems.forEach((item, index) => {
                const title = item.querySelector('.resource-title').value || `Resource ${index + 1}`;
                const description = item.querySelector('.resource-description').value || 'Resource description...';
                
                const previewResourceItem = document.createElement('div');
                previewResourceItem.classList.add('preview-resource-item');
                previewResourceItem.innerHTML = `
                    <p class="resource-title-preview"><strong>${title}</strong></p>
                    <p class="resource-description-preview">${description}</p>
                `;
                previewResources.appendChild(previewResourceItem);
            });
        }

        function updatePrivacyPreview() {
            const isPrivate = document.getElementById('is_private').checked;
            const joinButton = document.querySelector('.group-preview button');
            
            if (isPrivate) {
                joinButton.textContent = 'Request to Join';
                joinButton.style.backgroundColor = '#6c757d';
                joinButton.style.cursor = 'not-allowed';
            } else {
                joinButton.textContent = 'Join';
                joinButton.style.backgroundColor = '#4a90e2';
                joinButton.style.cursor = 'pointer';
            }
        }

        // Event Listeners for Real-time Updates
        document.getElementById('name').addEventListener('input', updateNamePreview);
        document.getElementById('description').addEventListener('input', updateDescriptionPreview);
        document.getElementById('is_private').addEventListener('change', updatePrivacyPreview);

        // Event delegation for dynamically added rule/resource inputs
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('rule-title') || e.target.classList.contains('rule-description')) {
                updateRulesPreview();
            }
            if (e.target.classList.contains('resource-title') || e.target.classList.contains('resource-description')) {
                updateResourcesPreview();
            }
        });
    // Preview Image Update
        document.getElementById('photo').addEventListener('change', function(e) {
            handleFileInput(e, 'photo-label');
            updatePhotoPreview(e);
        });
        
        document.getElementById('banner').addEventListener('change', function(e) {
            handleFileInput(e, 'banner-label');
            updateBannerPreview(e);
        });
        
        function updatePhotoPreview(event) {
            const file = event.target.files[0];
            const previewImage = document.querySelector('.preview-image');
            
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImage.innerHTML = `<img src="${e.target.result}" alt="Group photo preview">`;
                    previewImage.classList.add('has-image');
                };
                
                reader.readAsDataURL(file);
            } else {
                previewImage.innerHTML = '<p class="placeholder-text">Photo</p>';
                previewImage.classList.remove('has-image');
            }
        }

        function updateBannerPreview(event) {
            const file = event.target.files[0];
            const previewBanner = document.querySelector('.preview-banner');
            
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewBanner.innerHTML = `<img src="${e.target.result}" alt="Group banner preview">`;
                    previewBanner.classList.add('has-image');
                };
                
                reader.readAsDataURL(file);
            } else {
                previewBanner.innerHTML = '<p class="placeholder-text">Banner preview will appear here</p>';
                previewBanner.classList.remove('has-image');
            }
        }
</script>
</html>