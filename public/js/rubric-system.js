/**
 * Rubric System JavaScript
 * Handles rubric builder interface and rubric-based grading
 */

let rubricCriteria = [];
let rubricCounter = 0;

/**
 * Load rubrics for an assignment
 */
async function loadRubrics(assignmentId) {
    try {
        const response = await fetch(`/group/${window.groupData.id}/assignments/${assignmentId}/rubrics`);
        
        if (!response.ok) {
            throw new Error('Failed to load rubrics');
        }

        const data = await response.json();
        rubricCriteria = data.rubrics || [];
        
        renderRubricsList();
        updateRubricTotal();
        
        return data;
    } catch (error) {
        console.error('Error loading rubrics:', error);
        showError('Failed to load rubrics');
        return null;
    }
}

/**
 * Add a new rubric criterion
 */
function addRubricCriterion() {
    const criterion = {
        id: null, // null for new criteria
        criteria_name: '',
        description: '',
        max_points: 0,
        order: rubricCriteria.length,
        tempId: ++rubricCounter // Temporary ID for tracking before save
    };
    
    rubricCriteria.push(criterion);
    renderRubricsList();
}

/**
 * Remove a rubric criterion
 */
function removeRubricCriterion(index) {
    if (confirm('Are you sure you want to remove this criterion?')) {
        rubricCriteria.splice(index, 1);
        // Update order numbers
        rubricCriteria.forEach((criterion, idx) => {
            criterion.order = idx;
        });
        renderRubricsList();
        updateRubricTotal();
    }
}

/**
 * Render the rubrics list in the builder
 */
function renderRubricsList() {
    const container = document.getElementById('rubrics-list');
    
    if (!container) {
        console.error('Rubrics list container not found');
        return;
    }

    if (rubricCriteria.length === 0) {
        container.innerHTML = '<div class="no-rubrics-message">No rubric criteria yet. Click "Add Criterion" to get started.</div>';
        document.getElementById('rubric-total').style.display = 'none';
        return;
    }

    container.innerHTML = rubricCriteria.map((criterion, index) => `
        <div class="rubric-criterion" data-index="${index}">
            <div class="rubric-criterion-header">
                <span class="rubric-criterion-number">Criterion ${index + 1}</span>
                <button type="button" class="rubric-remove-btn" onclick="removeRubricCriterion(${index})" title="Remove criterion">×</button>
            </div>
            
            <div class="rubric-field">
                <label>Criterion Name *</label>
                <input type="text" 
                       class="rubric-criteria-name" 
                       data-index="${index}"
                       value="${escapeHtml(criterion.criteria_name)}"
                       placeholder="e.g., Content Quality, Research Depth, Writing Style"
                       required>
            </div>
            
            <div class="rubric-field">
                <label>Description</label>
                <textarea class="rubric-description" 
                          data-index="${index}"
                          placeholder="Describe what this criterion evaluates...">${escapeHtml(criterion.description || '')}</textarea>
            </div>
            
            <div class="rubric-field">
                <label>Maximum Points *</label>
                <input type="number" 
                       class="rubric-max-points" 
                       data-index="${index}"
                       value="${criterion.max_points}"
                       min="0"
                       step="0.5"
                       placeholder="e.g., 10"
                       required>
            </div>
        </div>
    `).join('');

    // Add event listeners for input changes
    container.querySelectorAll('.rubric-criteria-name').forEach(input => {
        input.addEventListener('input', (e) => {
            const index = parseInt(e.target.dataset.index);
            rubricCriteria[index].criteria_name = e.target.value;
        });
    });

    container.querySelectorAll('.rubric-description').forEach(input => {
        input.addEventListener('input', (e) => {
            const index = parseInt(e.target.dataset.index);
            rubricCriteria[index].description = e.target.value;
        });
    });

    container.querySelectorAll('.rubric-max-points').forEach(input => {
        input.addEventListener('input', (e) => {
            const index = parseInt(e.target.dataset.index);
            rubricCriteria[index].max_points = parseFloat(e.target.value) || 0;
            updateRubricTotal();
        });
    });

    document.getElementById('rubric-total').style.display = 'block';
    updateRubricTotal();
}

/**
 * Update the total points display
 */
function updateRubricTotal() {
    const total = rubricCriteria.reduce((sum, criterion) => sum + (parseFloat(criterion.max_points) || 0), 0);
    const totalElement = document.getElementById('rubric-total-points');
    
    if (totalElement) {
        totalElement.textContent = total.toFixed(1);
    }
}

/**
 * Save rubrics to the server
 */
async function saveRubrics() {
    const assignmentId = document.getElementById('edit_assignment_id').value;
    
    if (!assignmentId) {
        showError('Assignment ID not found');
        return;
    }

    // Validate criteria
    for (let i = 0; i < rubricCriteria.length; i++) {
        const criterion = rubricCriteria[i];
        if (!criterion.criteria_name || !criterion.criteria_name.trim()) {
            showError(`Please enter a name for Criterion ${i + 1}`);
            return;
        }
        if (criterion.max_points <= 0) {
            showError(`Please enter a valid max points for Criterion ${i + 1}`);
            return;
        }
    }

    try {
        const response = await fetch(`/group/${window.groupData.id}/assignments/${assignmentId}/rubrics`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                rubrics: rubricCriteria.map(criterion => ({
                    id: criterion.id,
                    criteria_name: criterion.criteria_name,
                    description: criterion.description,
                    max_points: criterion.max_points,
                    order: criterion.order
                }))
            })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Failed to save rubrics');
        }

        showSuccess('Rubric saved successfully!');
        
        // Reload rubrics to get updated IDs
        await loadRubrics(assignmentId);
        
    } catch (error) {
        console.error('Error saving rubrics:', error);
        showError(error.message || 'Failed to save rubrics');
    }
}

/**
 * Load rubric grading interface for a submission
 */
async function loadRubricGradingInterface(assignmentId, studentId) {
    try {
        // Load rubrics
        const rubricData = await loadRubrics(assignmentId);
        
        if (!rubricData || !rubricData.rubrics || rubricData.rubrics.length === 0) {
            return null; // No rubrics defined
        }

        // Load existing rubric scores if any
        const scoresResponse = await fetch(`/group/${window.groupData.id}/assignments/${assignmentId}/submissions/${studentId}`);
        const submissionData = await scoresResponse.json();
        
        const existingScores = submissionData.submission?.rubric_scores || [];
        
        return {
            rubrics: rubricData.rubrics,
            existingScores: existingScores,
            totalPoints: rubricData.total_points
        };
        
    } catch (error) {
        console.error('Error loading rubric grading interface:', error);
        return null;
    }
}

/**
 * Render rubric grading interface in grading modal
 */
function renderRubricGradingInterface(container, rubrics, existingScores = []) {
    if (!container || !rubrics || rubrics.length === 0) {
        return;
    }

    const scoresMap = {};
    existingScores.forEach(score => {
        scoresMap[score.rubric_id] = score;
    });

    container.innerHTML = `
        <div class="rubric-grading-container">
            <div class="rubric-grading-header">
                <h3>Rubric Grading</h3>
            </div>
            
            ${rubrics.map(rubric => {
                const existingScore = scoresMap[rubric.id] || {};
                return `
                    <div class="rubric-score-item">
                        <div class="rubric-score-header">
                            <span class="rubric-score-title">${escapeHtml(rubric.criteria_name)}</span>
                            <span class="rubric-score-max">Max: ${rubric.max_points} pts</span>
                        </div>
                        ${rubric.description ? `<div class="rubric-score-description">${escapeHtml(rubric.description)}</div>` : ''}
                        
                        <div class="rubric-score-input-row">
                            <div class="rubric-score-points">
                                <label class="rubric-score-label">Points Earned</label>
                                <input type="number" 
                                       class="rubric-points-input"
                                       data-rubric-id="${rubric.id}"
                                       data-max="${rubric.max_points}"
                                       value="${existingScore.points_earned || 0}"
                                       min="0"
                                       max="${rubric.max_points}"
                                       step="0.5"
                                       required>
                            </div>
                            <div class="rubric-score-feedback">
                                <label class="rubric-score-label">Feedback (Optional)</label>
                                <textarea class="rubric-feedback-input"
                                          data-rubric-id="${rubric.id}"
                                          placeholder="Provide specific feedback for this criterion...">${existingScore.feedback || ''}</textarea>
                            </div>
                        </div>
                    </div>
                `;
            }).join('')}
            
            <div class="rubric-grading-summary">
                <h4>Calculated Grade</h4>
                <div class="rubric-calculated-grade">
                    <span id="rubric-calculated-total">0</span> / ${rubrics.reduce((sum, r) => sum + parseFloat(r.max_points), 0)} points
                </div>
            </div>
        </div>
    `;

    // Add event listeners to calculate total
    container.querySelectorAll('.rubric-points-input').forEach(input => {
        input.addEventListener('input', calculateRubricTotal);
    });

    // Calculate initial total
    calculateRubricTotal();
}

/**
 * Calculate total points from rubric inputs
 */
function calculateRubricTotal() {
    const inputs = document.querySelectorAll('.rubric-points-input');
    let total = 0;
    
    inputs.forEach(input => {
        const value = parseFloat(input.value) || 0;
        const max = parseFloat(input.dataset.max) || 0;
        
        // Enforce max value
        if (value > max) {
            input.value = max;
            total += max;
        } else {
            total += value;
        }
    });

    const totalElement = document.getElementById('rubric-calculated-total');
    if (totalElement) {
        totalElement.textContent = total.toFixed(1);
    }
    
    // Auto-update the manual grade input field
    const gradeInput = document.getElementById('grading-score');
    if (gradeInput) {
        gradeInput.value = total.toFixed(1);
    }
    
    return total;
}

/**
 * Collect rubric scores for submission
 */
function collectRubricScores() {
    const rubricScores = [];
    const inputs = document.querySelectorAll('.rubric-points-input');
    
    inputs.forEach(input => {
        const rubricId = input.dataset.rubricId;
        const pointsEarned = parseFloat(input.value) || 0;
        const feedbackInput = document.querySelector(`.rubric-feedback-input[data-rubric-id="${rubricId}"]`);
        const feedback = feedbackInput ? feedbackInput.value : '';
        
        rubricScores.push({
            rubric_id: parseInt(rubricId),
            points_earned: pointsEarned,
            feedback: feedback
        });
    });
    
    return rubricScores;
}

/**
 * Submit rubric-based grade
 */
async function submitRubricGrade(assignmentId, studentId, overallFeedback = '') {
    const rubricScores = collectRubricScores();
    
    if (rubricScores.length === 0) {
        showError('No rubric scores to submit');
        return false;
    }

    try {
        const response = await fetch(`/group/${window.groupData.id}/assignments/${assignmentId}/submissions/${studentId}/grade-rubric`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                rubric_scores: rubricScores,
                overall_feedback: overallFeedback
            })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Failed to submit grade');
        }

        showSuccess('Grade submitted successfully!');
        return true;
        
    } catch (error) {
        console.error('Error submitting rubric grade:', error);
        showError(error.message || 'Failed to submit grade');
        return false;
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
 * Show success message
 */
function showSuccess(message) {
    // Try to use existing success message system
    const successHeader = document.querySelector('.success-header');
    if (successHeader) {
        successHeader.textContent = message;
        successHeader.style.display = 'block';
        setTimeout(() => {
            successHeader.style.display = 'none';
        }, 3000);
    } else {
        alert(message);
    }
}

/**
 * Show error message
 */
function showError(message) {
    // Try to use existing error message system
    const errorHeader = document.querySelector('.error-header');
    if (errorHeader) {
        errorHeader.textContent = message;
        errorHeader.style.display = 'block';
        setTimeout(() => {
            errorHeader.style.display = 'none';
        }, 3000);
    } else {
        alert('Error: ' + message);
    }
}

// Export functions for use in other scripts
window.rubricSystem = {
    loadRubrics,
    addRubricCriterion,
    removeRubricCriterion,
    saveRubrics,
    loadRubricGradingInterface,
    renderRubricGradingInterface,
    collectRubricScores,
    submitRubricGrade,
    calculateRubricTotal
};
