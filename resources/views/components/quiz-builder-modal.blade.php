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
                <div class="quiz-instructions quiz-builder-divider">
                    <p class="section-note">Add questions to your quiz or exam. Each question can be multiple choice, true/false, short answer, or essay.</p>
                </div>

                <!-- Questions List -->
                <div id="quiz-questions-list" class="quiz-questions-list">
                    <!-- Questions will be added here dynamically -->
                </div>

                <!-- Add Question Button -->
                <div class="add-question-section">
                    <button type="button" class="btn btn-secondary" onclick="addQuizQuestion()">Add Question</button>
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