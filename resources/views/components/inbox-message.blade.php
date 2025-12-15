<style>
    .notification-item {
        display: flex;
        align-items: flex-start;
        padding: 12px 20px;
        border-bottom: 1px solid #f9f9f9;
        position: relative;
        transition: background-color 0.2s ease;
    }

    .notification-item:hover {
        background-color: #f8f9fa;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-item.unread {
        background-color: #f8fbff;
    }

    .notification-avatar-wrapper {
        position: relative;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .notification-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        background-color: #133c06;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 18px;
    }

    .notification-badge {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #133c06;
        display: flex;
        justify-content: center;
        align-items: center;
        border: 2px solid #fff;
        color: #fff;
        font-size: 12px;
        font-weight: bold;
    }

    .notification-badge.group_join_request,
    .notification-badge.group_invitation {
        background-color: #6a8e61;
    }

    .notification-badge.moderator_action {
        background-color: #9c27b0;
    }

    .notification-badge.assignment_post {
        background-color: #ffc107;
        color: #333;
    }

    .notification-badge.group_post_notification {
        background-color: #dc3545;
    }

    .notification-content {
        flex-grow: 1;
        min-width: 0;
    }

    .notification-content p {
        margin: 0 0 8px 0;
        font-size: 17px;
        line-height: 1.4;
        color: #333;
        margin-bottom: 0;
    }

    .notification-content p a {
        color: #133c06;
    }

    .notification-content p a:hover {
        text-decoration: underline;
    }

    .notification-content .username {
        font-weight: 600;
        color: #000;
    }

    .notification-content .action {
        color: #555;
    }

    .notification-content .item-name {
        font-weight: 600;
        color: #000;
    }

    .notification-time {
        font-size: 13px;
        color: #888;
        margin-left: auto;
        white-space: nowrap;
        flex-shrink: 0;
        padding-top: 5px;
        padding-left: 10px;
    }

    .notification-unread-indicator {
        width: 8px;
        height: 8px;
        background-color: #28a745;
        border-radius: 50%;
        margin-left: 10px;
        flex-shrink: 0;
    }

    .notification-actions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .notification-actions button {
        padding: 8px 15px;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.2s ease;
        font-weight: 500;
    }

    .notification-actions .decline-button {
        background-color: #e0e0e0;
        color: #333;
        border: none;
    }

    .notification-actions .decline-button:hover {
        background-color: #d0d0d0;
    }

    .notification-actions .accept-button {
        background-color: #333;
        color: #fff;
        border: none;
    }

    .notification-actions .accept-button:hover {
        background-color: #555;
    }

    .notification-actions .acknowledge-button {
        background-color: #28a745;
        color: #fff;
        border: none;
    }

    .notification-actions .acknowledge-button:hover {
        background-color: #218838;
    }

    .responded-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #133c06;
        font-size: 10px;
        font-weight: 600;
        opacity: 50%;
    }

    .clear-message-btn {
        background: none;
        border: none;
        color: #133c06;
        cursor: pointer;
        font-size: 18px;
        margin-left: 10px;
        transition: color 0.2s ease;
        flex-shrink: 0;
    }
</style>

<div
    class="notification-item {{ $message->responded ? 'read' : 'unread' }}"
    data-message-id="{{ $message->id }}"
    data-message-type="{{ $message->type }}">

    <div class="notification-avatar-wrapper">
        <div class="notification-avatar">
            {{ strtoupper(substr($message->type, 0, 1)) }}
        </div>
        <div class="notification-badge {{ $message->type }}">
            @if($message->type === 'group_join_request' || $message->type === 'group_invitation')
            +
            @elseif($message->type === 'moderator_action')
            ★
            @elseif($message->type === 'assignment_post')
            📝
            @else
            ●
            @endif
        </div>
    </div>

    <div class="notification-content">
        <p>{!! $message->title !!}</p>

        @if(!$message->responded)
        <form action="/inbox/{{ $message->id }}/respond" method="POST"
            class="inbox-message-form"
            data-message-id="{{ $message->id }}">
            @csrf
            <div class="notification-actions">
                @if($message->type === 'group_join_request' || $message->type === 'group_invitation')
                <button type="submit" class="decline-button" data-action="reject">Decline</button>
                <button type="submit" class="accept-button" data-action="accept">Accept</button>
                @else
                <button type="submit" class="acknowledge-button" data-action="acknowledge">Mark as Read</button>
                @endif
            </div>

        </form>
        @else
        <span class="responded-badge">responded</span>
        @endif
    </div>

    <span class="notification-time">{{ $message->created_at->diffForHumans() }}</span>

    <button class="clear-message-btn" data-message-id="{{ $message->id }}" title="Clear message">✕</button>

    @if(!$message->responded)
    <div class="notification-unread-indicator"></div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messageRow = document.querySelector('.notification-item[data-message-id="{{ $message->id }}"]');
        const form = messageRow?.querySelector('.inbox-message-form');

        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const messageType = messageRow.dataset.messageType;
                const messageId = messageRow.dataset.messageId;
                const action = (e.submitter.dataset.action || e.submitter.textContent).toLowerCase();

                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                formData.append('action', action);

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Find ALL instances of this message (in both unread and any clones)
                        const allMessageInstances = document.querySelectorAll(`.notification-item[data-message-id="${messageId}"]`);

                        allMessageInstances.forEach((instance) => {
                            // Update the instance to "read" state
                            instance.classList.remove('unread');
                            instance.classList.add('read');

                            // Remove unread indicator
                            const unreadIndicator = instance.querySelector('.notification-unread-indicator');
                            if (unreadIndicator) {
                                unreadIndicator.remove();
                            }

                            // Replace form/actions with responded badge
                            const formElement = instance.querySelector('.inbox-message-form');
                            const actionsDiv = instance.querySelector('.notification-actions');

                            if (formElement) {
                                formElement.remove();
                            }

                            if (actionsDiv && !instance.querySelector('.responded-badge')) {
                                const respondedBadge = document.createElement('span');
                                respondedBadge.className = 'responded-badge';
                                respondedBadge.textContent = '✓';
                                actionsDiv.parentElement.appendChild(respondedBadge);
                                actionsDiv.remove();
                            }
                        });

                        // Move from unread to read section
                        const unreadSection = document.querySelector('[data-section="unread"]');
                        const readSection = document.querySelector('[data-section="read"]');
                        const unreadMessage = unreadSection?.querySelector(`.notification-item[data-message-id="${messageId}"]`);

                        if (unreadMessage && readSection) {
                            // Create clean clone for read section
                            const messageClone = unreadMessage.cloneNode(true);
                            messageClone.classList.remove('unread');
                            messageClone.classList.add('read');

                            // Ensure no form in clone
                            const cloneForm = messageClone.querySelector('.inbox-message-form');
                            if (cloneForm) {
                                cloneForm.remove();
                            }

                            // Ensure responded badge exists in clone
                            if (!messageClone.querySelector('.responded-badge')) {
                                const content = messageClone.querySelector('.notification-content');
                                const respondedBadge = document.createElement('span');
                                respondedBadge.className = 'responded-badge';
                                respondedBadge.textContent = '✓';
                                content.appendChild(respondedBadge);
                            }

                            // Re-attach clear button event
                            const cloneClearBtn = messageClone.querySelector('.clear-message-btn');
                            if (cloneClearBtn) {
                                cloneClearBtn.addEventListener('click', handleClearMessage);
                            }

                            messageClone.style.opacity = '0';
                            messageClone.style.transform = 'translateX(-20px)';
                            messageClone.style.transition = 'opacity 0.4s ease, transform 0.4s ease';

                            readSection.insertBefore(messageClone, readSection.firstChild);

                            setTimeout(() => {
                                messageClone.style.opacity = '1';
                                messageClone.style.transform = 'translateX(0)';
                            }, 10);

                            // Remove from unread section with animation
                            unreadMessage.style.transition = 'opacity 0.3s ease, transform 0.3s ease, max-height 0.3s ease';
                            unreadMessage.style.opacity = '0';
                            unreadMessage.style.transform = 'translateX(-30px)';
                            unreadMessage.style.maxHeight = '0';
                            unreadMessage.style.paddingTop = '0';
                            unreadMessage.style.paddingBottom = '0';

                            setTimeout(() => {
                                unreadMessage.remove();
                            }, 300);
                        }
                    } else {
                        alert(data.message || 'An error occurred');
                    }
                } catch (error) {
                    console.error('Error: ', error);
                    alert('Network error. Please try again.');
                }
            });
        }

        // Handle clear message button
        function handleClearMessage() {
            const messageId = this.dataset.messageId;

            if (confirm('Are you sure you want to clear this message?')) {
                // Find and remove ALL instances of this message
                const allMessageInstances = document.querySelectorAll(`.notification-item[data-message-id="${messageId}"]`);

                allMessageInstances.forEach((instance) => {
                    instance.style.transition = 'opacity 0.3s ease, transform 0.3s ease, max-height 0.3s ease';
                    instance.style.opacity = '0';
                    instance.style.transform = 'translateX(-30px)';
                    instance.style.maxHeight = '0';
                    instance.style.paddingTop = '0';
                    instance.style.paddingBottom = '0';

                    setTimeout(() => {
                        instance.remove();
                    }, 300);
                });
            }
        }

        const clearBtn = messageRow?.querySelector('.clear-message-btn');
        if (clearBtn) {
            clearBtn.addEventListener('click', handleClearMessage);
        }
    });
</script>