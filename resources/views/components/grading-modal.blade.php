<!-- Teacher Grading Modal -->
<div id="gradingModal" class="modal" style="display: none;">
    <div class="modal-content settings-modal grading-modal">
        <div class="modal-header">
            <h2 id="grading-modal-title">Grade Submission</h2>
            <button class="close-modal" onclick="closeGradingModal()">&times;</button>
        </div>

        <div class="modal-body grading-modal-body">
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
                                    <button type="button"
                                        class="btn btn-primary btn-sm"
                                        id="add-feedback-btn"
                                        onclick="window.addSubmissionComment && window.addSubmissionComment();">
                                        Add Feedback
                                    </button>
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

<!-- Grading modal inline styles removed; now using consolidated group-styles.css -->