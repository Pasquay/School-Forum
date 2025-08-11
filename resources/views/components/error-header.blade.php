@if (session()->has('success'))
    <div class="success-message">
        <div style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center;">
            {{ session('success') }}
        </div>
    </div>
    <style>
        .error-message {
            background-color: #f8d7da; 
            color: #721c24; 
            margin-top: -1rem;
            text-align: center;
        }
        
        .error-message ul {
            margin: 0; 
            padding-left: 1rem;
        }
        
        .error-message p {
            margin: 0;
        }
    </style>
@endif