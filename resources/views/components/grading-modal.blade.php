<!-- Teacher Grading Modal -->
<div id="gradingModal" class="modal" style="display: none;">
    <div class="modal-content settings-modal grading-modal">
        <div class="modal-header">
            <h2 id="grading-modal-title">Grade Submission</h2>
            <button class="close-modal" onclick="closeGradingModal()">&times;</button>
        </div>

        <div class="modal-body">
            <!-- Student Info Header -->
            <div class="grading-student-header">
                <div class="grading-student-info">
                    <div class="grading-student-avatar" id="grading-student-avatar">
                        <!-- Student initial -->
                    </div>
                    <div>
                        <h3 id="grading-student-name">Student Name</h3>
                        <p id="grading-assignment-name">Assignment Name</p>
                    </div>
                </div>
                <div class="grading-submission-meta">
                    <span id="grading-status-badge" class="status-badge">Submitted</span>
                    <div id="grading-submission-date">Submitted: -</div>
                </div>
            </div>

            <!-- Submission Content Section -->
            <div class="grading-content-section">
                <h4>Submission Content</h4>

                <!-- Loading State -->
                <div id="grading-loading" style="text-align: center; padding: 40px;">
                    <p>Loading submission...</p>
                </div>

                <!-- Error State -->
                <div id="grading-error" style="display: none; padding: 20px; background: #fee; border-radius: 6px; color: #c00;">
                    <p id="grading-error-message">Failed to load submission</p>
                </div>

                <!-- Text Submission Display -->
                <div id="grading-text-content" style="display: none;">
                    <div class="submission-text-display" id="grading-text-display">
                        <!-- Text content will be shown here -->
                    </div>
                </div>

                <!-- File Submission Display -->
                <div id="grading-file-content" style="display: none;">
                    <div class="submission-file-display">
                        <div class="file-info">
                            <svg class="file-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                <polyline points="13 2 13 9 20 9"></polyline>
                            </svg>
                            <span id="grading-file-name">file.pdf</span>
                        </div>
                        <div class="file-actions">
                            <a id="grading-file-download" href="#" download class="btn btn-secondary btn-sm">Download</a>
                            <button id="grading-file-preview" class="btn btn-secondary btn-sm" onclick="previewFile()">Preview</button>
                        </div>
                    </div>
                    <div id="grading-file-preview-container" style="display: none; margin-top: 20px;">
                        <!-- File preview will be shown here -->
                    </div>
                </div>

                <!-- External Link Submission Display -->
                <div id="grading-link-content" style="display: none;">
                    <div class="submission-link-display">
                        <p><strong>Submitted Link:</strong></p>
                        <a id="grading-external-link" href="#" target="_blank" rel="noopener">
                            <!-- Link will be shown here -->
                        </a>
                    </div>
                </div>

                <!-- Quiz Submission Display -->
                <div id="grading-quiz-content" style="display: none;">
                    <div class="quiz-responses-display" id="grading-quiz-responses">
                        <!-- Quiz responses will be shown here -->
                    </div>
                </div>
            </div>

            <!-- Rubric Grading Interface (loaded dynamically if rubric exists) -->
            <div id="rubric-grading-interface-container" class="rubric-grading-interface" style="display: none;">
                <!-- Rubric grading interface will be rendered here by rubric-system.js -->
            </div>

            <!-- Grading Form Section -->
            <div class="grading-form-section">
                <form id="grading-form">
                    <div class="grading-grid">
                        <div class="form-group">
                            <label for="grading-score">
                                Grade (out of <span id="grading-max-points">100</span>)
                                <span style="color: #dc3545;">*</span>
                            </label>
                            <input type="number"
                                id="grading-score"
                                name="grade"
                                min="0"
                                step="0.5"
                                required
                                placeholder="Enter grade">
                        </div>

                        <div class="form-group">
                            <label>Current Status</label>
                            <div id="grading-current-grade" style="font-size: 18px; font-weight: 600; color: var(--color-pakistan-green); padding: 10px 0;">
                                Not graded yet
                            </div>
                        </div>
                    </div>

                    <!-- Feedback Section -->
                    <div class="comments-section">
                        <div class="comments-header">
                            <h4>Feedback</h4>
                        </div>

                        <div id="comments-content" class="comments-content">
                            <!-- Existing Comments -->
                            <div id="comments-list" class="comments-list">
                                <!-- Comments will be loaded here -->
                            </div>

                            <!-- Add New Feedback -->
                            <div class="add-comment-section">
                                <label for="new-comment-text">Add Feedback:</label>
                                <textarea id="new-comment-text"
                                    class="comment-textarea"
                                    rows="4"
                                    placeholder="Provide detailed feedback to the student about their submission..."></textarea>
                                <div class="comment-options">
                                    <label class="comment-private-checkbox">
                                        <input type="checkbox" id="comment-is-private">
                                        <span>Private (Teacher notes - not visible to student)</span>
                                    </label>
                                    <button type="button" class="btn btn-primary btn-sm" id="add-feedback-btn">Add Feedback</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grading-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeGradingModal()">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" id="submit-grade-btn">Save Grade</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .grading-modal {
        max-width: 900px;
        max-height: 90vh;
    }

    .modal-body {
        padding: 0;
    }

    .grading-student-header {
        padding: 20px;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .grading-student-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .grading-student-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--color-sage);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 600;
    }

    .grading-student-info h3 {
        margin: 0;
        font-size: 18px;
        color: var(--color-dark-green);
    }

    .grading-student-info p {
        margin: 4px 0 0 0;
        font-size: 14px;
        color: #6b7280;
    }

    .grading-submission-meta {
        text-align: right;
    }

    .grading-submission-date {
        font-size: 14px;
        color: #6b7280;
        margin-top: 8px;
    }

    .grading-content-section {
        padding: 24px;
        border-bottom: 1px solid #e5e7eb;
        max-height: 400px;
        overflow-y: auto;
    }

    .rubric-grading-interface {
        padding: 24px;
        border-bottom: 1px solid #e5e7eb;
    }

    .grading-content-section h4 {
        margin: 0 0 16px 0;
        color: var(--color-pakistan-green);
        font-size: 16px;
    }

    .submission-text-display {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 16px;
        min-height: 100px;
        line-height: 1.6;
        color: #374151;
    }

    .submission-file-display {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 20px;
    }

    .file-info {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        padding: 12px;
        background: #f9fafb;
        border-radius: 6px;
    }

    .file-icon {
        color: var(--color-pakistan-green);
        flex-shrink: 0;
    }

    .file-actions {
        display: flex;
        gap: 10px;
    }

    .file-actions .btn svg {
        display: inline-block;
        vertical-align: middle;
        margin-right: 4px;
    }

    .submission-link-display {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 20px;
    }

    .submission-link-display a {
        color: var(--color-pakistan-green);
        font-weight: 500;
        word-break: break-all;
    }

    .quiz-responses-display {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 16px;
    }

    .quiz-response-item {
        padding: 16px;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 16px;
    }

    .quiz-response-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .quiz-question-text {
        font-weight: 600;
        color: var(--color-dark-green);
        margin-bottom: 12px;
        font-size: 15px;
    }

    .quiz-student-answer {
        background: #f9fafb;
        padding: 12px;
        border-radius: 6px;
        margin: 8px 0;
    }

    .quiz-correct-answer {
        background: #f0fdf4;
        padding: 12px;
        border-radius: 6px;
        margin: 8px 0;
        border-left: 3px solid #16a34a;
    }

    .quiz-incorrect-answer {
        background: #fef2f2;
        padding: 12px;
        border-radius: 6px;
        margin: 8px 0;
        border-left: 3px solid #dc2626;
    }

    .quiz-answer-label {
        font-size: 13px;
        font-weight: 500;
        color: #6b7280;
        margin-bottom: 4px;
    }

    .quiz-points-earned {
        display: inline-block;
        margin-top: 8px;
        padding: 4px 10px;
        background: var(--color-sage);
        color: white;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 500;
    }

    .grading-form-section {
        padding: 24px;
        background: #f9fafb;
    }

    .grading-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 16px;
    }

    .grading-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 20px;
    }

    #grading-file-preview-container {
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 16px;
        background: #f9fafb;
    }

    #grading-file-preview-container iframe {
        width: 100%;
        height: 500px;
        border: none;
        border-radius: 4px;
    }

    /* Comments Section Styles */
    .comments-section {
        margin-top: 20px;
        padding: 20px;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }

    .comments-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .comments-header h4 {
        margin: 0;
        color: var(--color-pakistan-green);
        font-size: 1.1em;
    }

    .btn-toggle-comments {
        background: transparent;
        border: 1px solid #dee2e6;
        padding: 6px 14px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        color: #495057;
        transition: all 0.2s;
    }

    .btn-toggle-comments:hover {
        background: #e9ecef;
        border-color: #adb5bd;
    }

    #comments-toggle-icon {
        display: inline-block;
        transition: transform 0.3s;
    }

    #comments-toggle-icon.expanded {
        transform: rotate(180deg);
    }

    .comments-content {
        margin-top: 15px;
    }

    .comments-list {
        margin-bottom: 20px;
        max-height: 400px;
        overflow-y: auto;
    }

    .comment-item {
        background: #f8f9fa;
        border-left: 3px solid var(--color-sage);
        padding: 12px 16px;
        margin-bottom: 12px;
        border-radius: 4px;
    }

    .comment-item.private {
        border-left-color: #ffc107;
        background: #fff9e6;
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .comment-author {
        font-weight: 600;
        color: var(--color-dark-green);
        font-size: 0.95em;
    }

    .comment-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85em;
        color: #6c757d;
    }

    .comment-private-badge {
        background: #ffc107;
        color: #856404;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 0.8em;
        font-weight: 600;
    }

    .comment-date {
        font-size: 0.85em;
        color: #6c757d;
    }

    .comment-text {
        color: #212529;
        line-height: 1.5;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .no-comments-message {
        text-align: center;
        padding: 20px;
        color: #999;
        font-style: italic;
    }

    .add-comment-section {
        padding: 16px;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 6px;
    }

    .add-comment-section label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #495057;
    }

    .comment-textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 14px;
        font-family: inherit;
        resize: vertical;
    }

    .comment-textarea:focus {
        outline: none;
        border-color: var(--color-pakistan-green);
        box-shadow: 0 0 0 3px rgba(0, 128, 128, 0.1);
    }

    .comment-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
    }

    .comment-private-checkbox {
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        font-size: 0.9em;
        color: #495057;
    }

    .comment-private-checkbox input[type="checkbox"] {
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .grading-grid {
            grid-template-columns: 1fr;
        }

        .grading-student-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .grading-submission-meta {
            text-align: left;
        }

        .comments-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .comment-options {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }
    }
</style>