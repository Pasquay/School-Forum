<!-- Quiz Builder Modal -->
<div id="quizBuilderModal" class="modal" style="display: none;">
    <div class="modal-content settings-modal quiz-builder-modal">
        <div class="modal-header">
            <h2>Quiz/Exam Builder: <span id="quiz-assignment-name"></span></h2>
            <button class="close-modal" onclick="closeQuizBuilder()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="quiz-builder-container">
                <!-- Instructions -->
                <div class="quiz-instructions">
                    <p>Add questions to your quiz or exam. Each question can be multiple choice, true/false, short answer, or essay.</p>
                </div>

                <!-- Questions List -->
                <div id="quiz-questions-list" class="quiz-questions-list">
                    <!-- Questions will be added here dynamically -->
                </div>

                <!-- Add Question Button -->
                <div class="add-question-section">
                    <button type="button" class="btn btn-primary" onclick="addQuizQuestion()">
                        ➕ Add Question
                    </button>
                </div>

                <!-- Action Buttons -->
                <div class="form-buttons">
                    <button type="button" onclick="closeQuizBuilder()" class="btn btn-secondary">Close</button>
                    <button type="button" onclick="saveQuizBuilder()" class="btn btn-primary">Save All Changes</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .quiz-builder-modal {
        max-width: 1200px !important;
        width: 95% !important;
    }

    .quiz-builder-container {
        padding: 20px;
    }

    .quiz-instructions {
        background: #e3f2fd;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #1976d2;
    }

    .quiz-instructions p {
        margin: 0;
        color: #1565c0;
    }

    .quiz-questions-list {
        margin-bottom: 20px;
    }

    .quiz-question-item {
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        position: relative;
    }

    .quiz-question-item:hover {
        border-color: #1976d2;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .question-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }

    .question-number {
        font-weight: 600;
        color: #1976d2;
        font-size: 16px;
    }

    .question-actions {
        display: flex;
        gap: 10px;
    }

    .question-body {
        margin-bottom: 15px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 15px;
        margin-bottom: 15px;
    }

    .form-group-inline {
        display: flex;
        flex-direction: column;
    }

    .form-group-inline label {
        margin-bottom: 5px;
        font-weight: 500;
        font-size: 14px;
    }

    .form-group-inline input,
    .form-group-inline select,
    .form-group-inline textarea {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .form-group-inline textarea {
        resize: vertical;
        min-height: 80px;
    }

    .question-options {
        margin-top: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .question-options h5 {
        margin: 0 0 10px 0;
        font-size: 14px;
        color: #666;
    }

    .option-item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        padding: 10px;
        background: white;
        border-radius: 4px;
        border: 1px solid #e0e0e0;
    }

    .option-item input[type="text"] {
        flex: 1;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .option-item input[type="radio"],
    .option-item input[type="checkbox"] {
        width: 20px;
        height: 20px;
    }

    .option-item button {
        padding: 5px 10px;
        font-size: 12px;
    }

    .add-option-btn {
        margin-top: 10px;
    }

    .add-question-section {
        text-align: center;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .no-questions-message {
        text-align: center;
        padding: 40px;
        color: #999;
        font-style: italic;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .quiz-builder-modal {
            width: 98% !important;
        }
    }
</style>