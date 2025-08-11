@if ($errors->any())
    <div style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
        <ul style="margin: 0; padding-left: 1rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <style>
        .success-message {
            background-color: #d4edda;
            color: #155724;
            margin-top: -0.5rem;
            text-align: center;
        }    
    </style>
@endif 