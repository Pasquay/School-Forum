<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAROLINK | Create Group</title>
    <meta name='csrf-token' content='{{ csrf_token() }}'>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/create-group.css') }}">
</head>

<body>
    @include('components.navbar', ['active' => ''])
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
<script src="{{ asset('js/create-group.js') }}"></script>

</html>