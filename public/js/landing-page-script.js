/* GSAP ANIMATIONS */

// GSDevTools.create();

gsap.set(".section-subtitle", { opacity: 0 });
gsap.set(".feature-card", { opacity: 0 });


let smoother = ScrollSmoother.create({
    wrapper: "#smooth-wrapper",
    content: "#smooth-content",
    smooth: 2,
    effects: true
});

let tl1 = gsap.timeline({
    scrollTrigger: {
        trigger: ".features",
        start: "top top",
        end: "+=5000",
        pin: true,
        scrub: 4,
        invalidateOnRefresh: true
    },
});

tl1.to(".section-title", {
        scale: 6,
        x: 1500,
        ease: "power1.inOut"
    })
    .to(".section-title", {
        x: -3000,
        ease: "power1.inOut",
        duration: 3
    })
    // .to(".section-title", { opacity: 0,  })
    .to(".section-subtitle", { opacity: 1, duration: 2 })
    .to(".section-subtitle", { opacity: 0, duration: 1 })
    .to(".feature-card", { opacity: 1 });


let tl2 = gsap.timeline({
    scrollTrigger: {
        trigger: ".features2",
        start: "top 80%",
        end: "bottom 20%",
        toggleActions: "play none none reverse"
    },
    repeat: -1
});

tl2.from(".feature-icon.academic", {
        x: -1000,
        rotation: 360,
        duration: 1,
        ease: "power2.out"
    })
    .to(".feature-icon.academic", {
        y: -200,
        duration: 0.7,
        ease: "bounce"
    })
    .fromTo(".box2", {
        scaleY: 0,
        y: -200,
        transformOrigin: "top",
        overflow: "hidden"
    }, {
        scaleX: 2,
        scaleY: 2,
        duration: 1,
        ease: "power2.inOut"
    }, "-=0.3")
    .to(".box2", {
        delay: 2,
        scaleY: 0,
        opacity: 0,
        duration: 0.5,
        transformOrigin: "top",
        ease: "power4.in"
    })
    .to(".feature-icon.academic", {
        y: 0,
        duration: 0.7,
        ease: "bounce"
    }, "-=0.2")
    .to(".feature-icon.academic", {
        x: 1000,
        rotation: 360,
        duration: 1,
        ease: "power1.inOut"
    })
    .set([".feature-icon.academic", ".box2"], { display: "none" })
    .set([".feature-icon.community", ".box3"], { display: "block" })
    .from(".feature-icon.community", {
        x: -1000,
        rotation: 360,
        duration: 1,
        ease: "power2.out"
    })
    .to(".feature-icon.community", {
        y: -200,
        duration: 0.7,
        ease: "bounce"
    })
    .fromTo(".box3", {
        scaleY: 0,
        y: -200,
        transformOrigin: "top",
        overflow: "hidden"
    }, {
        scaleX: 2,
        scaleY: 2,
        duration: 1,
        ease: "power2.inOut"
    }, "-=0.3")
    .to(".box3", {
        delay: 2,
        scaleY: 0,
        opacity: 0,
        duration: 0.5,
        transformOrigin: "top",
        ease: "power4.in"
    })
    .to(".feature-icon.community", {
        y: 0,
        duration: 0.7,
        ease: "bounce"
    }, "-=0.5")
    .to(".feature-icon.community", {
        x: 1000,
        rotation: 360,
        duration: 1,
        ease: "power1.inOut"
    })
    .set([".feature-icon.community", ".box3"], { display: "none" })
    .set([".feature-icon.updates", ".box4"], { display: "block" })
    .from(".feature-icon.updates", {
        x: -1000,
        rotation: 360,
        duration: 1,
        ease: "power2.out"
    })
    .to(".feature-icon.updates", {
        y: -200,
        duration: 0.7,
        ease: "bounce"
    })
    .fromTo(".box4", {
        scaleY: 0,
        y: -200,
        transformOrigin: "top",
        overflow: "hidden"
    }, {
        scaleX: 2,
        scaleY: 2,
        duration: 1,
        ease: "power2.inOut"
    }, "-=0.3")
    .to(".box4", {
        delay: 2,
        scaleY: 0,
        opacity: 0,
        duration: 0.5,
        transformOrigin: "top",
        ease: "power4.in"
    })
    .to(".feature-icon.updates", {
        y: 0,
        duration: 0.7,
        ease: "bounce"
    }, "-=0.5")
    .to(".feature-icon.updates", {
        x: 1000,
        rotation: 360,
        duration: 1,
        ease: "power1.inOut"
    })
    .set([".feature-icon.updates", ".box4"], { display: "none" })
    .set([".feature-icon.academic", ".box2"], { display: "block" });


//Back Up

let backUp = document.querySelector("#backUp");

backUp.addEventListener("click", (e) => {
    e.preventDefault();

    gsap.to(smoother, {
        scrollTop: 0,
        duration: 7,
        ease: "power2.out"
    });
});



// Auto-hide session messages after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const sessionMessages = document.querySelectorAll('.session-message');
    sessionMessages.forEach(message => {
        setTimeout(() => {
            message.classList.add('fade-out');
            setTimeout(() => {
                message.remove();
            }, 300);
        }, 5000);

        message.addEventListener('click', function() {
            this.classList.add('fade-out');
            setTimeout(() => {
                this.remove();
            }, 300);
        });

        let autoHideTimeout;
        let removeTimeout;

        const startAutoHide = () => {
            autoHideTimeout = setTimeout(() => {
                message.classList.add('fade-out');
                removeTimeout = setTimeout(() => {
                    message.remove();
                }, 300);
            }, 5000);
        };

        const cancelAutoHide = () => {
            clearTimeout(autoHideTimeout);
            clearTimeout(removeTimeout);
            message.classList.remove('fade-out');
        };

        message.addEventListener('mouseenter', cancelAutoHide);
        message.addEventListener('mouseleave', startAutoHide);

        startAutoHide();
    });
});

// Modal controls
heroLoginBtn.onclick = function(e) {
    e.preventDefault();
    modal.style.display = 'block';
    // Pause smoother 
    if (smoother) {
        smoother.paused(true);
    }
    document.body.style.overflow = 'hidden';
}

if (closeBtn) {
    closeBtn.onclick = function() {
        modal.style.display = 'none';
        // Resume smoother 
        if (smoother) {
            smoother.paused(false);
        }
        document.body.style.overflow = '';
    }
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = 'none';
        // Resume smoother 
        if (smoother) {
            smoother.paused(false);
        }
        document.body.style.overflow = '';
    }
}

// Sliding form controls
const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const slidingContainer = document.getElementById('slidingContainer');

if (signUpButton && signInButton && slidingContainer) {
    signUpButton.addEventListener('click', () => {
        slidingContainer.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
        slidingContainer.classList.remove("right-panel-active");
    });
}

// Forgot Password controls
const forgotPasswordLink = document.getElementById('forgotPasswordLink');
const forgotPasswordContainer = document.getElementById('forgotPasswordContainer');
const backToLoginLink = document.getElementById('backToLogin');
const signInContainer = document.querySelector('.sign-in-container');
const overlayContainer = document.querySelector('.sliding-overlay-container');
const emailSentMessage = document.getElementById('emailSentMessage');
const backToLoginFromSuccess = document.getElementById('backToLoginFromSuccess');

if (forgotPasswordLink && forgotPasswordContainer && backToLoginLink && signInContainer) {
    forgotPasswordLink.addEventListener('click', (e) => {
        e.preventDefault();
        // Hide the sign-in form and overlay, show forgot password form
        signInContainer.style.display = 'none';
        if (overlayContainer) overlayContainer.style.display = 'none';
        forgotPasswordContainer.classList.add('active');
        // Remove any active panel state to show default styling
        slidingContainer.classList.remove("right-panel-active");
    });

    backToLoginLink.addEventListener('click', (e) => {
        e.preventDefault();
        // Hide forgot password form and show sign-in form and overlay
        forgotPasswordContainer.classList.remove('active');
        signInContainer.style.display = 'block';
        if (overlayContainer) overlayContainer.style.display = 'block';
    });
}

// Handle back to login from success message
if (backToLoginFromSuccess) {
    backToLoginFromSuccess.addEventListener('click', (e) => {
        e.preventDefault();
        // Hide success message and show sign-in form and overlay
        if (emailSentMessage) emailSentMessage.classList.remove('active');
        signInContainer.style.display = 'block';
        if (overlayContainer) overlayContainer.style.display = 'block';
    });
}

// Form validation and utility functions
function clearErrors() {
    const errors = document.querySelectorAll('.form-error.show');
    const errorInputs = document.querySelectorAll('.input-error');

    errors.forEach(error => {
        error.classList.remove('show');
        error.innerHTML = '';
    });

    errorInputs.forEach(input => {
        input.classList.remove('input-error');
    });
}

function showError(inputId, errorId, message) {
    const input = document.getElementById(inputId);
    const errorDiv = document.getElementById(errorId);

    if (input) input.classList.add('input-error');
    if (errorDiv) {
        errorDiv.innerHTML = message;
        errorDiv.classList.add('show');
    }
}

function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function validatePassword(password) {
    const errors = [];
    if (password.length < 8) {
        errors.push('Must be at least 8 characters long');
    }
    if (!/[A-Z]/.test(password)) {
        errors.push('Must contain at least one uppercase letter');
    }
    if (!/[a-z]/.test(password)) {
        errors.push('Must contain at least one lowercase letter');
    }
    if (!/[0-9]/.test(password)) {
        errors.push('Must contain at least one number');
    }
    return errors;
}

// Form submissions with validation and AJAX
document.getElementById('loginForm').onsubmit = function(e) {
    e.preventDefault();
    clearErrors();

    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;

    // Client-side validation
    let hasErrors = false;

    if (!email) {
        showError('loginEmail', 'loginEmailError', 'Email is required');
        hasErrors = true;
    } else if (!validateEmail(email)) {
        showError('loginEmail', 'loginEmailError', 'Please enter a valid email address');
        hasErrors = true;
    }

    if (!password) {
        showError('loginPassword', 'loginPasswordError', 'Password is required');
        hasErrors = true;
    }

    if (hasErrors) return;

    // Show loading state
    submitBtn.textContent = 'Signing In...';
    submitBtn.disabled = true;

    // AJAX request
    fetch('/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                'login-email': email,
                'login-password': password
            })
        })
        .then(response => {
            if (!response.ok) {
                // Handle HTTP errors (401, 422, etc.)
                return response.json().then(data => {
                    throw new Error(data.message || 'Login failed');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect || '/home';
            } else {
                // Show server errors
                console.log('Server response:', data); // Debug log
                if (data.errors) {
                    if (data.errors['login-email']) {
                        showError('loginEmail', 'loginEmailError', data.errors['login-email'][0]);
                    }
                    if (data.errors['login-password']) {
                        showError('loginPassword', 'loginPasswordError', data.errors['login-password'][0]);
                    }
                } else if (data.message) {
                    showError('', 'loginGeneralError', data.message);
                } else {
                    showError('', 'loginGeneralError', 'Login failed. Please try again.');
                }
            }
        })
        .catch(error => {
            console.error('Login error:', error);
            // Show the actual error message from server if available
            showError('', 'loginGeneralError', error.message || 'Network error. Please try again.');
        })
        .finally(() => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
}

document.getElementById('registerForm').onsubmit = function(e) {
    e.preventDefault();
    clearErrors();

    const name = document.getElementById('registerName').value;
    const email = document.getElementById('registerEmail').value;
    const password = document.getElementById('registerPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;

    // Client-side validation
    let hasErrors = false;

    if (!name) {
        showError('registerName', 'registerNameError', 'Full name is required');
        hasErrors = true;
    }

    if (!email) {
        showError('registerEmail', 'registerEmailError', 'Email is required');
        hasErrors = true;
    } else if (!validateEmail(email)) {
        showError('registerEmail', 'registerEmailError', 'Please enter a valid email address');
        hasErrors = true;
    }

    if (!password) {
        showError('registerPassword', 'registerPasswordError', 'Password is required');
        hasErrors = true;
    } else {
        const passwordErrors = validatePassword(password);
        if (passwordErrors.length > 0) {
            showError('registerPassword', 'registerPasswordError',
                '<ul>' + passwordErrors.map(err => '<li>' + err + '</li>').join('') + '</ul>');
            hasErrors = true;
        }
    }

    if (!confirmPassword) {
        showError('confirmPassword', 'confirmPasswordError', 'Please confirm your password');
        hasErrors = true;
    } else if (password !== confirmPassword) {
        showError('confirmPassword', 'confirmPasswordError', 'Passwords do not match');
        hasErrors = true;
    }

    if (hasErrors) return;

    // Show loading state
    submitBtn.textContent = 'Creating Account...';
    submitBtn.disabled = true;

    // AJAX request
    fetch('/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                name: name,
                email: email,
                password: password,
                password_confirmation: confirmPassword
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message and redirect
                document.getElementById('loginModal').style.display = 'none';
                // Create a temporary success message
                const successDiv = document.createElement('div');
                successDiv.className = 'session-message success';
                successDiv.textContent = 'Account created successfully! You can now log in.';
                document.body.appendChild(successDiv);
                setTimeout(() => successDiv.remove(), 3000);

                // Reset the form and switch to sign-in
                this.reset();
                document.getElementById('slidingContainer').classList.remove('right-panel-active');
            } else {
                // Show server errors
                if (data.errors) {
                    if (data.errors.name) {
                        showError('registerName', 'registerNameError', data.errors.name[0]);
                    }
                    if (data.errors.email) {
                        showError('registerEmail', 'registerEmailError', data.errors.email[0]);
                    }
                    if (data.errors.password) {
                        showError('registerPassword', 'registerPasswordError', data.errors.password[0]);
                    }
                } else if (data.message) {
                    showError('', 'registerGeneralError', data.message);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('', 'registerGeneralError', 'Wrong Email or Password!');
        })
        .finally(() => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
}

// Forgot Password form submission
document.getElementById('forgotPasswordForm').onsubmit = function(e) {
    e.preventDefault();

    // Clear any previous errors
    clearErrors();

    // Validate email
    const email = document.getElementById('forgotEmail').value;
    if (!validateEmail(email)) {
        showError('forgotEmail', 'forgotEmailError', 'Please enter a valid university email address.');
        return;
    }

    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Sending...';
    submitBtn.disabled = true;

    // Submit the form via AJAX
    const formData = new FormData(this);
    const formElement = this;

    fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data); // Debug log

            // Reset button state first
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;

            if (data.success) {
                console.log('Success received, switching views...');

                // Get elements fresh
                const forgotContainer = document.getElementById('forgotPasswordContainer');
                const successMessage = document.getElementById('emailSentMessage');

                console.log('Forgot container found:', !!forgotContainer);
                console.log('Success message found:', !!successMessage);

                // Hide forgot password form
                if (forgotContainer) {
                    forgotContainer.classList.remove('active');
                    console.log('Forgot container hidden');
                }

                // Show success message
                if (successMessage) {
                    successMessage.classList.add('active');
                    console.log('Success message shown');
                }

                // Reset form after a small delay
                setTimeout(() => {
                    formElement.reset();
                }, 100);

            } else {
                console.log('Error in response:', data);

                if (data.errors && data.errors.email) {
                    showError('forgotEmail', 'forgotEmailError', data.errors.email[0]);
                } else if (data.message) {
                    showError('forgotEmail', 'forgotEmailError', data.message);
                } else {
                    showError('forgotEmail', 'forgotEmailError', 'An error occurred. Please try again.');
                }
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            showError('forgotEmail', 'forgotEmailError', 'An error occurred. Please try again.');
        });
}

// Google Sign-In callback function
function handleCredentialResponse(response) {
    const responsePayload = decodeJwtResponse(response.credential);

    console.log("ID: " + responsePayload.sub);
    console.log('Full Name: ' + responsePayload.name);
    console.log("Email: " + responsePayload.email);

    // Send the user data to backend
    fetch('/google-login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                name: responsePayload.name,
                email: responsePayload.email
            })
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                return response.json();
            }
        })
        .catch(error => {
            alert('Google login failed.');
            console.error(error);
        });
}

function handleGoogleSignIn() {
    const googleButton = document.querySelector('.g_id_signin');
    if (googleButton) {
        const actualButton = googleButton.querySelector('[role="button"]');
        if (actualButton) {
            actualButton.click();
        } else {
            google.accounts.id.prompt();
        }
    }
}

// 4. Helper function to decode the JWT token from Google.
function decodeJwtResponse(token) {
    var base64Url = token.split('.')[1];
    var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    var jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
    return JSON.parse(jsonPayload);
}

(function() {
    const form = document.getElementById('registerForm');
    const acceptedField = document.getElementById('accepted_terms');
    const backdrop = document.getElementById('tcBackdrop');
    const acceptBtn = document.getElementById('tcAccept');

    if (!form || !acceptedField || !backdrop || !acceptBtn) return;

    const closeBtn = document.getElementById('tcClose');
    const dismissBtn = document.getElementById('tcDismiss');
    const confirmErr = document.getElementById('confirmPasswordError');
    const passInput = document.getElementById('registerPassword');
    const confirmInput = document.getElementById('confirmPassword');

    // Helpers
    function lockScroll(lock) {
        document.documentElement.style.overflow = lock ? 'hidden' : '';
        document.body.style.overflow = lock ? 'hidden' : '';
    }

    function openModal() {
        backdrop.style.display = 'flex';
        lockScroll(true);
    }

    function closeModal() {
        backdrop.style.display = 'none';
        lockScroll(false);
    }

    if (confirmInput) {
        confirmInput.addEventListener('input', () => {
            if (confirmErr) confirmErr.textContent = '';
        });
    }
    if (passInput) {
        passInput.addEventListener('input', () => {
            if (confirmErr) confirmErr.textContent = '';
        });
    }

    let tcShownForThisAttempt = false;

    form.addEventListener('submit', function(e) {

        if (acceptedField.value === '1') return;

        if (tcShownForThisAttempt) return;

        e.preventDefault();

        const nativeValid = form.checkValidity();

        let customValid = true;
        if (passInput && confirmInput) {
            if (confirmInput.value !== passInput.value) {
                customValid = false;
                if (confirmErr) confirmErr.textContent = 'Passwords do not match.';
            }
        }

        if (!nativeValid || !customValid) {
            form.reportValidity();
            return;
        }


        tcShownForThisAttempt = true;
        openModal();
    }, true);


    acceptBtn.addEventListener('click', function() {
        acceptedField.value = '1';
        closeModal();

        requestAnimationFrame(() => form.submit());
    });

    function handleCancel() {
        closeModal();
        tcShownForThisAttempt = false;
    }
    if (closeBtn) closeBtn.addEventListener('click', handleCancel);
    if (dismissBtn) dismissBtn.addEventListener('click', handleCancel);

    backdrop.addEventListener('click', function(e) {
        if (e.target === backdrop) handleCancel();
    });
})();

// Cursor-following 3D effect for feature boxes
document.addEventListener('DOMContentLoaded', function() {
    const boxes = document.querySelectorAll('.box2, .box3, .box4');

    boxes.forEach(box => {
        box.addEventListener('mousemove', function(e) {
            const rect = box.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = (y - centerY) / centerY * -10; // Max 15 degrees
            const rotateY = (x - centerX) / centerX * 10; // Max 15 degrees

            // Use GSAP to set rotation without overriding existing transforms
            gsap.set(box, {
                rotationX: rotateX,
                rotationY: rotateY,
                transformPerspective: 1000
            });
        });

        box.addEventListener('mouseleave', function() {
            // Reset rotation without affecting scale
            gsap.set(box, {
                rotationX: 0,
                rotationY: 0,
                transformPerspective: 1000
            });
        });
    });
});