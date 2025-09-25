<div class="inbox-message-row {{ $message->read_at ? 'read' : 'unread' }}">
    <div class="inbox-message-content">
        <div class="inbox-message-actions">
            <form action="#" method="POST">
                @csrf
                @if($message->type === 'group_join_request')
                    <button type="submit" class="action-btn accept">Accept</button>
                    <button type="submit" class="action-btn reject">Reject</button>
                @else if($message->type === 'moderator_action')
                    <button type="submit" class="acknowledge-btn">Acknowledge</button>
                @endif
            </form>
        </div>
        <div class="inbox-message-main">
            <div class="inbox-message-header">
                <span class="inbox-message-type">{{ ucfirst(str_replace('_', ' ', $message->type)) }}</span>
                <span class="inbox-message-date">{{ $message->created_at->format('M d, Y H:i') }}</span>
            </div>
            <div class="inbox-message-body">
                {{ $message->body }}
            </div>
        </div>
    </div>
</div>

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