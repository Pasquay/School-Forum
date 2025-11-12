// Student Assignment Modal JavaScript

let currentAssignment = null;
let currentSubmission = null;
let autoSaveInterval = null;
let lastSavedContent = null;
let isAutoSaving = false;
let quizTimer = null;
let quizTimeRemaining = 0;
let quizStartTime = null;

// Helper function to parse datetime as local time (not UTC)
function parseLocalDateTime(dateString) {
    if (!dateString) return null;
    try {
        // Remove timezone markers if present (Z or +00:00)
        let cleanDate = dateString.replace(/Z$/, '').replace(/\.\d{6}$/, '').replace(/[+-]\d{2}:\d{2}$/, '');
        
        // dateString format: "2025-10-14T18:07:00" or "2025-10-14T18:07"
        const [datePart, timePart] = cleanDate.split('T');
        if (!datePart || !timePart) return new Date(dateString); // Fallback
        
        const [year, month, day] = datePart.split('-');
        const timeComponents = timePart.split(':');
        const hour = timeComponents[0];
        const minute = timeComponents[1];
        const second = timeComponents[2] || 0;
        
        return new Date(year, month - 1, day, hour, minute, second);
    } catch (e) {
        console.error('Error parsing date:', dateString, e);
        return new Date(dateString); // Fallback to default parsing
    }
}

// DOM helpers to avoid null.style errors
function $(id) { return document.getElementById(id); }
function setDisplay(id, value) { const el = $(id); if (el) { el.style.display = value; } else { console.warn('Missing element:', id); } }
function showEl(id) { setDisplay(id, 'block'); }
function hideEl(id) { setDisplay(id, 'none'); }

// Open student assignment modal
function openStudentAssignmentModal(assignmentId) {
    const modal = document.getElementById('studentAssignmentModal');
    modal.classList.add('show');
    modal.style.display = 'flex';
    loadAssignmentForStudent(assignmentId);
}

// Close student assignment modal
function closeStudentAssignmentModal() {
    const modal = document.getElementById('studentAssignmentModal');
    
    // Save quiz progress before closing if it's a quiz/exam
    if (currentAssignment && (currentAssignment.assignment_type === 'quiz' || currentAssignment.assignment_type === 'exam')) {
        if (quizStartTime) { // Only save if quiz has been started
            saveQuizProgress();
        }
    }
    
    modal.classList.remove('show');
    modal.style.display = 'none';
    
    // Stop auto-save and timer
    stopAutoSave();
    stopQuizTimer();
    
    currentAssignment = null;
    currentSubmission = null;
    lastSavedContent = null;
    quizStartTime = null;
}

// Load assignment data for student
async function loadAssignmentForStudent(assignmentId) {
    try {
        const response = await fetch(`/group/${window.groupData.id}/assignments/${assignmentId}/my-submission`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to load assignment');
        }

        const data = await response.json();
        currentAssignment = data.assignment;
        currentSubmission = data.submission;

        displayAssignmentInfo(data.assignment, data.submission, data.can_submit);
        
        // Load resubmission history if assignment allows resubmissions and student has submitted
        if (data.assignment.allow_resubmissions && data.submission && data.submission.status !== 'draft') {
            await loadResubmissionHistory(assignmentId);
        }
    } catch (error) {
        console.error('Error loading assignment:', error);
        alert('Failed to load assignment');
        closeStudentAssignmentModal();
    }
}

// Display assignment information
function displayAssignmentInfo(assignment, submission, canSubmit) {
    try {
        console.log('displayAssignmentInfo called with:', { assignment, submission, canSubmit });
        
        // Set title
        document.getElementById('assignment-modal-title').textContent = assignment.assignment_name;

    // Set type badge
    const typeBadge = document.getElementById('assignment-type-badge');
    typeBadge.textContent = assignment.assignment_type;
    typeBadge.className = `assignment-badge ${assignment.assignment_type}`;

    // Set status badge
    const statusBadge = document.getElementById('assignment-status-badge');
    if (submission.status === 'submitted' || submission.status === 'graded') {
        statusBadge.textContent = submission.is_late ? 'Submitted Late' : 'Submitted';
        statusBadge.className = `status-badge ${submission.status}`;
        statusBadge.style.display = 'inline-block';
    } else {
        statusBadge.textContent = 'Not Submitted';
        statusBadge.className = 'status-badge not-submitted';
        statusBadge.style.display = 'inline-block';
    }

    // Set points
    document.getElementById('assignment-points').textContent = assignment.max_points;

    // Set dates
    document.getElementById('assignment-date-assigned').textContent = 
        assignment.date_assigned ? parseLocalDateTime(assignment.date_assigned).toLocaleString() : 'Not specified';
    
    const dueDate = parseLocalDateTime(assignment.date_due);
    document.getElementById('assignment-date-due').textContent = dueDate.toLocaleString();

    // Show countdown
    updateDueCountdown(dueDate);

    // Close date
    if (assignment.close_date) {
        document.getElementById('close-date-container').style.display = 'block';
        document.getElementById('assignment-close-date').textContent = 
            parseLocalDateTime(assignment.close_date).toLocaleString();
    }

    // Description
    document.getElementById('assignment-description').innerHTML = 
        assignment.description || '<em>No description provided</em>';

    // Attachments
    if (assignment.attachments && assignment.attachments.length > 0) {
        document.getElementById('assignment-attachments-container').style.display = 'block';
        const attachmentsList = document.getElementById('assignment-attachments-list');
        attachmentsList.innerHTML = assignment.attachments.map(attachment => {
            const fileIcon = getFileIcon(attachment.file_type);
            const fileSize = formatFileSize(attachment.file_size);
            return `
                <a href="${attachment.download_url}" class="attachment-item" download="${attachment.file_name}">
                    <span class="file-icon">${fileIcon}</span>
                    <span class="file-info">
                        <span class="file-name">${attachment.file_name}</span>
                        <span class="file-size">${fileSize}</span>
                    </span>
                    <span class="download-icon">⬇️</span>
                </a>
            `;
        }).join('');
    }

    // External link (this is not used for assignments - external_link is a submission property)
    // Removed: assignments don't have external_link, only submissions do

    // Handle submission section
    if (submission.status === 'submitted' || submission.status === 'graded') {
        showSubmittedView(assignment, submission);
    } else if (canSubmit) {
        showSubmissionForm(assignment, submission);
    } else {
        // Not submitted/graded and cannot submit
        const closedMsg = document.getElementById('closed-message');
        const now = new Date();
        const dueDate = parseLocalDateTime(assignment.date_due);

        // If the assignment is closed, maintain existing behavior: hide form and show message
        if (assignment.is_closed) {
            closedMsg.innerHTML = '<p class="error-message">⚠️ This assignment is closed and no longer accepting submissions.</p>';
            closedMsg.style.display = 'block';
            // Ensure the submission form stays hidden
            document.getElementById('assignment-submission-form').style.display = 'none';
            document.getElementById('submitted-view').style.display = 'none';
            return;
        }

        // Keep the form visible but disable actions with a clear tooltip explaining why
        showSubmissionForm(assignment, submission);

        let reason = 'This assignment is not accepting submissions at this time.';
        if (now > dueDate && !assignment.allow_late_submissions) {
            reason = 'This assignment is past due and late submissions are not allowed.';
        }

        // Disable submit and save-draft buttons
        const submitBtn = document.getElementById('submit-btn');
        const draftBtn = document.getElementById('save-draft-btn');
        [submitBtn, draftBtn].forEach(btn => {
            if (!btn) return;
            btn.disabled = true;
            btn.title = reason;
            btn.setAttribute('aria-disabled', 'true');
            btn.style.opacity = '0.6';
            btn.style.cursor = 'not-allowed';
        });

        // Also disable quiz start button if present (quiz/exam with time limit)
        const startQuizBtn = document.querySelector('#quiz-ready-prompt button');
        if (startQuizBtn) {
            startQuizBtn.disabled = true;
            startQuizBtn.title = reason;
            startQuizBtn.setAttribute('aria-disabled', 'true');
            startQuizBtn.style.opacity = '0.6';
            startQuizBtn.style.cursor = 'not-allowed';
        }

        // Show an informational banner while keeping the form visible
        closedMsg.innerHTML = `<p class="error-message">⚠️ ${reason}</p>`;
        closedMsg.style.display = 'block';
    }
    } catch (error) {
        console.error('Error in displayAssignmentInfo:', error);
        console.error('Assignment:', assignment);
        console.error('Submission:', submission);
        alert('Error displaying assignment information. Please check console for details.');
    }
}

// Show submitted view
function showSubmittedView(assignment, submission) {
    try {
        console.log('showSubmittedView called with:', { assignment, submission });
        
        // Guarded show/hide to prevent null.style errors if markup changes
        showEl('submitted-view');
        hideEl('assignment-submission-form');
        hideEl('closed-message');

        // Submission date
        const submittedDate = $('submitted-date');
        if (submittedDate) {
            if (submission.date_submitted) {
                submittedDate.textContent = new Date(submission.date_submitted).toLocaleString();
            } else {
                submittedDate.textContent = 'N/A';
            }
        }

        // Late badge
        if (submission.is_late) { setDisplay('late-badge', 'inline'); }

        // Display submitted content
        const contentDisplay = $('submission-content-display');
        if (!contentDisplay) {
            console.warn('Missing element: submission-content-display');
            return;
        }
    if (assignment.submission_type === 'text') {
        contentDisplay.innerHTML = submission.submission_text || '<em>No text submitted</em>';
    } else if (assignment.submission_type === 'file') {
        if (submission.file_path) {
            contentDisplay.innerHTML = `<a href="/storage/${submission.file_path}" target="_blank">📎 View Submitted File</a>`;
        } else {
            contentDisplay.innerHTML = '<em>No file submitted</em>';
        }
    } else if (assignment.submission_type === 'external_link') {
        if (submission.external_link) {
            contentDisplay.innerHTML = `<a href="${submission.external_link}" target="_blank">🔗 ${submission.external_link}</a>`;
        } else {
            contentDisplay.innerHTML = '<em>No link submitted</em>';
        }
    } else if (assignment.assignment_type === 'quiz' || assignment.assignment_type === 'exam') {
        displayQuizResults(assignment, submission);
    } else if (assignment.submission_type === 'none') {
        contentDisplay.innerHTML = '<em>No submission required</em>';
    }

    // Show grade if graded
    if (submission.status === 'graded' && submission.grade !== null) {
        showEl('grade-display');
        // Convert grade to number in case it's a string from the database
        const gradeValue = parseFloat(submission.grade);
        const gv = $('grade-value'); if (gv) gv.textContent = gradeValue.toFixed(2);
        const gm = $('grade-max'); if (gm) gm.textContent = assignment.max_points;
        
        if (submission.teacher_feedback) {
            const tf = $('teacher-feedback');
            if (tf) tf.innerHTML = `<strong>Feedback:</strong><br>${submission.teacher_feedback}`;
        }
    }

    // Load and display teacher comments/feedback
    if (submission.student_id && assignment.id) {
        loadStudentComments(assignment.id, submission.student_id);
    }

    // Show resubmit button if allowed (before due date)
    // BUT NOT for quizzes/exams - they cannot be retaken
    const now = new Date();
    const dueDate = parseLocalDateTime(assignment.date_due);
    const isQuizOrExam = assignment.assignment_type === 'quiz' || assignment.assignment_type === 'exam';
    
    if (isQuizOrExam) {
        // Show message that quizzes/exams cannot be retaken
        showEl('no-retake-message');
        setDisplay('resubmit-btn', 'none');
    } else if (now < dueDate && !assignment.is_closed) {
        // Show resubmit button for other assignment types
        setDisplay('resubmit-btn', 'inline-block');
        hideEl('no-retake-message');
    } else {
        // Hide both if past due date or closed
        setDisplay('resubmit-btn', 'none');
        hideEl('no-retake-message');
    }
    } catch (error) {
        console.error('Error in showSubmittedView:', error);
        console.error('Assignment:', assignment);
        console.error('Submission:', submission);
        alert('Error displaying submission view. Please check console for details.');
    }
}

// Show submission form
function showSubmissionForm(assignment, submission) {
    document.getElementById('submitted-view').style.display = 'none';
    document.getElementById('assignment-submission-form').style.display = 'block';
    document.getElementById('closed-message').style.display = 'none';

    // Hide all submission containers
    document.getElementById('text-submission-container').style.display = 'none';
    document.getElementById('file-submission-container').style.display = 'none';
    document.getElementById('link-submission-container').style.display = 'none';
    document.getElementById('quiz-submission-container').style.display = 'none';
    document.getElementById('no-submission-container').style.display = 'none';

    // Check if assignment is overdue AND late submissions are not allowed
    const now = new Date();
    const dueDate = parseLocalDateTime(assignment.date_due);
    const isOverdue = now > dueDate;
    const shouldBlockSubmission = isOverdue && !assignment.allow_late_submissions;

    // Show appropriate submission type
    if (assignment.submission_type === 'text') {
        document.getElementById('text-submission-container').style.display = 'block';
        
        // Initialize Quill editor
        initializeQuillEditor();
        
        // Load existing submission if any
        if (submission.submission_text) {
            quillEditor.root.innerHTML = submission.submission_text;
            document.getElementById('submission_text').value = submission.submission_text;
        } else {
            quillEditor.setText('');
        }
        
        setupWordCounter();
        
        // Start auto-save for text submissions
        startAutoSave();
        lastSavedContent = JSON.stringify(getFormData());
    } else if (assignment.submission_type === 'file') {
        document.getElementById('file-submission-container').style.display = 'block';
        setupFileUpload();
    } else if (assignment.submission_type === 'external_link') {
        document.getElementById('link-submission-container').style.display = 'block';
        if (submission.external_link) {
            document.getElementById('external_link_input').value = submission.external_link;
        }
        
        // Start auto-save for link submissions
        startAutoSave();
        lastSavedContent = JSON.stringify(getFormData());
    } else if (assignment.assignment_type === 'quiz' || assignment.assignment_type === 'exam') {
        // Check if quiz/exam has time limit
        if (assignment.time_limit && !submission.quiz_started) {
            // Show ready prompt instead of quiz
            document.getElementById('assignment-submission-form').style.display = 'none';
            document.getElementById('quiz-ready-prompt').style.display = 'block';
            
            // Update time limit text
            const timeLimitText = document.getElementById('quiz-time-limit-text');
            timeLimitText.textContent = `This ${assignment.assignment_type} has a ${assignment.time_limit} minute time limit. Once you click "Start", the timer will begin and cannot be paused.`;
        } else {
            // No time limit or already started, show quiz directly
            document.getElementById('quiz-submission-container').style.display = 'block';
            displayQuizQuestions(assignment, submission);
            
            // If time limit exists and quiz started, show timer
            if (assignment.time_limit && submission.quiz_started) {
                document.getElementById('timer-container').style.display = 'block';
                // Calculate remaining time based on start time
                const startTime = new Date(submission.quiz_started);
                const now = new Date();
                const elapsedMinutes = (now - startTime) / (1000 * 60);
                quizTimeRemaining = Math.max(0, (assignment.time_limit - elapsedMinutes) * 60);
                startQuizTimer();
            }
        }
        // No auto-save for quizzes
    } else if (assignment.submission_type === 'none') {
        document.getElementById('no-submission-container').style.display = 'block';
        document.getElementById('save-draft-btn').style.display = 'none';
        document.getElementById('submit-btn').style.display = 'none';
    }

    // Grey out submit button if assignment is overdue AND late submissions not allowed
    const submitBtn = document.getElementById('submit-btn');
    if (submitBtn && shouldBlockSubmission) {
        submitBtn.disabled = true;
        submitBtn.title = 'This assignment is past the due date and late submissions are not allowed';
        submitBtn.setAttribute('aria-disabled', 'true');
        submitBtn.style.opacity = '0.5';
        submitBtn.style.cursor = 'not-allowed';
        submitBtn.style.backgroundColor = '#999';
    } else if (submitBtn) {
        // Re-enable if late submissions are allowed or not overdue
        submitBtn.disabled = false;
        submitBtn.removeAttribute('aria-disabled');
        submitBtn.title = '';
        submitBtn.style.opacity = '1';
        submitBtn.style.cursor = 'pointer';
        submitBtn.style.backgroundColor = '';
    }

    // Setup form submission
    setupFormSubmission();
}

// Display quiz questions for taking
function displayQuizQuestions(assignment, submission) {
    const container = document.getElementById('quiz-questions-container');
    container.innerHTML = '';

    assignment.quiz_questions.forEach((question, index) => {
        const questionDiv = document.createElement('div');
        questionDiv.className = 'quiz-question';

        let questionHTML = `
            <div class="question-header">
                <span class="question-number">Question ${index + 1}</span>
                <span class="question-points">${question.points} point${question.points !== 1 ? 's' : ''}</span>
            </div>
            <div class="question-text">${question.question_text}</div>
        `;

        // Get saved response if exists
        const savedResponse = submission.quiz_responses?.find(r => r.question_id === question.id);

        if (question.question_type === 'multiple_choice' || question.question_type === 'true_false') {
            questionHTML += '<div class="quiz-options">';
            question.options.forEach(option => {
                const checked = savedResponse?.selected_option_id === option.id ? 'checked' : '';
                questionHTML += `
                    <div class="quiz-option">
                        <input type="radio" 
                               id="option_${option.id}" 
                               name="question_${question.id}" 
                               value="${option.id}"
                               ${checked}>
                        <label for="option_${option.id}">${option.option_text}</label>
                    </div>
                `;
            });
            questionHTML += '</div>';
        } else {
            // Short answer or essay
            const savedText = savedResponse?.text_response || '';
            const rows = question.question_type === 'essay' ? 10 : 3;
            questionHTML += `
                <textarea class="quiz-text-answer" 
                          name="question_${question.id}_text" 
                          rows="${rows}" 
                          placeholder="Enter your answer...">${savedText}</textarea>
            `;
        }

        questionDiv.innerHTML = questionHTML;
        container.appendChild(questionDiv);
    });
}

// Display quiz results after submission
function displayQuizResults(assignment, submission) {
    const container = document.getElementById('submission-content-display');
    let resultsHTML = '<div class="quiz-results">';

    assignment.quiz_questions.forEach((question, index) => {
        const response = submission.quiz_responses?.find(r => r.question_id === question.id);
        
        resultsHTML += `
            <div class="quiz-result-item">
                <strong>Question ${index + 1}:</strong> ${question.question_text}<br>
        `;

        if (response) {
            if (question.question_type === 'multiple_choice' || question.question_type === 'true_false') {
                const selectedOption = question.options.find(o => o.id === response.selected_option_id);
                resultsHTML += `<em>Your answer:</em> ${selectedOption?.option_text || 'Not answered'}<br>`;
                
                if (response.is_correct !== null) {
                    resultsHTML += response.is_correct ? 
                        `<span style="color: green;">✓ Correct (+${response.points_earned} points)</span>` :
                        `<span style="color: red;">✗ Incorrect (0 points)</span>`;
                }
            } else {
                resultsHTML += `<em>Your answer:</em> ${response.text_response || 'Not answered'}<br>`;
                if (response.points_earned !== null) {
                    resultsHTML += `<em>Points earned:</em> ${response.points_earned} / ${question.points}`;
                }
            }
        } else {
            resultsHTML += '<em>Not answered</em>';
        }

        resultsHTML += '</div><br>';
    });

    resultsHTML += '</div>';
    container.innerHTML = resultsHTML;
}

// Update due date countdown
function updateDueCountdown(dueDate) {
    const now = new Date();
    const diff = dueDate - now;
    const countdown = document.getElementById('due-countdown');

    if (!countdown) return;

    if (diff < 0) {
        const status = currentSubmission ? currentSubmission.status : null;
        // Suppress overdue label when already submitted or graded
        if (status === 'submitted' || status === 'graded') {
            countdown.textContent = '';
            countdown.className = 'countdown normal';
        } else {
            countdown.textContent = '(Overdue)';
            countdown.className = 'countdown urgent';
        }
        return;
    }

    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));

    if (days > 1) {
        countdown.textContent = `(Due in ${days} days)`;
        countdown.className = 'countdown normal';
    } else if (days === 1) {
        countdown.textContent = `(Due in 1 day, ${hours} hours)`;
        countdown.className = 'countdown warning';
    } else {
        countdown.textContent = `(Due in ${hours} hours)`;
        countdown.className = 'countdown urgent';
    }
}

// Setup word counter for text submissions
function setupWordCounter() {
    const textarea = document.getElementById('submission_text');
    const wordCount = document.getElementById('word-count');
    const charCount = document.getElementById('char-count');

    textarea.addEventListener('input', () => {
        const text = textarea.value.trim();
        const words = text.split(/\s+/).filter(word => word.length > 0);
        wordCount.textContent = words.length;
        charCount.textContent = text.length;
    });

    // Initial count
    textarea.dispatchEvent(new Event('input'));
}

// Setup file upload
function setupFileUpload() {
    const uploadArea = document.getElementById('file-upload-area');
    const fileInput = document.getElementById('file_upload');
    const fileDisplay = document.getElementById('file-name-display');

    uploadArea.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            fileDisplay.innerHTML = `Selected: <strong>${file.name}</strong> (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            fileDisplay.style.display = 'block';
        }
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#1976d2';
        uploadArea.style.background = '#e3f2fd';
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.style.borderColor = '#ddd';
        uploadArea.style.background = '';
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#ddd';
        uploadArea.style.background = '';
        
        const file = e.dataTransfer.files[0];
        if (file) {
            fileInput.files = e.dataTransfer.files;
            fileDisplay.innerHTML = `Selected: <strong>${file.name}</strong> (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            fileDisplay.style.display = 'block';
        }
    });
}

// Save draft
async function saveDraft(showAlert = true) {
    const formData = getFormData();
    
    try {
        const response = await fetch(`/group/${window.groupData.id}/assignments/${currentAssignment.id}/save-draft`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        if (!response.ok) {
            throw new Error('Failed to save draft');
        }

        if (showAlert) {
            alert('✅ Draft saved successfully!');
        }
        
        // Update last saved content
        lastSavedContent = JSON.stringify(formData);
        updateAutoSaveIndicator('saved');
        
        return true;
    } catch (error) {
        console.error('Error saving draft:', error);
        if (showAlert) {
            alert('❌ Failed to save draft');
        }
        updateAutoSaveIndicator('error');
        return false;
    }
}

// Auto-save draft
async function autoSaveDraft() {
    if (isAutoSaving) return;
    
    // Don't auto-save if already submitted or graded
    if (currentSubmission && (currentSubmission.status === 'submitted' || currentSubmission.status === 'graded')) {
        return;
    }
    
    // Don't auto-save for quiz/exam types
    if (currentAssignment && (currentAssignment.assignment_type === 'quiz' || currentAssignment.assignment_type === 'exam')) {
        return;
    }
    
    const formData = getFormData();
    const currentContent = JSON.stringify(formData);
    
    // Only save if content has changed
    if (currentContent === lastSavedContent) {
        return;
    }
    
    // Check if there's any content to save
    const hasContent = formData.submission_text || formData.external_link;
    if (!hasContent) {
        return;
    }
    
    isAutoSaving = true;
    updateAutoSaveIndicator('saving');
    
    try {
        const response = await fetch(`/group/${window.groupData.id}/assignments/${currentAssignment.id}/save-draft`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        if (response.ok) {
            lastSavedContent = currentContent;
            updateAutoSaveIndicator('saved');
        } else {
            updateAutoSaveIndicator('error');
        }
    } catch (error) {
        console.error('Auto-save error:', error);
        updateAutoSaveIndicator('error');
    } finally {
        isAutoSaving = false;
    }
}

// Save quiz progress (responses and timer state)
async function saveQuizProgress() {
    if (!currentAssignment || !currentSubmission) return;
    
    try {
        // Collect current quiz responses
        const quizResponses = {};
        currentAssignment.quiz_questions.forEach(question => {
            if (question.question_type === 'multiple_choice' || question.question_type === 'true_false') {
                const selected = document.querySelector(`input[name="question_${question.id}"]:checked`);
                if (selected) {
                    quizResponses[question.id] = {
                        selected_option_id: parseInt(selected.value)
                    };
                }
            } else {
                const textAnswer = document.querySelector(`textarea[name="question_${question.id}_text"]`);
                if (textAnswer && textAnswer.value.trim()) {
                    quizResponses[question.id] = {
                        text_response: textAnswer.value
                    };
                }
            }
        });

        // Save progress to server
        const response = await fetch(`/group/${window.groupData.id}/assignments/${currentAssignment.id}/save-quiz-progress`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                quiz_responses: quizResponses,
                time_remaining: quizTimeRemaining
            })
        });

        if (!response.ok) {
            console.error('Failed to save quiz progress');
        }
    } catch (error) {
        console.error('Error saving quiz progress:', error);
    }
}

// Start auto-save
function startAutoSave() {
    // Clear any existing interval
    stopAutoSave();
    
    // Save every 30 seconds
    autoSaveInterval = setInterval(autoSaveDraft, 30000);
    
    // Show auto-save indicator
    showAutoSaveIndicator();
}

// Stop auto-save
function stopAutoSave() {
    if (autoSaveInterval) {
        clearInterval(autoSaveInterval);
        autoSaveInterval = null;
    }
    hideAutoSaveIndicator();
}

// Update auto-save indicator
function updateAutoSaveIndicator(status) {
    const indicator = document.getElementById('auto-save-indicator');
    if (!indicator) return;
    
    const statusText = indicator.querySelector('.auto-save-status');
    const statusIcon = indicator.querySelector('.auto-save-icon');
    
    if (status === 'saving') {
        statusIcon.textContent = '⏳';
        statusText.textContent = 'Saving...';
        statusText.style.color = '#6b7280';
    } else if (status === 'saved') {
        statusIcon.textContent = '✓';
        statusText.textContent = 'Draft saved';
        statusText.style.color = '#16a34a';
        
        // Reset to default after 3 seconds
        setTimeout(() => {
            statusIcon.textContent = '💾';
            statusText.textContent = 'Auto-save enabled';
            statusText.style.color = '#6b7280';
        }, 3000);
    } else if (status === 'error') {
        statusIcon.textContent = '⚠️';
        statusText.textContent = 'Save failed';
        statusText.style.color = '#dc2626';
    }
}

// Show auto-save indicator
function showAutoSaveIndicator() {
    let indicator = document.getElementById('auto-save-indicator');
    
    if (!indicator) {
        // Create indicator if it doesn't exist
        indicator = document.createElement('div');
        indicator.id = 'auto-save-indicator';
        indicator.innerHTML = `
            <span class="auto-save-icon">💾</span>
            <span class="auto-save-status">Auto-save enabled</span>
        `;
        indicator.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            padding: 10px 16px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            z-index: 999;
            border: 1px solid #e5e7eb;
        `;
        document.body.appendChild(indicator);
    }
    
    indicator.style.display = 'flex';
}

// Hide auto-save indicator
function hideAutoSaveIndicator() {
    const indicator = document.getElementById('auto-save-indicator');
    if (indicator) {
        indicator.style.display = 'none';
    }
}

// Setup form submission
function setupFormSubmission() {
    const form = document.getElementById('assignment-submission-form');
    form.action = `/group/${window.groupData.id}/assignments/${currentAssignment.id}/submit`;
    
    form.onsubmit = (e) => {
        if (!confirm('Are you sure you want to submit this assignment? You may not be able to edit it after submission.')) {
            e.preventDefault();
            return false;
        }

        // For quiz/exam submissions, add quiz responses
        if (currentAssignment.assignment_type === 'quiz' || currentAssignment.assignment_type === 'exam') {
            e.preventDefault();
            submitQuiz();
        }
    };
}

// Submit quiz
async function submitQuiz() {
    const formData = getFormData();
    
    // Add quiz responses
    const quizResponses = {};
    currentAssignment.quiz_questions.forEach(question => {
        if (question.question_type === 'multiple_choice' || question.question_type === 'true_false') {
            const selected = document.querySelector(`input[name="question_${question.id}"]:checked`);
            if (selected) {
                quizResponses[question.id] = {
                    selected_option_id: parseInt(selected.value)
                };
            }
        } else {
            const textAnswer = document.querySelector(`textarea[name="question_${question.id}_text"]`);
            if (textAnswer) {
                quizResponses[question.id] = {
                    text_response: textAnswer.value
                };
            }
        }
    });

    formData.quiz_responses = quizResponses;

    // Submit via form
    const form = document.getElementById('assignment-submission-form');
    
    // Add quiz responses as hidden input
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'quiz_responses';
    hiddenInput.value = JSON.stringify(quizResponses);
    form.appendChild(hiddenInput);
    
    form.submit();
}

// Get form data
function getFormData() {
    const data = {};

    if (currentAssignment.submission_type === 'text') {
        data.submission_text = document.getElementById('submission_text').value;
    } else if (currentAssignment.submission_type === 'external_link') {
        data.external_link = document.getElementById('external_link_input').value;
    }

    return data;
}

// Enable resubmit
function enableResubmit() {
    document.getElementById('submitted-view').style.display = 'none';
    showSubmissionForm(currentAssignment, currentSubmission);
}

// Initialize Quill Rich Text Editor
let quillEditor = null;

function initializeQuillEditor() {
    if (!quillEditor && document.getElementById('quill-editor')) {
        quillEditor = new Quill('#quill-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    ['link', 'image'],
                    ['clean']
                ]
            },
            placeholder: 'Enter your submission here...'
        });

        // Sync Quill content with hidden textarea
        quillEditor.on('text-change', function() {
            const html = quillEditor.root.innerHTML;
            document.getElementById('submission_text').value = html;
            
            // Update word count
            const text = quillEditor.getText();
            const words = text.trim().split(/\s+/).filter(word => word.length > 0).length;
            const chars = text.length;
            document.getElementById('word-count').textContent = words;
            document.getElementById('char-count').textContent = chars;
        });
    }
}

// Start timed quiz/exam
async function startTimedQuiz() {
    if (!currentAssignment || !currentAssignment.time_limit) return;
    
    // Save quiz start time to server
    try {
        const response = await fetch(`/group/${window.groupData.id}/assignments/${currentAssignment.id}/start-quiz`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to start quiz');
        }
        
        const data = await response.json();
        quizStartTime = new Date(data.quiz_started);
        
    } catch (error) {
        console.error('Error starting quiz:', error);
        alert('Failed to start quiz. Please try again.');
        return;
    }
    
    // Hide ready prompt
    document.getElementById('quiz-ready-prompt').style.display = 'none';
    
    // Show quiz submission container and timer
    document.getElementById('quiz-submission-container').style.display = 'block';
    document.getElementById('assignment-submission-form').style.display = 'block';
    document.getElementById('timer-container').style.display = 'block';
    
    // Display the quiz questions
    displayQuizQuestions(currentAssignment, currentSubmission);
    
    // Set time remaining (in seconds)
    quizTimeRemaining = currentAssignment.time_limit * 60;
    
    // Start the timer
    startQuizTimer();
    
    // Setup form submission
    setupFormSubmission();
}

// Start quiz timer
function startQuizTimer() {
    const timerDisplay = document.getElementById('timer-display');
    
    quizTimer = setInterval(() => {
        quizTimeRemaining--;
        
        // Format time as MM:SS
        const minutes = Math.floor(quizTimeRemaining / 60);
        const seconds = quizTimeRemaining % 60;
        const timeString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        timerDisplay.textContent = timeString;
        
        // Add warning/danger classes
        const percentRemaining = (quizTimeRemaining / (currentAssignment.time_limit * 60)) * 100;
        timerDisplay.classList.remove('warning', 'danger');
        
        if (percentRemaining <= 10) {
            timerDisplay.classList.add('danger');
        } else if (percentRemaining <= 25) {
            timerDisplay.classList.add('warning');
        }
        
        // Time's up!
        if (quizTimeRemaining <= 0) {
            stopQuizTimer();
            alert('Time is up! Your quiz/exam will be automatically submitted.');
            submitQuiz(); // Auto-submit quiz
        }
    }, 1000);
}

// Stop quiz timer
function stopQuizTimer() {
    if (quizTimer) {
        clearInterval(quizTimer);
        quizTimer = null;
    }
}

// Helper: Get file icon based on file type
function getFileIcon(mimeType) {
    if (mimeType.includes('pdf')) return '📄';
    if (mimeType.includes('word') || mimeType.includes('document')) return '📝';
    if (mimeType.includes('excel') || mimeType.includes('spreadsheet')) return '📊';
    if (mimeType.includes('powerpoint') || mimeType.includes('presentation')) return '📽️';
    if (mimeType.includes('zip') || mimeType.includes('rar')) return '📦';
    if (mimeType.includes('image')) return '🖼️';
    return '📎';
}

// Helper: Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
}

// ==================== RESUBMISSION HISTORY SYSTEM ====================

/**
 * Load and display resubmission history
 */
async function loadResubmissionHistory(assignmentId) {
    try {
        const response = await fetch(`/group/${window.groupData.id}/assignments/${assignmentId}/attempts`);
        
        if (!response.ok) {
            throw new Error('Failed to load submission history');
        }

        const data = await response.json();
        
        if (data.attempts && data.attempts.length > 1) {
            // Only show history if there are multiple attempts
            displayResubmissionHistory(data.attempts, data.can_resubmit, data.max_attempts, data.current_attempt_number);
        }
        
    } catch (error) {
        console.error('Error loading resubmission history:', error);
        // Don't show error to user, just fail silently
    }
}

/**
 * Display resubmission history section
 */
function displayResubmissionHistory(attempts, canResubmit, maxAttempts, currentAttemptNumber) {
    const historySection = document.getElementById('resubmission-history-section');
    const attemptsList = document.getElementById('resubmission-attempts-list');
    const eligibilityDiv = document.getElementById('resubmission-eligibility');
    
    if (!historySection || !attemptsList || !eligibilityDiv) {
        return;
    }

    // Show history section
    historySection.style.display = 'block';

    // Render attempts (newest first - they come from backend ordered by attempt_number desc)
    attemptsList.innerHTML = attempts.map((attempt, index) => {
        const isCurrentAttempt = index === 0; // Latest attempt
        const submittedDate = attempt.date_submitted ? 
            new Date(attempt.date_submitted).toLocaleString() : 'Not submitted';
        
        return `
            <div class="attempt-card ${isCurrentAttempt ? 'current-attempt' : ''}">
                <div class="attempt-header">
                    <div class="attempt-number">Attempt ${attempt.attempt_number}</div>
                    <div class="attempt-status">
                        <span class="attempt-badge ${attempt.status}">${attempt.status}</span>
                        ${attempt.is_late ? '<span class="attempt-badge late">Late</span>' : ''}
                    </div>
                </div>
                
                <div class="attempt-details">
                    <div class="attempt-detail-item">
                        <div class="attempt-detail-label">Submitted</div>
                        <div class="attempt-detail-value">${submittedDate}</div>
                    </div>
                    
                    ${attempt.grade !== null ? `
                        <div class="attempt-detail-item">
                            <div class="attempt-detail-label">Grade</div>
                            <div class="attempt-detail-value attempt-grade">
                                ${attempt.grade} / ${currentAssignment.max_points}
                            </div>
                        </div>
                    ` : ''}
                    
                    ${attempt.graded_at ? `
                        <div class="attempt-detail-item">
                            <div class="attempt-detail-label">Graded On</div>
                            <div class="attempt-detail-value">${new Date(attempt.graded_at).toLocaleString()}</div>
                        </div>
                    ` : ''}
                </div>
                
                ${attempt.teacher_feedback ? `
                    <div class="attempt-feedback">
                        <div class="attempt-feedback-label">Teacher Feedback:</div>
                        <div class="attempt-feedback-text">${escapeHtml(attempt.teacher_feedback)}</div>
                    </div>
                ` : ''}
                
                ${attempt.late_penalty_applied && attempt.late_penalty_applied > 0 ? `
                    <div style="margin-top: 8px; padding: 8px; background: #fff3cd; border-radius: 4px; font-size: 0.9em;">
                        <strong>⚠️ Late Penalty:</strong> ${attempt.late_penalty_applied} points deducted
                    </div>
                ` : ''}
            </div>
        `;
    }).join('');

    // Display eligibility message
    if (!currentAssignment.allow_resubmissions) {
        eligibilityDiv.className = 'resubmission-eligibility no-resubmission';
        eligibilityDiv.innerHTML = `
            <div class="eligibility-icon">ℹ️</div>
            <div class="eligibility-message">Resubmissions Not Allowed</div>
            <div class="eligibility-details">
                This assignment does not allow resubmissions. Your latest submission is final.
            </div>
        `;
    } else if (canResubmit) {
        eligibilityDiv.className = 'resubmission-eligibility can-resubmit';
        eligibilityDiv.innerHTML = `
            <div class="eligibility-icon">✅</div>
            <div class="eligibility-message">You Can Resubmit</div>
            <div class="eligibility-details">
                Your previous submission has been graded. You may submit again.
                ${maxAttempts ? `<br>Attempts: ${currentAttemptNumber} / ${maxAttempts}` : ''}
            </div>
            <button type="button" class="resubmit-action-btn" onclick="enableResubmit()">
                Start New Attempt
            </button>
        `;
    } else {
        let reason = 'You cannot resubmit at this time.';
        if (currentAttemptNumber >= maxAttempts) {
            reason = `You have reached the maximum number of attempts (${maxAttempts}).`;
        } else if (attempts[0] && attempts[0].status !== 'graded') {
            reason = 'Your current submission must be graded before you can resubmit.';
        } else if (currentAssignment.isPastDue && !currentAssignment.allow_late_submissions) {
            reason = 'This assignment is past due and late submissions are not allowed.';
        }
        
        eligibilityDiv.className = 'resubmission-eligibility cannot-resubmit';
        eligibilityDiv.innerHTML = `
            <div class="eligibility-icon">❌</div>
            <div class="eligibility-message">Resubmission Not Available</div>
            <div class="eligibility-details">
                ${reason}
                ${maxAttempts ? `<br>Attempts: ${currentAttemptNumber} / ${maxAttempts}` : ''}
            </div>
        `;
    }
}

/**
 * Toggle resubmission history visibility
 */
window.toggleResubmissionHistory = function() {
    const content = document.getElementById('resubmission-history-content');
    const icon = document.getElementById('history-toggle-icon');
    const button = document.querySelector('.btn-collapse');
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.classList.add('expanded');
        button.innerHTML = '<span id="history-toggle-icon" class="expanded">▼</span> Hide History';
    } else {
        content.style.display = 'none';
        icon.classList.remove('expanded');
        button.innerHTML = '<span id="history-toggle-icon">▼</span> Show History';
    }
}

/**
 * Utility function to escape HTML
 */
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/**
 * Load and display teacher comments/feedback for student
 */
async function loadStudentComments(assignmentId, studentId) {
    try {
        const response = await fetch(
            `/group/${window.groupData.id}/assignments/${assignmentId}/submissions/${studentId}/comments`
        );

        if (!response.ok) {
            throw new Error('Failed to fetch comments');
        }

        const data = await response.json();

        if (data.comments && data.comments.length > 0) {
            // Filter out private comments (students shouldn't see those)
            const visibleComments = data.comments.filter(comment => !comment.is_private);
            
            if (visibleComments.length > 0) {
                displayStudentComments(visibleComments);
                document.getElementById('student-comments-section').style.display = 'block';
            } else {
                document.getElementById('student-comments-section').style.display = 'none';
            }
        } else {
            document.getElementById('student-comments-section').style.display = 'none';
        }
    } catch (error) {
        console.error('Error loading comments:', error);
        document.getElementById('student-comments-section').style.display = 'none';
    }
}

/**
 * Display comments for student
 */
function displayStudentComments(comments) {
    const commentsList = document.getElementById('student-comments-list');
    
    if (!comments || comments.length === 0) {
        commentsList.innerHTML = '<p class="no-comments-message">No feedback yet.</p>';
        return;
    }

    commentsList.innerHTML = comments.map(comment => {
        const date = new Date(comment.created_at);
        const formattedDate = date.toLocaleString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        return `
            <div class="comment-item">
                <div class="comment-header">
                    <span class="comment-author">${escapeHtml(comment.user?.name || 'Teacher')}</span>
                    <div class="comment-meta">
                        <span class="comment-date">${formattedDate}</span>
                    </div>
                </div>
                <div class="comment-text">${escapeHtml(comment.comment_text)}</div>
            </div>
        `;
    }).join('');
}

