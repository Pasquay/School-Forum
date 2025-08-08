<style>
    .back-to-top-button {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: #4a90e2;
        border: none;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        overflow: hidden; 
    }
    
    .back-to-top-button img {
        width: 80%; 
        height: 80%; 
        object-fit: cover;
        border-radius: 50%;
        transition: transform 0.2s ease;
    }
    
    .back-to-top-button:hover {
        transform: translateY(0);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    }
    
    .back-to-top-button:hover img {
        transform: scale(1.1);
    }
    
    .back-to-top-button.show {
        opacity: 1;
        transform: translateY(0);
    }
    
    @media (max-width: 768px) {
        .back-to-top-button {
            bottom: 1rem;
            right: 1rem;
            width: 45px;
            height: 45px;
        }
        
        .back-to-top-button img {
            width: 90%;
            height: 90%;
        }
    }
</style>

<button 
    id='back-to-top-button' 
    class='back-to-top-button'
    style='display: none;'
    aria-label='back-to-top'
>
    <img 
        src="{{ asset('storage/icons/back-to-top.png') }}" 
        alt="↑"
    >
</button>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopBtn = document.getElementById('back-to-top-button');
        
        // Back to Top Button Click
        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Show/Hide Back to Top Button on Scroll
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTopBtn.style.display = 'flex';
                setTimeout(() => backToTopBtn.classList.add('show'), 10);
            } else {
                backToTopBtn.classList.remove('show');
                setTimeout(() => {
                    if (!backToTopBtn.classList.contains('show')) {
                        backToTopBtn.style.display = 'none';
                    }
                }, 300);
            }
        });
    });
</script>