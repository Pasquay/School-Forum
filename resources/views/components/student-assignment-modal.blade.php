<!-- Student Assignment View/Submit Modal -->
<div id="studentAssignmentModal" class="modal" style="display: none;">
    <div class="modal-content settings-modal assignment-modal">
        <div class="modal-header">
            <h2 id="assignment-modal-title">Assignment</h2>
            <button class="close-modal" onclick="closeStudentAssignmentModal()">&times;</button>
        </div>

        <div class="modal-body">
            <!-- Assignment Info Section -->
            <div class="assignment-info-section">
                <div class="assignment-header-row">
                    <div>
                        <span id="assignment-type-badge" class="assignment-badge"></span>
                        <span id="assignment-status-badge" class="status-badge"></span>
                    </div>
                    <div class="assignment-points">
                        <strong id="assignment-points">0</strong> points
                    </div>
                </div>

                <div class="assignment-dates">
                    <div class="date-item">
                        <strong>Assigned:</strong> <span id="assignment-date-assigned">-</span>
                    </div>
                    <div class="date-item" id="due-date-container">
                        <strong>Due:</strong> <span id="assignment-date-due">-</span>
                        <span id="due-countdown" class="countdown"></span>
                    </div>
                    <div class="date-item" id="close-date-container" style="display: none;">
                        <strong>Closes:</strong> <span id="assignment-close-date">-</span>
                    </div>
                    <!-- Time Limit Timer -->
                    <div class="date-item" id="timer-container" style="display: none;">
                        <strong>Time Remaining:</strong>
                        <span id="timer-display" style="font-size: 1.2em; color: var(--color-pakistan-green); font-weight: bold;">--:--</span>
                    </div>
                </div>

                <!-- Quiz/Exam Ready Prompt -->
                <div id="quiz-ready-prompt" style="display: none;">
                    <div class="quiz-ready-box">
                        <h3>Ready to Begin?</h3>
                        <p id="quiz-time-limit-text">This quiz has a time limit. Once you start, the timer will begin.</p>
                        <p><strong>Important:</strong> Make sure you have a stable internet connection and enough time to complete this assessment.</p>
                        <button type="button" onclick="startTimedQuiz()" class="btn btn-primary" style="margin-top: 15px;">
                            Start Quiz/Exam
                        </button>
                    </div>
                </div>

                <div class="assignment-description" id="assignment-description">
                    <!-- Description will be inserted here -->
                </div>

                <!-- Attachments Section -->
                <div id="assignment-attachments-container" style="display: none; margin-top: 20px;">
                    <h4 style="color: var(--color-pakistan-green); margin-bottom: 10px;">📎 Attached Files</h4>
                    <div id="assignment-attachments-list" class="attachments-list">
                        <!-- Attachments will be inserted here -->
                    </div>
                </div>

                <div id="assignment-external-link-container" style="display: none;">
                    <a id="assignment-external-link" href="#" target="_blank" class="external-link-btn">
                        📎 View External Resource
                    </a>
                </div>
            </div>

            <!-- Submission Section -->
            <div class="submission-section" id="submission-section">
                <h3>Your Submission</h3>

                <!-- Already Submitted View -->
                <div id="submitted-view" style="display: none;">
                    <div class="submitted-info">
                        <div class="success-message">
                            ✅ <strong>Submitted</strong> on <span id="submitted-date"></span>
                            <span id="late-badge" class="late-badge" style="display: none;">LATE</span>
                        </div>

                        <div id="submission-content-display">
                            <!-- Submitted content will be shown here -->
                        </div>

                        <div id="grade-display" style="display: none;">
                            <div class="grade-box">
                                <strong>Grade:</strong> <span id="grade-value"></span> / <span id="grade-max"></span>
                                <div id="teacher-feedback" style="margin-top: 10px;"></div>
                            </div>
                        </div>

                        <button type="button" id="resubmit-btn" class="btn btn-secondary" onclick="enableResubmit()" style="display: none;">
                            Edit Submission
                        </button>

                        <div id="no-retake-message" style="display: none; margin-top: 15px; padding: 12px; background: #fff3cd; border-left: 4px solid #ffc107; color: #856404;">
                            <strong>⚠️ Note:</strong> Quizzes and exams cannot be retaken once submitted.
                        </div>
                    </div>
                </div>

                <!-- Resubmission History Section -->
                <div id="resubmission-history-section" style="display: none;">
                    <div class="resubmission-history-container">
                        <div class="resubmission-header">
                            <h3>Submission History</h3>
                            <button type="button" class="btn-collapse" onclick="toggleResubmissionHistory()">
                                <span id="history-toggle-icon">▼</span> Show History
                            </button>
                        </div>

                        <div id="resubmission-history-content" class="resubmission-history-content" style="display: none;">
                            <div id="resubmission-attempts-list" class="resubmission-attempts-list">
                                <!-- Attempts will be loaded here -->
                            </div>

                            <div id="resubmission-eligibility" class="resubmission-eligibility">
                                <!-- Eligibility message will be shown here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submission Form -->
                <form id="assignment-submission-form" method="POST" enctype="multipart/form-data" style="display: none;">
                    @csrf

                    <!-- Text Submission -->
                    <div id="text-submission-container" style="display: none;">
                        <label for="submission_text">Your Answer:</label>
                        <div id="quill-editor" style="min-height: 300px; background: white;"></div>
                        <textarea id="submission_text" name="submission_text" style="display: none;"></textarea>
                        <div class="word-count">
                            <span id="word-count">0</span> words | <span id="char-count">0</span> characters
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div id="file-submission-container" style="display: none;">
                        <label for="file_upload">Upload File:</label>
                        <div class="file-upload-area" id="file-upload-area">
                            <input type="file" id="file_upload" name="file" accept="*/*">
                            <div class="upload-text">
                                <p>📎 Click to upload or drag and drop</p>
                                <small>Maximum file size: 10MB</small>
                            </div>
                        </div>
                        <div id="file-name-display" style="display: none;"></div>
                    </div>

                    <!-- External Link -->
                    <div id="link-submission-container" style="display: none;">
                        <label for="external_link_input">External Link:</label>
                        <input type="url" id="external_link_input" name="external_link" placeholder="https://example.com">
                        <small>Paste the link to your work (Google Docs, GitHub, etc.)</small>
                    </div>

                    <!-- Quiz -->
                    <div id="quiz-submission-container" style="display: none;">
                        <div id="quiz-questions-container">
                            <!-- Quiz questions will be dynamically inserted here -->
                        </div>
                    </div>

                    <!-- No Submission Required -->
                    <div id="no-submission-container" style="display: none;">
                        <p class="info-message">No submission required for this assignment.</p>
                    </div>

                    <!-- Form Buttons -->
                    <div class="form-buttons" id="submission-buttons">
                        <button type="button" onclick="closeStudentAssignmentModal()" class="btn btn-secondary">Cancel</button>
                        <button type="button" onclick="saveDraft()" class="btn btn-secondary" id="save-draft-btn">Save Draft</button>
                        <button type="submit" class="btn btn-primary" id="submit-btn">Submit Assignment</button>
                    </div>
                </form>

                <!-- Closed Message -->
                <div id="closed-message" style="display: none;">
                    <p class="error-message">⚠️ This assignment is closed and no longer accepting submissions.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .assignment-modal {
        max-width: 1000px !important;
        width: 95% !important;
    }

    .assignment-info-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .assignment-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .assignment-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        margin-right: 8px;
    }

    .assignment-badge.essay {
        background: #e3f2fd;
        color: #1976d2;
    }

    .assignment-badge.quiz {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .assignment-badge.project {
        background: #e8f5e9;
        color: #388e3c;
    }

    .assignment-badge.exam {
        background: #fff3e0;
        color: #f57c00;
    }

    .assignment-badge.homework {
        background: #fce4ec;
        color: #c2185b;
    }

    .assignment-badge.discussion {
        background: #e0f2f1;
        color: #00796b;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.not-submitted {
        background: #ffebee;
        color: #c62828;
    }

    .status-badge.submitted {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-badge.late {
        background: #fff3e0;
        color: #ef6c00;
    }

    .status-badge.graded {
        background: #e3f2fd;
        color: #1565c0;
    }

    .assignment-points {
        font-size: 18px;
        color: #1976d2;
    }

    .assignment-dates {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 10px;
        margin-bottom: 15px;
    }

    .date-item {
        padding: 8px;
        background: white;
        border-radius: 4px;
    }

    .countdown {
        display: inline-block;
        margin-left: 8px;
        font-weight: 600;
    }

    .countdown.urgent {
        color: #d32f2f;
    }

    .countdown.warning {
        color: #f57c00;
    }

    .countdown.normal {
        color: #388e3c;
    }

    .assignment-description {
        margin: 15px 0;
        padding: 15px;
        background: white;
        border-radius: 4px;
        line-height: 1.6;
        min-height: 50px;
        max-height: 300px;
        overflow-y: auto;
    }

    .assignment-description:empty::before {
        content: 'No description provided';
        color: #999;
        font-style: italic;
    }

    .external-link-btn {
        display: inline-block;
        padding: 10px 20px;
        background: #1976d2;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        margin-top: 10px;
    }

    .submission-section {
        padding-top: 20px;
        border-top: 2px solid #e0e0e0;
    }

    .submitted-info {
        background: #f1f8e9;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #689f38;
    }

    .success-message {
        color: #33691e;
        font-size: 16px;
        margin-bottom: 15px;
    }

    .late-badge {
        background: #ff9800;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 12px;
        margin-left: 8px;
    }

    #submission-content-display {
        background: white;
        padding: 15px;
        border-radius: 4px;
        margin: 15px 0;
        white-space: pre-wrap;
    }

    .grade-box {
        background: white;
        padding: 15px;
        border-radius: 4px;
        margin-top: 15px;
        border: 2px solid #4caf50;
    }

    #assignment-submission-form textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-family: inherit;
        resize: vertical;
    }

    .word-count {
        text-align: right;
        color: #666;
        font-size: 14px;
        margin-top: 5px;
    }

    .file-upload-area {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 40px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .file-upload-area:hover {
        border-color: #1976d2;
        background: #f5f5f5;
    }

    .file-upload-area input[type="file"] {
        display: none;
    }

    .upload-text p {
        margin: 0;
        font-size: 16px;
        color: #333;
    }

    .upload-text small {
        color: #666;
    }

    #file-name-display {
        margin-top: 10px;
        padding: 10px;
        background: #e3f2fd;
        border-radius: 4px;
    }

    #link-submission-container input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    #link-submission-container small {
        display: block;
        margin-top: 5px;
        color: #666;
    }

    #quiz-questions-container {
        background: white;
        padding: 20px;
        border-radius: 8px;
    }

    .quiz-question {
        margin-bottom: 30px;
        padding: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
    }

    .question-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .question-number {
        font-weight: 600;
        color: #1976d2;
    }

    .question-points {
        background: #e3f2fd;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 14px;
    }

    .question-text {
        font-size: 16px;
        margin-bottom: 15px;
        line-height: 1.6;
    }

    .quiz-option {
        display: flex;
        align-items: flex-start;
        padding: 12px;
        margin: 8px 0;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .quiz-option:hover {
        background: #f5f5f5;
        border-color: #1976d2;
    }

    .quiz-option input[type="radio"] {
        margin-right: 12px;
        margin-top: 3px;
    }

    .quiz-option label {
        flex: 1;
        cursor: pointer;
    }

    .quiz-text-answer {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        min-height: 100px;
        font-family: inherit;
    }

    .info-message {
        text-align: center;
        padding: 40px;
        color: #666;
        font-size: 16px;
    }

    .error-message {
        text-align: center;
        padding: 20px;
        background: #ffebee;
        color: #c62828;
        border-radius: 4px;
        font-weight: 500;
    }

    .quiz-ready-box {
        background: #f0f7f0;
        border: 2px solid var(--color-pakistan-green);
        border-radius: 8px;
        padding: 30px;
        margin: 20px 0;
        text-align: center;
    }

    .quiz-ready-box h3 {
        color: var(--color-pakistan-green);
        margin-bottom: 15px;
    }

    .quiz-ready-box p {
        margin: 10px 0;
        color: #555;
    }

    #timer-display {
        font-family: 'Courier New', monospace;
        padding: 5px 15px;
        background: #fff;
        border-radius: 4px;
        border: 2px solid var(--color-pakistan-green);
    }

    #timer-display.warning {
        color: #ff9800;
        border-color: #ff9800;
    }

    #timer-display.danger {
        color: #f44336;
        border-color: #f44336;
        animation: pulse 1s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.7;
        }
    }

    .form-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }
</style>