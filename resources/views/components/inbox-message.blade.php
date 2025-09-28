<style>
    .inbox-message-row {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        margin-bottom: 1.5rem;
        padding: 1.5rem;
        transition: all 0.2s ease;
        border-left: 4px solid #4a90e2;
        position: relative;
    }
    
    .inbox-message-row:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .inbox-message-row.unread {
        background: #f8fbff;
        border-left-color: #4a90e2;
    }
    
    .inbox-message-row.read {
        opacity: 0.85;
        border-left-color: #6c757d;
    }
    
    .inbox-message-content {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .inbox-message-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .inbox-message-type {
        background: #4a90e2;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    
    .inbox-message-date {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 500;
    }
    
    .inbox-message-main-container {
        display: flex;
        gap: 2rem;
        align-items: flex-start;
    }
    
    .inbox-message-main {
        flex: 1;
    }
    
    .inbox-message-main h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2d3436;
        margin: 0 0 0.75rem 0;
        line-height: 1.4;
    }
    
    .inbox-message-main h3 a {
        color: #4a90e2;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .inbox-message-main h3 a:hover {
        color: #357abd;
        text-decoration: underline;
    }
    
    .inbox-message-main p {
        font-size: 0.95rem;
        color: #636e72;
        line-height: 1.5;
        margin: 0;
    }
    
    .inbox-message-main p a {
        color: #4a90e2;
        text-decoration: none;
        font-weight: 500;
    }
    
    .inbox-message-main p a:hover {
        color: #357abd;
        text-decoration: underline;
    }
    
    .inbox-message-actions {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        min-width: 100px;
    }
    
    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .action-btn.accept {
        background: #00b894;
        color: #fff;
    }
    
    .action-btn.accept:hover {
        background: #00a085;
    }
    
    .action-btn.reject {
        background: #e17055;
        color: #fff;
    }
    
    .action-btn.reject:hover {
        background: #d63031;
    }
    
    .action-btn.acknowledge {
        background: #4a90e2;
        color: #fff;
    }
    
    .action-btn.acknowledge:hover {
        background: #357abd;
    }
    
    .responded-badge {
        background: #00b894;
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .inbox-message-main-container {
            flex-direction: column;
            gap: 1rem;
        }
        
        .inbox-message-actions {
            flex-direction: row;
            justify-content: flex-start;
            min-width: auto;
        }
        
        .action-btn {
            flex: 1;
            min-width: 80px;
        }
    }
</style>

<div 
    class="inbox-message-row {{ $message->responded ? 'read' : 'unread' }}"
    data-message-id="{{ $message->id }}"
    data-message-type="{{ $message->type }}"
>
    <div class="inbox-message-content">
        <div class="inbox-message-header">
            <span class="inbox-message-type">{{ ucfirst(str_replace('_', ' ', $message->type)) }}</span>
            <span class="inbox-message-date">{{ $message->created_at->format('M d, Y H:i') }}</span>
        </div>
        
        <div class="inbox-message-main-container">
            <div class="inbox-message-main">
                <h3>{!! $message->title !!}</h3>
                <p>{!! $message->body !!}</p>
            </div>
            
            <div class="inbox-message-actions">
                @if(!$message->responded)
                    <form action="/inbox/{{ $message->id }}/respond" method="POST" 
                        class="inbox-message-form"
                        data-message-id="{{ $message->id }}"
                    >
                        @csrf
                        @if($message->type === 'group_join_request' || $message->type === 'group_invitation')
                            <button type="submit" class="action-btn accept">Accept</button>
                            <button type="submit" class="action-btn reject">Reject</button>
                        @elseif($message->type === 'moderator_action')
                            <button type="submit" class="action-btn acknowledge">Acknowledge</button>
                        @endif
                    </form>
                @else
                    <div class="responded-badge">Responded</div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messageRow = document.querySelector('.inbox-message-row[data-message-id="{{ $message->id }}"]');
        const form = messageRow?.querySelector('.inbox-message-form');

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
                        // ADD TO READ MESSAGES
                        const readMessages = document.querySelector('.read-messages');
                        if(readMessages) {
                            const messageClone = messageRow.cloneNode(true);
                            messageClone.classList.remove('unread');
                            messageClone.classList.add('read');
                            
                            const actionsDiv = messageClone.querySelector('.inbox-message-actions');
                            if(actionsDiv) {
                                actionsDiv.innerHTML = '<div class="responded-badge">Responded</div>';
                            }

                            const readHeader = readMessages.querySelector('h2');
                            messageClone.style.opacity = '0';
                            messageClone.style.transform = 'translateX(-30px)';
                            messageClone.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                            
                            if(readHeader) {
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
                        messageRow.style.transition = 'opacity 0.3s ease, transform 0.3s ease, height 0.3s ease';
                        messageRow.style.opacity = '0';
                        messageRow.style.transform = 'translateX(-50px)';
                        messageRow.style.height = '0';
                        messageRow.style.marginBottom = '0';
                        messageRow.style.paddingTop = '0';
                        messageRow.style.paddingBottom = '0';
                        
                        setTimeout(() => {
                            messageRow.remove();
                        }, 300);
                    } else {
                        // Show error in a more elegant way
                        const errorDiv = document.createElement('div');
                        errorDiv.style.cssText = `
                            position: fixed; top: 20px; right: 20px; 
                            background: #e17055; color: white; 
                            padding: 1rem; border-radius: 8px; 
                            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                            z-index: 9999; font-weight: 600;
                        `;
                        errorDiv.textContent = data.message || 'An error occurred';
                        document.body.appendChild(errorDiv);
                        
                        setTimeout(() => errorDiv.remove(), 3000);
                    }
                } catch (error) {
                    console.error('Error: ', error);
                    // Show network error elegantly
                    const errorDiv = document.createElement('div');
                    errorDiv.style.cssText = `
                        position: fixed; top: 20px; right: 20px; 
                        background: #d63031; color: white; 
                        padding: 1rem; border-radius: 8px; 
                        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                        z-index: 9999; font-weight: 600;
                    `;
                    errorDiv.textContent = 'Network error. Please try again.';
                    document.body.appendChild(errorDiv);
                    
                    setTimeout(() => errorDiv.remove(), 3000);
                }
            });
        }
    });
</script>