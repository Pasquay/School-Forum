<style>
    .inbox-message-row {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        margin-bottom: 1rem;
        padding: 1rem 1.25rem;
        transition: background 0.2s;
        border-left: 5px solid #4a90e2;
    }
    .inbox-message-row.unread {
        background: #f5faff;
        border-left-color: #ff9800;
    }
    .inbox-message-row.read {
        opacity: 0.8;
    }
    .inbox-message-content {
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
    }
    
    .inbox-message-actions {
        min-width: 90px;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-start;
        margin-right: 0.5rem;
    }
    .action-btn {
        padding: 0.35rem 1rem;
        border-radius: 6px;
        border: none;
        font-weight: 500;
        font-size: 0.95rem;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
    }
    .action-btn.accept {
        background: #28a745;
        color: #fff;
    }
    .action-btn.accept:hover {
        background: #218838;
    }
    .action-btn.reject {
        background: #dc3545;
        color: #fff;
    }
    .action-btn.reject:hover {
        background: #b71c1c;
    }
    .action-btn.acknowledge {
        background: #4a90e2;
        color: #fff;
    }
    .action-btn.acknowledge:hover {
        background: #357abd;
    }
    .inbox-message-main {
        flex: 1;
    }
    .inbox-message-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    .inbox-message-type {
        font-size: 0.95rem;
        font-weight: 600;
        color: #4a90e2;
        text-transform: capitalize;
    }
    .inbox-message-date {
        font-size: 0.85rem;
        color: #888;
    }
    .inbox-message-body {
        font-size: 1rem;
        color: #222;
        word-break: break-word;
    }
</style>
<div 
    class="inbox-message-row {{ $message->read_at ? 'read' : 'unread' }}"
    data-message-id="{{ $message->id }}"
    data-message-type="{{ $message->type }}"
    class="{{ $message->responded ? 'responded' : 'unresponded' }}"
>
    <div class="inbox-message-content">
        <div class="inbox-message-main">
            <div class="inbox-message-header">
                <span class="inbox-message-type">{{ ucfirst(str_replace('_', ' ', $message->type)) }}</span>
                <span class="inbox-message-date">{{ $message->created_at->format('M d, Y H:i') }}</span>
            </div>
            <div class="inbox-message-body">
                {{ $message->body }}
            </div>
        </div>
        <div class="inbox-message-actions">
            @if(!$message->responded)
                <form action="/inbox/{{ $message->id }}/respond/{{ $message->type}}" method="POST" 
                    class="inbox-message-form"
                    data-message-id="{{ $message->id }}"
                >
                    @csrf
                    @if($message->type === 'group_join_request')
                        <button type="submit" class="action-btn accept">Accept</button>
                        <button type="submit" class="action-btn reject">Reject</button>
                    @elseif($message->type === 'moderator_action')
                        <button type="submit" class="action-btn acknowledge">Acknowledge</button>
                    @endif
                </form>
            @endif
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messageRow = document.querySelector('.inbox-message-row[data-message-id="{{ $message->id }}"]');
        const form = messageRow.querySelector('.inbox-message-form');

        if(form){
            form.addEventListener('submit', async(e) => {
                e.preventDefault();

                const messageType = messageRow.dataset.messageType;
                const messageId = messageRow.dataset.messageId;
                const action = e.submitter.textContent.toLowerCase();
                
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

                    if(data.success){
                        // ADD TO READ
                        const readMessages = document.querySelector('.read-messages');
                        const messageClone = messageRow.cloneNode(true);

                        messageClone.classList.add('unresponded');
                        messageClone.classList.add('responded');
                        
                        const actionsDiv = messageClone.querySelector('.inbox-message-actions');
                        if(actionsDiv) {
                            actionsDiv.innerHTML = '';
                        }

                        if(readMessages){
                            const readHeader = readMessages.querySelector('h2');
                            messageClone.style.opacity = '0';
                            messageClone.style.transform = 'translateX(-50px)';
                            messageClone.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                            
                            if(readHeader){
                                readHeader.insertAdjacentElement('afterend', messageClone);
                            } else {
                                readMessages.appendChild(messageClone);
                            }

                            setTimeout(() => {
                                messageClone.style.opacity = '1';
                                messageClone.style.transform = 'translateX(0)';
                            }, 10);
                        }

                        // REMOVE FROM UNREAD
                        messageRow.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        messageRow.style.opacity = '0';
                        messageRow.style.transform = 'translateX(-100px)';
                        
                        setTimeout(() => {
                            messageRow.remove();
                        }, 300);
                    } else {
                        alert(data.message || 'An error occurred');
                    }
                } catch (error) {
                    console.error('Error: ', error);
                }
            });
        }
    });
</script>