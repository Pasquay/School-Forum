// Teacher Grading JavaScript

let currentGradingSubmission = null;
let currentGradingAssignment = null;

// Open grading modal (called as openGradeModal from onclick)
window.openGradeModal = async function(studentId, assignmentId) {
    try {
        // Show modal immediately
        const modal = document.getElementById('gradingModal');
        modal.style.display = 'flex';

        // Show loading state
        document.getElementById('grading-loading').style.display = 'block';
        document.getElementById('grading-error').style.display = 'none';
        hideAllGradingContent();

        // Fetch submission data
        const response = await fetch(`/group/${window.groupData.id}/assignments/${assignmentId}/submissions/${studentId}`);
        
        if (!response.ok) {
            throw new Error('Failed to fetch submission');
        }

        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || 'Failed to load submission');
        }

        currentGradingSubmission = data.submission;
        currentGradingAssignment = data.assignment;

        // Display submission
        displayGradingSubmission(data.submission, data.assignment, data.student);

        // Load rubric grading interface if rubrics exist
        await loadAndDisplayRubricGradingInterface(assignmentId, studentId);

        // Load comments for this submission
        await loadSubmissionComments(assignmentId, studentId);

    } catch (error) {
        console.error('Error loading submission:', error);
        document.getElementById('grading-loading').style.display = 'none';
        document.getElementById('grading-error').style.display = 'block';
        document.getElementById('grading-error-message').textContent = error.message;
    }
}

// Load and display rubric grading interface
async function loadAndDisplayRubricGradingInterface(assignmentId, studentId) {
    try {
        const container = document.getElementById('rubric-grading-interface-container');
        
        if (!container || !window.rubricSystem) {
            return; // Rubric system not available
        }

        // Load rubric data
        const rubricData = await window.rubricSystem.loadRubricGradingInterface(assignmentId, studentId);
        
        if (!rubricData || !rubricData.rubrics || rubricData.rubrics.length === 0) {
            // No rubrics defined, hide container
            container.style.display = 'none';
            return;
        }

        // Render rubric grading interface
        window.rubricSystem.renderRubricGradingInterface(
            container,
            rubricData.rubrics,
            rubricData.existingScores
        );
        
        // Show container
        container.style.display = 'block';

        // Update the manual grade input with calculated rubric total
        const calculatedTotal = window.rubricSystem.calculateRubricTotal();
        const gradeInput = document.getElementById('grading-score');
        if (gradeInput) {
            gradeInput.value = calculatedTotal;
        }

    } catch (error) {
        console.error('Error loading rubric grading interface:', error);
        // Don't show error to user, just fail silently and allow manual grading
    }
}

// Close grading modal
window.closeGradingModal = function() {
    document.getElementById('gradingModal').style.display = 'none';
    document.getElementById('grading-form').reset();
    currentGradingSubmission = null;
    currentGradingAssignment = null;
}

// Hide all content sections
function hideAllGradingContent() {
    document.getElementById('grading-text-content').style.display = 'none';
    document.getElementById('grading-file-content').style.display = 'none';
    document.getElementById('grading-link-content').style.display = 'none';
    document.getElementById('grading-quiz-content').style.display = 'none';
}

// Display submission in grading modal
function displayGradingSubmission(submission, assignment, student) {
    // Hide loading
    document.getElementById('grading-loading').style.display = 'none';

    // Set student info
    const avatar = document.getElementById('grading-student-avatar');
    avatar.textContent = student.name.charAt(0).toUpperCase();
    document.getElementById('grading-student-name').textContent = student.name;
    document.getElementById('grading-assignment-name').textContent = assignment.assignment_name;

    // Set submission meta
    const statusBadge = document.getElementById('grading-status-badge');
    statusBadge.textContent = submission.status === 'graded' ? 'Graded' : 'Submitted';
    statusBadge.className = `status-badge ${submission.status}`;
    
    if (submission.is_late) {
        statusBadge.textContent += ' (Late)';
        statusBadge.classList.add('late');
    }

    const submittedDate = submission.date_submitted ? 
        new Date(submission.date_submitted).toLocaleString() : 'Not submitted';
    document.getElementById('grading-submission-date').textContent = `Submitted: ${submittedDate}`;

    // Set max points
    document.getElementById('grading-max-points').textContent = assignment.max_points;

    // Display current grade if exists
    if (submission.grade !== null) {
        // Format both numbers consistently - remove decimals if whole number, show decimals if not
        const formattedGrade = Number(submission.grade) % 1 === 0 ? Math.round(submission.grade) : Number(submission.grade).toFixed(1);
        const formattedMax = Number(assignment.max_points) % 1 === 0 ? Math.round(assignment.max_points) : Number(assignment.max_points).toFixed(1);
        
        let gradeHTML = `<span style="color: var(--color-pakistan-green);">${formattedGrade} / ${formattedMax}</span>`;
        
        // Show late penalty if applied
        if (submission.late_penalty_applied && submission.late_penalty_applied > 0) {
            const penalty = Number(submission.late_penalty_applied) % 1 === 0 ? Math.round(submission.late_penalty_applied) : Number(submission.late_penalty_applied).toFixed(1);
            gradeHTML += `<br><small style="color: #e74c3c;">(${penalty} points deducted for late submission)</small>`;
        }
        
        document.getElementById('grading-current-grade').innerHTML = gradeHTML;
        document.getElementById('grading-score').value = submission.grade;
    } else {
        document.getElementById('grading-current-grade').textContent = 'Not graded yet';
    }

    // Display content based on type
    hideAllGradingContent();

    if (assignment.submission_type === 'text') {
        displayTextSubmission(submission);
    } else if (assignment.submission_type === 'file') {
        displayFileSubmission(submission);
    } else if (assignment.submission_type === 'external_link') {
        displayLinkSubmission(submission);
    } else if (assignment.assignment_type === 'quiz' || assignment.assignment_type === 'exam') {
        displayQuizSubmission(submission, assignment);
    }
    
    // Attach event listener to add feedback button
    const addFeedbackBtn = document.getElementById('add-feedback-btn');
    if (addFeedbackBtn) {
        // Remove any existing listeners by cloning the button
        const newBtn = addFeedbackBtn.cloneNode(true);
        addFeedbackBtn.parentNode.replaceChild(newBtn, addFeedbackBtn);
        
        // Add new event listener
        newBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.addSubmissionComment();
        });
    }
}

// Display text submission
function displayTextSubmission(submission) {
    const container = document.getElementById('grading-text-content');
    const display = document.getElementById('grading-text-display');
    
    display.innerHTML = submission.submission_text || '<em style="color: #999;">No content submitted</em>';
    container.style.display = 'block';
}

// Display file submission
function displayFileSubmission(submission) {
    const container = document.getElementById('grading-file-content');
    
    if (submission.file_path) {
        const fileName = submission.file_path.split('/').pop();
        const fileUrl = `/storage/${submission.file_path}`;
        
        document.getElementById('grading-file-name').textContent = fileName;
        document.getElementById('grading-file-download').href = fileUrl;
        document.getElementById('grading-file-download').download = fileName;
        
        // Hide preview initially
        document.getElementById('grading-file-preview-container').style.display = 'none';
        
        container.style.display = 'block';
    } else {
        container.innerHTML = '<p style="color: #999; padding: 20px;">No file uploaded</p>';
        container.style.display = 'block';
    }
}

// Display link submission
function displayLinkSubmission(submission) {
    const container = document.getElementById('grading-link-content');
    const linkElement = document.getElementById('grading-external-link');
    
    if (submission.external_link) {
        linkElement.href = submission.external_link;
        linkElement.textContent = submission.external_link;
        container.style.display = 'block';
    } else {
        container.innerHTML = '<p style="color: #999; padding: 20px;">No link submitted</p>';
        container.style.display = 'block';
    }
}

// Display quiz submission
function displayQuizSubmission(submission, assignment) {
    const container = document.getElementById('grading-quiz-content');
    const responsesDiv = document.getElementById('grading-quiz-responses');
    
    console.log('Quiz submission data:', submission);
    console.log('Quiz responses:', submission.quiz_responses);
    
    let html = '';
    
    // Check both quiz_responses and quizResponses (camelCase)
    const responses = submission.quiz_responses || submission.quizResponses || [];
    
    if (!responses || responses.length === 0) {
        html = '<p style="color: #999;">No quiz responses submitted</p>';
    } else {
        assignment.quiz_questions.forEach((question, index) => {
            const response = responses.find(r => r.question_id === question.id);
            
            html += `
                <div class="quiz-response-item">
                    <div class="quiz-question-text">
                        Question ${index + 1}: ${question.question_text}
                        <span style="color: #6b7280; font-weight: normal; font-size: 14px;">
                            (${question.points} points)
                        </span>
                    </div>
            `;
            
            if (response) {
                if (question.question_type === 'multiple_choice' || question.question_type === 'true_false') {
                    const selectedOption = question.options.find(o => o.id === response.selected_option_id);
                    const correctOption = question.options.find(o => o.is_correct);
                    
                    html += `
                        <div class="${response.is_correct ? 'quiz-correct-answer' : 'quiz-incorrect-answer'}">
                            <div class="quiz-answer-label">Student's Answer:</div>
                            <div>${selectedOption?.option_text || 'Not answered'}</div>
                        </div>
                    `;
                    
                    if (!response.is_correct && correctOption) {
                        html += `
                            <div class="quiz-correct-answer">
                                <div class="quiz-answer-label">Correct Answer:</div>
                                <div>${correctOption.option_text}</div>
                            </div>
                        `;
                    }
                    
                    html += `
                        <div class="quiz-points-earned">
                            ${response.is_correct ? '✓' : '✗'} 
                            ${response.points_earned || 0} / ${question.points} points
                        </div>
                    `;
                } else {
                    // Short answer or essay
                    html += `
                        <div class="quiz-student-answer">
                            <div class="quiz-answer-label">Student's Answer:</div>
                            <div>${response.text_response || '<em>Not answered</em>'}</div>
                        </div>
                    `;
                    
                    if (response.points_earned !== null) {
                        html += `
                            <div class="quiz-points-earned">
                                ${response.points_earned} / ${question.points} points
                            </div>
                        `;
                    } else {
                        html += `
                            <div style="margin-top: 12px; padding: 10px; background: #fff3cd; border-radius: 6px; font-size: 13px;">
                                ⚠️ <strong>Needs manual grading</strong> - Use the grade form below to assign points
                            </div>
                        `;
                    }
                }
            } else {
                html += '<div style="color: #999; font-style: italic;">Not answered</div>';
            }
            
            html += '</div>';
        });
    }
    
    responsesDiv.innerHTML = html;
    container.style.display = 'block';
}

// Preview file
function previewFile() {
    const submission = currentGradingSubmission;
    if (!submission || !submission.file_path) return;
    
    const previewContainer = document.getElementById('grading-file-preview-container');
    const fileUrl = `/storage/${submission.file_path}`;
    const fileName = submission.file_path.split('/').pop();
    const fileExt = fileName.split('.').pop().toLowerCase();
    
    // Check if file can be previewed
    const previewableTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'txt', 'html'];
    
    if (previewableTypes.includes(fileExt)) {
        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
            previewContainer.innerHTML = `<img src="${fileUrl}" style="max-width: 100%; border-radius: 4px;">`;
        } else if (fileExt === 'pdf') {
            previewContainer.innerHTML = `<iframe src="${fileUrl}" style="width: 100%; height: 500px; border: none; border-radius: 4px;"></iframe>`;
        } else {
            previewContainer.innerHTML = `<iframe src="${fileUrl}" style="width: 100%; height: 500px; border: none; border-radius: 4px;"></iframe>`;
        }
        previewContainer.style.display = 'block';
    } else {
        alert('This file type cannot be previewed. Please download it to view.');
    }
}

// Submit grade
document.addEventListener('DOMContentLoaded', function() {
    const gradingForm = document.getElementById('grading-form');
    
    if (gradingForm) {
        gradingForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!currentGradingSubmission || !currentGradingAssignment) {
                alert('Error: No submission loaded');
                return;
            }
            
            const submitBtn = document.getElementById('submit-grade-btn');
            submitBtn.disabled = true;
            submitBtn.textContent = '💾 Saving...';
            
            try {
                // Check if rubric grading is being used
                const rubricContainer = document.getElementById('rubric-grading-interface-container');
                const isUsingRubric = rubricContainer && rubricContainer.style.display !== 'none' 
                    && rubricContainer.querySelector('.rubric-points-input');
                
                if (isUsingRubric && window.rubricSystem) {
                    // Use rubric grading endpoint
                    const success = await window.rubricSystem.submitRubricGrade(
                        currentGradingAssignment.id,
                        currentGradingSubmission.student_id,
                        '' // No overall feedback field anymore, use comments instead
                    );
                    
                    if (success) {
                        // Reload submissions list
                        if (typeof loadSubmissions === 'function') {
                            await loadSubmissions(currentGradingAssignment.id);
                        }
                        closeGradingModal();
                    }
                } else {
                    // Use regular grading endpoint
                    const formData = new FormData(gradingForm);
                    formData.append('_method', 'POST');
                    
                    const response = await fetch(
                        `/group/${window.groupData.id}/assignments/${currentGradingAssignment.id}/submissions/${currentGradingSubmission.student_id}/grade`,
                        {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: formData
                        }
                    );
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        alert('✅ Grade saved successfully!');
                        
                        // Reload submissions list if on submissions tab
                        if (typeof currentEditAssignment !== 'undefined' && currentEditAssignment && currentGradingAssignment && currentEditAssignment.id === currentGradingAssignment.id) {
                            await loadSubmissions(currentGradingAssignment.id);
                        }
                        
                        closeGradingModal();
                    } else {
                        throw new Error(data.message || 'Failed to save grade');
                    }
                }
            } catch (error) {
                console.error('Error saving grade:', error);
                alert('❌ Failed to save grade: ' + error.message);
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = '💾 Save Grade';
            }
        });
    }
});

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('gradingModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeGradingModal();
            }
        });
    }
});

// ==================== Comment System ====================

// Toggle comments section visibility (now deprecated - feedback always visible)
window.toggleCommentsSection = function() {
    // Feedback section is now always visible by default
    // This function kept for backwards compatibility
    return;
}

// Load comments for a submission
async function loadSubmissionComments(assignmentId, studentId) {
    try {
        const response = await fetch(
            `/group/${window.groupData.id}/assignments/${assignmentId}/submissions/${studentId}/comments`
        );

        if (!response.ok) {
            throw new Error('Failed to fetch comments');
        }

        const data = await response.json();

        // Handle both response formats: {success: true, comments: [...]} or {comments: [...]}
        if (data.comments) {
            displayComments(data.comments);
        } else if (data.success && data.comments) {
            displayComments(data.comments);
        }
    } catch (error) {
        console.error('Error loading comments:', error);
    }
}

// Display comments in the list
function displayComments(comments) {
    const commentsList = document.getElementById('comments-list');
    
    if (!comments || comments.length === 0) {
        commentsList.innerHTML = '<p class="no-comments-message">No comments yet. Be the first to add one!</p>';
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
            <div class="comment-item ${comment.is_private ? 'private' : ''}">
                <div class="comment-header">
                    <span class="comment-author">${escapeHtml(comment.user?.name || 'Unknown')}</span>
                    <div class="comment-meta">
                        ${comment.is_private ? '<span class="comment-private-badge">Private</span>' : ''}
                        <span class="comment-date">${formattedDate}</span>
                    </div>
                </div>
                <div class="comment-text">${escapeHtml(comment.comment_text)}</div>
            </div>
        `;
    }).join('');
}

// Add a new comment
window.addSubmissionComment = async function() {
    const textarea = document.getElementById('new-comment-text');
    const privateCheckbox = document.getElementById('comment-is-private');
    const addButton = document.getElementById('add-feedback-btn');

    const commentText = textarea.value.trim();

    if (!commentText) {
        alert('Please enter a comment before submitting.');
        return;
    }

    if (!currentGradingSubmission || !currentGradingAssignment) {
        alert('Error: Submission information not available.');
        return;
    }

    // Disable button and show loading state
    const originalText = addButton.textContent;
    addButton.disabled = true;
    addButton.textContent = 'Adding...';

    try {
        const response = await fetch(
            `/group/${window.groupData.id}/assignments/${currentGradingAssignment.id}/submissions/${currentGradingSubmission.student_id}/comments`,
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    comment_text: commentText,
                    is_private: privateCheckbox.checked
                })
            }
        );

        if (!response.ok) {
            throw new Error('Failed to add comment');
        }

        const data = await response.json();

        if (data.success) {
            // Clear the textarea and checkbox
            textarea.value = '';
            privateCheckbox.checked = false;

            // Reload comments
            await loadSubmissionComments(currentGradingAssignment.id, currentGradingSubmission.student_id);
        } else {
            throw new Error(data.message || 'Failed to add comment');
        }
    } catch (error) {
        console.error('Error adding comment:', error);
        alert('❌ Failed to add comment: ' + error.message);
    } finally {
        addButton.disabled = false;
        addButton.textContent = originalText;
    }
}

// Helper function to escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
