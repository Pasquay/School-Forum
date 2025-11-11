// Quiz Builder JavaScript

let currentQuizAssignment = null;
let quizQuestions = [];
let questionIdCounter = 1;

// Open quiz builder modal
function openQuizBuilder(assignmentId, assignmentName) {
    currentQuizAssignment = assignmentId;
    document.getElementById('quiz-assignment-name').textContent = assignmentName;
    
    const modal = document.getElementById('quizBuilderModal');
    modal.classList.add('show');
    modal.style.display = 'flex';
    
    // Load existing questions
    loadQuizQuestions(assignmentId);
}

// Close quiz builder modal
function closeQuizBuilder() {
    const modal = document.getElementById('quizBuilderModal');
    modal.classList.remove('show');
    modal.style.display = 'none';
    currentQuizAssignment = null;
    quizQuestions = [];
}

// Load existing quiz questions
async function loadQuizQuestions(assignmentId) {
    try {
        const response = await fetch(`/group/${window.groupData.id}/assignments/${assignmentId}/quiz-questions`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to load quiz questions');
        }

        const data = await response.json();
        quizQuestions = data.questions || [];
        
        renderQuizQuestions();
    } catch (error) {
        console.error('Error loading quiz questions:', error);
        quizQuestions = [];
        renderQuizQuestions();
    }
}

// Render quiz questions
function renderQuizQuestions() {
    const container = document.getElementById('quiz-questions-list');
    
    if (quizQuestions.length === 0) {
        container.innerHTML = '<div class="no-questions-message">No questions yet. Click "Add Question" to get started.</div>';
        return;
    }
    
    container.innerHTML = quizQuestions.map((question, index) => {
        return createQuestionHTML(question, index);
    }).join('');
}

// Create question HTML
function createQuestionHTML(question, index) {
    const questionNumber = index + 1;
    const tempId = question.id || `temp-${questionIdCounter++}`;
    
    let optionsHTML = '';
    
    if (question.question_type === 'multiple_choice' || question.question_type === 'true_false') {
        const options = question.options || (question.question_type === 'true_false' ? 
            [{option_text: 'True', is_correct: false}, {option_text: 'False', is_correct: false}] : 
            [{option_text: '', is_correct: false}]);
        
        optionsHTML = `
            <div class="question-options">
                <h5>Answer Options:</h5>
                <div class="options-list" id="options-${tempId}">
                    ${options.map((opt, optIndex) => `
                        <div class="option-item" data-option-index="${optIndex}">
                            <input type="radio" 
                                   name="correct-${tempId}" 
                                   ${opt.is_correct ? 'checked' : ''}
                                   onchange="markCorrectOption(${index}, ${optIndex})">
                            <input type="text" 
                                   value="${opt.option_text || ''}" 
                                   placeholder="Option ${optIndex + 1}"
                                   onchange="updateOptionText(${index}, ${optIndex}, this.value)"
                                   ${question.question_type === 'true_false' ? 'readonly' : ''}>
                            ${question.question_type !== 'true_false' ? `
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeOption(${index}, ${optIndex})">✕</button>
                            ` : ''}
                        </div>
                    `).join('')}
                </div>
                ${question.question_type !== 'true_false' ? `
                    <button type="button" class="btn btn-secondary btn-sm add-option-btn" onclick="addOption(${index})">
                        Add Option
                    </button>
                ` : ''}
            </div>
        `;
    }
    
    return `
        <div class="quiz-question-item" data-question-index="${index}" data-question-id="${tempId}">
            <div class="question-header">
                <span class="question-number">Question ${questionNumber}</span>
                <div class="question-actions">
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteQuestion(${index})">Delete</button>
                </div>
            </div>
            
            <div class="question-body">
                <div class="form-row">
                    <div class="form-group-inline">
                        <label>Question Text *</label>
                        <textarea 
                            onchange="updateQuestion(${index}, 'question_text', this.value)"
                            placeholder="Enter your question here..."
                        >${question.question_text || ''}</textarea>
                    </div>
                    
                    <div class="form-group-inline">
                        <label>Question Type *</label>
                        <select onchange="updateQuestion(${index}, 'question_type', this.value)">
                            <option value="multiple_choice" ${question.question_type === 'multiple_choice' ? 'selected' : ''}>Multiple Choice</option>
                            <option value="true_false" ${question.question_type === 'true_false' ? 'selected' : ''}>True/False</option>
                            <option value="short_answer" ${question.question_type === 'short_answer' ? 'selected' : ''}>Short Answer</option>
                            <option value="essay" ${question.question_type === 'essay' ? 'selected' : ''}>Essay</option>
                        </select>
                    </div>
                    
                    <div class="form-group-inline">
                        <label>Points *</label>
                        <input type="number" 
                               min="0" 
                               step="0.5" 
                               value="${question.points || 1}"
                               onchange="updateQuestion(${index}, 'points', this.value)">
                    </div>
                </div>
                
                ${optionsHTML}
            </div>
        </div>
    `;
}

// Add new question
function addQuizQuestion() {
    const newQuestion = {
        id: null,
        question_text: '',
        question_type: 'multiple_choice',
        points: 1,
        order: quizQuestions.length + 1,
        options: [
            { option_text: '', is_correct: false }
        ]
    };
    
    quizQuestions.push(newQuestion);
    renderQuizQuestions();
}

// Update question field
function updateQuestion(index, field, value) {
    if (quizQuestions[index]) {
        quizQuestions[index][field] = value;
        
        // If type changed, reset options
        if (field === 'question_type') {
            if (value === 'true_false') {
                quizQuestions[index].options = [
                    { option_text: 'True', is_correct: false },
                    { option_text: 'False', is_correct: false }
                ];
            } else if (value === 'multiple_choice' && !quizQuestions[index].options) {
                quizQuestions[index].options = [{ option_text: '', is_correct: false }];
            } else if (value === 'short_answer' || value === 'essay') {
                quizQuestions[index].options = [];
            }
            renderQuizQuestions();
        }
    }
}

// Delete question
function deleteQuestion(index) {
    if (confirm('Are you sure you want to delete this question?')) {
        quizQuestions.splice(index, 1);
        renderQuizQuestions();
    }
}

// Add option to question
function addOption(questionIndex) {
    if (!quizQuestions[questionIndex].options) {
        quizQuestions[questionIndex].options = [];
    }
    quizQuestions[questionIndex].options.push({
        option_text: '',
        is_correct: false
    });
    renderQuizQuestions();
}

// Remove option from question
function removeOption(questionIndex, optionIndex) {
    if (quizQuestions[questionIndex].options && quizQuestions[questionIndex].options.length > 1) {
        quizQuestions[questionIndex].options.splice(optionIndex, 1);
        renderQuizQuestions();
    } else {
        alert('A question must have at least one option.');
    }
}

// Update option text
function updateOptionText(questionIndex, optionIndex, value) {
    if (quizQuestions[questionIndex].options && quizQuestions[questionIndex].options[optionIndex]) {
        quizQuestions[questionIndex].options[optionIndex].option_text = value;
    }
}

// Mark correct option
function markCorrectOption(questionIndex, optionIndex) {
    if (quizQuestions[questionIndex].options) {
        quizQuestions[questionIndex].options.forEach((opt, idx) => {
            opt.is_correct = (idx === optionIndex);
        });
    }
}

// Save quiz builder
async function saveQuizBuilder() {
    try {
        // Validate questions
        for (let i = 0; i < quizQuestions.length; i++) {
            const q = quizQuestions[i];
            
            if (!q.question_text || q.question_text.trim() === '') {
                alert(`Question ${i + 1} is missing question text.`);
                return;
            }
            
            if ((q.question_type === 'multiple_choice' || q.question_type === 'true_false') && 
                (!q.options || q.options.length === 0)) {
                alert(`Question ${i + 1} must have at least one option.`);
                return;
            }
            
            if (q.question_type === 'multiple_choice' || q.question_type === 'true_false') {
                const hasCorrect = q.options.some(opt => opt.is_correct);
                if (!hasCorrect) {
                    alert(`Question ${i + 1} must have a correct answer marked.`);
                    return;
                }
            }
        }
        
        // Save to backend
        const response = await fetch(`/group/${window.groupData.id}/assignments/${currentQuizAssignment}/quiz-questions`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                questions: quizQuestions
            })
        });
        
        if (!response.ok) {
            throw new Error('Failed to save quiz questions');
        }
        
        alert('Quiz questions saved successfully!');
        closeQuizBuilder();
        
        // Reload assignments list
        if (typeof loadAssignments === 'function') {
            loadAssignments();
        }
    } catch (error) {
        console.error('Error saving quiz:', error);
        alert('Failed to save quiz questions. Please try again.');
    }
}
