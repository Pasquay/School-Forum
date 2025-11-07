/**
 * Analytics Dashboard JavaScript
 * Handles loading and displaying assignment analytics with Chart.js
 */

let analyticsChart = null;

/**
 * Load and display analytics for an assignment
 */
async function loadAnalytics(assignmentId) {
    try {
        // Show loading state
        document.getElementById('analytics-loading').style.display = 'block';
        document.getElementById('analytics-no-data').style.display = 'none';
        document.getElementById('analytics-content').style.display = 'none';

        const response = await fetch(`/group/${window.groupData.id}/assignments/${assignmentId}/analytics`);
        
        if (!response.ok) {
            throw new Error('Failed to load analytics');
        }

        const data = await response.json();
        
        // Hide loading
        document.getElementById('analytics-loading').style.display = 'none';

        if (!data.stats || data.stats.total_submissions === 0) {
            // Show no data message
            document.getElementById('analytics-no-data').style.display = 'flex';
            return;
        }

        // Show analytics content
        document.getElementById('analytics-content').style.display = 'block';
        
        // Display statistics
        displayAnalyticsStats(data.stats);
        
        // Display grade distribution chart
        displayGradeDistributionChart(data.stats.distribution);
        
        // Display student grades table
        displayStudentGradesTable(data.submissions, data.stats.max_points);
        
    } catch (error) {
        console.error('Error loading analytics:', error);
        document.getElementById('analytics-loading').style.display = 'none';
        document.getElementById('analytics-no-data').style.display = 'flex';
        document.getElementById('analytics-no-data').innerHTML = `
            <p style="color: #dc3545;">❌ Failed to load analytics. Please try again.</p>
        `;
    }
}

/**
 * Display statistics cards
 */
function displayAnalyticsStats(stats) {
    document.getElementById('stat-total').textContent = stats.total_submissions;
    document.getElementById('stat-average').textContent = stats.average.toFixed(1);
    document.getElementById('stat-median').textContent = stats.median.toFixed(1);
    document.getElementById('stat-stddev').textContent = stats.std_dev.toFixed(1);
    document.getElementById('stat-min').textContent = stats.min;
    document.getElementById('stat-max').textContent = stats.max;
}

/**
 * Display grade distribution chart using Chart.js
 */
function displayGradeDistributionChart(distribution) {
    const ctx = document.getElementById('gradeDistributionChart');
    
    if (!ctx) {
        console.error('Chart canvas not found');
        return;
    }

    // Destroy existing chart if it exists
    if (analyticsChart) {
        analyticsChart.destroy();
    }

    // Create new chart
    analyticsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['A (90-100%)', 'B (80-89%)', 'C (70-79%)', 'D (60-69%)', 'F (<60%)'],
            datasets: [{
                label: 'Number of Students',
                data: [
                    distribution.A || 0,
                    distribution.B || 0,
                    distribution.C || 0,
                    distribution.D || 0,
                    distribution.F || 0
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',    // A - Green
                    'rgba(23, 162, 184, 0.8)',   // B - Cyan
                    'rgba(255, 193, 7, 0.8)',    // C - Yellow
                    'rgba(255, 152, 0, 0.8)',    // D - Orange
                    'rgba(220, 53, 69, 0.8)'     // F - Red
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(23, 162, 184, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(255, 152, 0, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 2,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            const count = context.parsed.y;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((count / total) * 100).toFixed(1) : 0;
                            return `${count} student${count !== 1 ? 's' : ''} (${percentage}%)`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

/**
 * Display student grades table
 */
function displayStudentGradesTable(submissions, maxPoints) {
    const tbody = document.getElementById('student-grades-tbody');
    
    if (!tbody || !submissions || submissions.length === 0) {
        return;
    }

    tbody.innerHTML = submissions.map(submission => {
        const submittedDate = submission.submitted_at ? 
            new Date(submission.submitted_at).toLocaleString() : 'Not submitted';
        
        const statusClass = submission.is_late ? 'late' : 'on-time';
        const statusText = submission.is_late ? 'Late' : 'On Time';
        
        return `
            <tr>
                <td>
                    <strong>${escapeHtml(submission.student_name)}</strong>
                </td>
                <td class="analytics-grade-cell">
                    ${submission.grade} / ${maxPoints}
                </td>
                <td>
                    <span class="analytics-status-badge ${statusClass}">${statusText}</span>
                </td>
                <td class="analytics-date-cell">
                    ${submittedDate}
                </td>
            </tr>
        `;
    }).join('');
}

/**
 * Export analytics data (future enhancement)
 */
function exportAnalytics() {
    // This could export to CSV, PDF, etc.
    alert('Export feature coming soon!');
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

// Export functions for use in other scripts
window.analyticsSystem = {
    loadAnalytics,
    exportAnalytics
};
