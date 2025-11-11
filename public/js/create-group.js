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