<style>
    .create-post-form {
        width: 100%;
        margin: 0 0 1.5rem 0;
        padding: 0;
    }

    .create-post-form form {
        width: 100%;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        border: 1px solid #e8e8e8;
    }

    .create-post-form input,
    .create-post-form textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e8e8e8;
        border-radius: 10px;
        font-size: 1rem;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        margin-bottom: 0;
    }

    .create-post-form button {
        width: 100%;
        background-color: #2d4a2b;
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 500;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        cursor: pointer;
        margin-top: 0;
    }

    .create-post-form button:hover {
        background-color: #133c06;
    }

    .create-post-form input:focus,
    .create-post-form textarea:focus {
        outline: none;
        border-color: rgba(45, 74, 43, 0.4);
        box-shadow: 0 0 0 3px rgba(45, 74, 43, 0.1);
    }

    .create-post-form textarea {
        min-height: 120px;
        resize: vertical;
    }
</style>
<div class="create-post-form" id='create-post-form'>
    <form action="/create-post/{{ $group->id ?? '' }}" method='POST'>
        @csrf
        <input type="text" name="create-post-title" id="create-post-title" placeholder="What's on your mind?" required>
        <textarea name="create-post-content" id="create-post-content" placeholder="Share your thoughts" style="display:none;" required></textarea>
        <button type="submit" id="create-post-submit" style="display:none;">Post</button>
    </form>
</div>
<script>
    // VARIABLES
    const createPostForm = document.querySelector('#create-post-form');
    const createPostTitle = createPostForm.querySelector('#create-post-title');
    const createPostContent = createPostForm.querySelector('#create-post-content');
    const createPostSubmit = createPostForm.querySelector('#create-post-submit');
    // LOGIC
    createPostForm.addEventListener('click', () => {
        createPostContent.style.display = 'block';
        createPostSubmit.style.display = 'block';
    });
    document.addEventListener('click', (e) => {
        if (!createPostForm.contains(e.target) && createPostTitle.value === '' && createPostContent.value === '') {
            createPostContent.style.display = 'none';
            createPostSubmit.style.display = 'none';
        }
    });
</script>