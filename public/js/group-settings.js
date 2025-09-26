// Show Group Settings Modal
function showGroupSettingsModal() {
    document.getElementById('groupSettingsModal').style.display = 'flex';
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

// Close Group Settings Modal
function closeGroupSettingsModal() {
    document.getElementById('groupSettingsModal').style.display = 'none';
    document.body.style.overflow = 'auto'; // Restore scrolling
}

// Tab switching functionality
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active class from all tabs and content
            document.querySelectorAll('.tab-btn').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            // Add active class to clicked tab and corresponding content
            btn.classList.add('active');
            const tabId = btn.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Close modal when clicking outside
    document.getElementById('groupSettingsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeGroupSettingsModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeGroupSettingsModal();
        }
    });
});

// Transfer modal functions (if needed later)
function showTransferModal() {
    document.getElementById('transferModal').style.display = 'flex';
}

function hideTransferModal() {
    document.getElementById('transferModal').style.display = 'none';
}