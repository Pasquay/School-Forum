<!-- Student Assignment View/Submit Modal -->
<div id="studentAssignmentModal" class="modal" style="display: none;">
    <div class="modal-content settings-modal assignment-modal student-assignment-modal">
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
                    <h4 style="color: var(--color-pakistan-green); margin-bottom: 10px;">Attached Files</h4>
                    <div id="assignment-attachments-list" class="attachments-list">
                        <!-- Attachments will be inserted here -->
                    </div>
                </div>

                <div id="assignment-external-link-container" style="display: none;">
                    <a id="assignment-external-link" href="#" target="_blank" class="external-link-btn btn btn-primary">
                        View External Resource
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
                            <strong>Submitted</strong> on <span id="submitted-date"></span>
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
                            <strong>Note:</strong> Quizzes and exams cannot be retaken once submitted.
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

                    <!-- Closed Message -->
                    <div id="file-submission-container" style="display: none;">
                        <label for="file_upload">Upload File:</label>
                        <div class="file-upload-area" id="file-upload-area">
                            <input type="file" id="file_upload" name="file" accept="*/*">
                            <div class="upload-text">
                                <p>Click to upload or drag and drop</p>
                                <small>Maximum file size: 10MB</small>
                                font-size: 16px;
                                margin-bottom: 15px;
                                line-height: 1.6;
                                }

                                .quiz-option {
                                display: flex;
                                align-items: flex-start;
                                padding: 12px;
                                margin: 8px 0;
                                border: 2px solid rgba(106, 142, 97, 0.2);
                                border-radius: 12px;
                                cursor: pointer;
                                transition: all 0.15s ease;
                                background: #fff;
                                }

                                .quiz-option:hover {
                                background: #f9fafb;
                                border-color: var(--color-sage);
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
                                padding: 16px;
                                background: #fff1f2;
                                color: #b91c1c;
                                border-radius: 12px;
                                border: 1px solid #fecaca;
                                font-weight: 600;
                                }

                                .quiz-ready-box {
                                background: #fff;
                                border: 1px solid rgba(106, 142, 97, 0.35);
                                border-radius: 12px;
                                padding: 24px;
                                margin: 16px 0;
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
                                border-radius: 8px;
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

                                /* Collapse/expand button styling */
                                .btn-collapse {
                                background: transparent;
                                border: 1px solid rgba(106, 142, 97, 0.35);
                                color: var(--color-dark-green);
                                padding: 0.4rem 0.75rem;
                                border-radius: 8px;
                                cursor: pointer;
                                font-weight: 600;
                                }

                                .btn-collapse:hover {
                                border-color: var(--color-sage);
                                }
                                </style>