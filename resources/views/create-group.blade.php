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
                    padding: 1rem; 
                    border-radius: 8px; 
                    margin-top: -0.5rem;
                    margin-bottom: 1rem; 
                    text-align: center;
                }

            /* ERROR HEADER */
                .error-message {
                    background-color: #f8d7da; 
                    color: #721c24; 
                    padding: 1rem; 
                    border-radius: 8px; 
                    margin-top: -0.5rem;
                    margin-bottom: 1rem;
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
                
                .row-4 .form-field p {
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

                .row-5 .form-field p {
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

                .row-7 button[type="submit"] {
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
                
                .row-7 button[type="submit"]:hover {
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

                .row-4,
                .row-5 {
                    display: flex;
                    flex-direction: column;
                    width: 100%;
                    background-color: #f8f9fa;
                    border-radius: 8px;
                    border: 1px solid #ddd;
                    padding: 1.5rem;
                    margin-bottom: 1rem;
                }

                .row-4 .form-field,
                .row-5 .form-field {
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

                .row-6 {
                    margin-top: 1.5rem;
                }

                .row-6 .form-field {
                    width: 100%;
                }
                
                .switch-container {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    width: 100%;
                    gap: 0.75rem;
                    margin-top: 0.5rem;
                }

                .row-7 {
                    margin-top: 0rem;
                }

                .row-7 .form-field {
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
                padding: 2rem 1.5rem;
                margin-bottom: 2rem;
                display: flex;
                flex-direction: column;
                align-items: start;
                gap: 1rem;
                width: 100%;
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
                <form action="/groups/create-submit" method="post">
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
                        <!-- img ----------------------------- banner -->
                        <!-- MAKE TS WORK PLEASE -->
                    </div>

                    <div class="row-6 form-row">
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

                    <div class="row-4 form-row">
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

                    <div class="row-5 form-row">
                        <div class="form-field">
                            <p>Group Resources:</p>
                            <div class="resources-container">
                                <button type="button" class="add-resource-button">Add Resource</button>
                            </div>
                        </div>
                    </div>

                    <div class="row-7 form-row">
                        <div class="form-field">
                            <button type="submit">Create Group</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="right-side">
            <div class="group-preview">
                Preview
            </div>
        </div>
    </main>
    @include('components.back-to-top-button')
</body>
<script>
    let ruleIndex = 1;
    let resourceIndex = 0; // Changed from 1 to 0

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
        }
    });

    // Remove Resource Button (Event Delegation)
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-resource-button')) {
            const resourceItem = e.target.closest('.resource-item');
            resourceItem.remove();
            updateResourceNumbers();
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
</script>
</html>