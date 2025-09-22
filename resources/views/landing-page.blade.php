<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Carolink - Connect & Grow</title>
    <!-- Updated font import to use modern serif font Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/landing-page-styles.css') }}">
</head>

<body>
    <!-- Session Messages - Outside smooth-content for proper positioning -->
    @if (session()->has('success'))
    <div class="session-message success">
        {{ session('success') }}
    </div>
    @endif
    @if (session()->has('error'))
    <div class="session-message error">
        {{ session('error') }}
    </div>
    @endif
    @if ($errors->any())
    <div class="session-message error">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div id="smooth-wrapper">
        <div id="smooth-content">
            <script src="https://accounts.google.com/gsi/client" async defer></script>
            <main>
                <section class="hero" id="home">
                    <div class="container">
                        <div class="hero-content">
                            <h1>
                                <span class="letter">C</span><span class="letter">a</span><span class="letter">r</span><span class="letter">o</span><span class="letter">l</span><span class="letter">i</span><span class="letter">n</span><span class="letter">k</span>
                            </h1>
                            <p>Access classes, assignments, announcements, and social features all in one place.</p>
                            <div class="cta-buttons">
                                <a href="#" class="btn btn-primary" id="heroLoginBtn">Get Started</a>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="features" id="features">
                    <div class="container">
                        <h2 class="section-title">Why Choose Carolink?</h2>
                        <p class="section-subtitle">Experience unified academic and social platform designed specifically for your university community.</p>

                    </div>
                </section>


                <section class="features2">
                    <div class="feature-icon academic"></div>
                    <div class="box2">
                        <h3>Academic Integration</h3>
                        <p>Access all your classes, assignments, grades, and academic announcements in one centralized platform. No more juggling multiple systems.</p>
                    </div>

                    <div class="feature-icon community" style="display: none;"></div>
                    <div class="box3" style="display: none;">
                        <h3>Social Community</h3>
                        <p>Connect with classmates, join study groups, and participate in university discussions. Build meaningful relationships within your academic community.</p>
                    </div>

                    <div class="feature-icon updates" style="display: none;"></div>
                    <div class="box4" style="display: none;">
                        <h3>Centralized Updates</h3>
                        <p>Never miss important announcements, deadlines, or university news. Everything you need to know is delivered to your personalized feed.</p>
                    </div>
                </section>

                <!-- OLD AHH CAROUSEL -->
                <!-- <div class="feature-card">
                    <div class="feature-icon academic"></div>
                    <h3>Academic Integration</h3>
                    <p>Access all your classes, assignments, grades, and academic announcements in one centralized platform. No more juggling multiple systems.</p>
                </div> -->

                <!-- <section class="old-carousel">
                    <div class="container">
                        <div class="carousel-container">
                            
                            <div class="feature-card">
                                <div class="feature-icon community"></div>
                                <h3>Social Community</h3>
                                <p>Connect with classmates, join study groups, and participate in university discussions. Build meaningful relationships within your academic community.</p>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon updates"></div>
                                <h3>Centralized Updates</h3>
                                <p>Never miss important announcements, deadlines, or university news. Everything you need to know is delivered to your personalized feed.</p>
                            </div>
                        </div>
                    </div> -->

                <!-- <div class="carousel-indicators">
                        <div class="indicator active" data-slide="0"></div>
                        <div class="indicator" data-slide="1"></div>
                        <div class="indicator" data-slide="2"></div>
                    </div> -->
                </section>
            </main>

            <footer id="contact">
                <div class="container">
                    <div class="footer-content">
                        <p>
                            <a href="#home" id="backUp">Home</a>
                            <a href="#features">Features</a>
                            <a href=" ">Contact</a>
                        </p>
                    </div>
                    <div class="footer-bottom">
                        <p>&copy; 2025 Carolink. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Login Modal - Outside smooth-content for proper viewport positioning -->
    <div id="loginModal" class="modal">

        <!-- Sliding Form Container -->
        <div class="sliding-container" id="slidingContainer">
            <!-- Close Button -->
            <span class="modal-close" onclick="document.getElementById('loginModal').style.display='none'">&times;</span>

            <!-- Sign In Form -->
            <div class="sliding-form-container sign-in-container">
                <form id="loginForm" action="/login" method="post">
                    @csrf
                    <h1>Student Login</h1>

                    <div class="social-container">
                        <button type="button" class="social-btn gsi-material-button" onclick="handleGoogleSignIn()">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" style="width: 20px; height: 20px;">
                                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                            </svg>
                        </button>
                    </div>
                    <span class="form-subtitle">or use your university email</span>

                    <input type="email" name="login-email" id="loginEmail" placeholder="id@usc.edu.ph" required />
                    <div class="form-error" id="loginEmailError"></div>

                    <input type="password" name="login-password" id="loginPassword" placeholder="Password" required />
                    <div class="form-error" id="loginPasswordError"></div>
                    <div class="form-error" id="loginGeneralError"></div>

                    <a href="#" class="forgot-link" id="forgotPasswordLink">Forgot your password?</a>
                    <button type="submit" class="sliding-btn">Sign In</button>
                </form>
            </div>

            <!-- Sign Up Form -->
            <div class="sliding-form-container sign-up-container">
                <form id="registerForm" action="/register" method="post">
                    @csrf
                    <h1>Create Account</h1>

                    <div class="social-container">
                        <button type="button" class="social-btn gsi-material-button" onclick="handleGoogleSignIn()">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" style="width: 20px; height: 20px;">
                                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                            </svg>
                        </button>
                    </div>
                    <span class="form-subtitle">or use your email for registration</span>

                    <input type="text" name="name" id="registerName" placeholder="Full Name" required />
                    <div class="form-error" id="registerNameError"></div>

                    <input type="email" name="email" id="registerEmail" placeholder="id@usc.edu.ph" required />
                    <div class="form-error" id="registerEmailError"></div>

                    <input type="password" id="registerPassword" name="password" placeholder="Password" required />
                    <div class="form-error" id="registerPasswordError"></div>

                    <input type="password" id="confirmPassword" name="password_confirmation" placeholder="Confirm Password" required />
                    <div class="form-error" id="confirmPasswordError"></div>
                    <div class="form-error" id="registerGeneralError"></div>

                    <div class="mt-3" id="tcSection">
                        <input type="hidden" name="accepted_terms" id="accepted_terms" value="0">
                    </div>


                    <button type="submit" class="sliding-btn">Sign Up</button>
                </form>
            </div>

            <!-- Forgot Password Form -->
            <div class="forgot-password-container" id="forgotPasswordContainer">
                <form id="forgotPasswordForm" action="{{ route('password.email') }}" method="post">
                    @csrf
                    <h1>Reset Password</h1>
                    <p>Enter your university email address and we'll send you a link to reset your password.</p>

                    <input type="email" name="email" id="forgotEmail" placeholder="id@usc.edu.ph" required />
                    <div class="form-error" id="forgotEmailError"></div>

                    <div class="forgot-password-buttons">
                        <button type="submit" class="sliding-btn">Send Reset Link</button>
                    </div>

                    <a href="#" class="back-to-login" id="backToLogin">← Back to Login</a>
                </form>
            </div>

            <!-- Email Sent Success Message -->
            <div class="email-sent-message" id="emailSentMessage">
                <div class="success-icon"></div>
                <h1>Email Sent!</h1>
                <p>We've sent a password reset link to your university email address. Please check your inbox and follow the instructions to reset your password.</p>
                <p><strong>Don't see the email?</strong> Check your spam folder or wait a few minutes for it to arrive.</p>

                <div class="forgot-password-buttons">
                    <button type="button" class="sliding-btn" id="backToLoginFromSuccess">Back to Login</button>
                </div>
            </div>

            <!-- Sliding Overlay -->
            <div class="sliding-overlay-container">
                <div class="sliding-overlay">
                    <div class="sliding-overlay-panel overlay-left">
                        <h1>Welcome Back!</h1>
                        <p>To keep connected with your university community, please login with your personal info</p>
                        <button class="sliding-btn ghost" id="signIn">Sign In</button>
                    </div>
                    <div class="sliding-overlay-panel overlay-right">
                        <h1>Hello, Student!</h1>
                        <p>Enter your details and start your journey with Carolink</p>
                        <button class="sliding-btn ghost" id="signUp">Sign Up</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden Google Sign-In Elements -->
        <div id="g_id_onload" style="display: none;"
            data-client_id="495352471012-51n88psp7q90qph631ai7hnvqhsmi3ve.apps.googleusercontent.com"
            data-callback="handleCredentialResponse">
        </div>
        <div class="g_id_signin" style="display: none;"
            data-type="standard"
            data-theme="outline"
            data-size="large"
            data-text="continue_with"
            data-shape="rectangular"
            data-logo_alignment="left">
        </div>
    </div>

    <div id="tcBackdrop" class="tc-backdrop" role="dialog" aria-modal="true" aria-labelledby="tcTitle" style="display:none;">
        <div class="tc-modal">
            <div class="tc-modal-header">
                <h3 id="tcTitle">Terms &amp; Conditions</h3>
                <button type="button" id="tcClose" class="tc-close" aria-label="Close">×</button>
            </div>

            <div class="tc-modal-body">
                <h3>General Privacy Statement</h3>
                <p>
                    The University of San Carlos (USC) values and understands the importance of protecting
                    the privacy of personal information and the confidentiality of data, information and knowledge
                    and is committed to the responsible handling of such. This Privacy Policy Statement explains
                    what information will be gathered and the details how collected information is used without
                    breaching its privacy and confidentiality.
                </p>

                <h3>Scope</h3>
                <p>
                    This Privacy Policy Statement applies to personal information about applicants, prospective
                    applicants, students and employees maintained, used, processed and/or kept in custody by the
                    University of San Carlos.
                </p>

                <h3>Types of Personal Information</h3>
                <ul>
                    <li><strong>Personal information:</strong> Recorded information about a living identifiable or easily identifiable individual.</li>
                    <li><strong>Sensitive information:</strong> Personal information about a living individual's race or ethnicity, political opinions, religious or philosophical beliefs, sexual preferences or practices, criminal record, or memberships details, such as trade union or professional, political or trade associations, genetic data and biometric data.</li>
                    <li><strong>Medical information:</strong> Information about a living or deceased individual's physical, mental or psychological health.</li>
                </ul>

                <h3>1. Information that we collect</h3>
                <p>USC collects the following information:</p>
                <ul>
                    <li><strong>Personal Information:</strong> name, residential address, email address, telephone number, date of birth, passport details (for international applicants) and nationality. USC will also assign you with a unique applicant/student identification number once you apply or are accepted in the University.</li>
                    <li><strong>Education background &amp; employment history:</strong> schools/universities attended, programs and courses completed, dates of completion, past work history, evaluations, previous employers and service information.</li>
                    <li>Information about family or personal affiliations, academic and extracurricular interests relevant to scholarships or student financial aid/assistance.</li>
                    <li>Sensitive personal information such as political affiliations, sexual preferences or practices, criminal record, memberships details, religious or philosophical beliefs, ethnicity.</li>
                    <li>Information concerning health/medical conditions including history, diagnosis, disability and dietary needs.</li>
                </ul>

                <h3>2. How we collect your information</h3>
                <ul>
                    <li>From the information you provide when you contact USC or express interest in studying.</li>
                    <li>When applying and completing application/enrollment forms and procedures.</li>
                    <li>When making inquiries, or communicating via email or USC’s official social media accounts.</li>
                    <li>From interactions as a student, employee, donor, or third party (e.g., references from previous schools, universities, employers).</li>
                </ul>

                <h3>3. From whom we collect information</h3>
                <ul>
                    <li>Prospective and current students</li>
                    <li>Exchange students, professors, job applicants, existing employees</li>
                    <li>Alumni, donors (individual/company), research participants</li>
                    <li>Industry partners, contractors, suppliers, concessionaires</li>
                    <li>Civic organization volunteers, other members of the public who interact with USC</li>
                </ul>

                <h3>4. How we use your information</h3>
                <p>
                    USC uses personal and sensitive personal information to perform and fulfill core functions:
                </p>
                <ul>
                    <li><strong>Educational support:</strong> admission, enrollment, assessments, learning, graduation, counselling, library, medical exams, data analysis.</li>
                    <li><strong>Research:</strong> data analysis, commercialization, administration.</li>
                    <li><strong>Community extension &amp; industry engagement:</strong> alumni relations, industry partnerships, website operations, events, forums.</li>
                    <li><strong>Employment:</strong> recruitment, payroll, employee development, HR activities, medical exams.</li>
                    <li><strong>Operational/infrastructure management:</strong> fees, finance, IT, legal, CCTV, identity management, emergency response.</li>
                    <li><strong>Non-academic matters:</strong> student accommodation, parking, grievances, disciplinary actions.</li>
                    <li><strong>Other purposes permitted by law:</strong> information provision to government agencies and legal entities.</li>
                </ul>

                <h3>5. To whom we share your information</h3>
                <p>
                    USC may share personal data with third parties if required by official business, Data Privacy
                    Act provisions, or legal obligations:
                </p>
                <ul>
                    <li>Employees and administrators</li>
                    <li>Agencies and partners providing healthcare, insurance, scholarships, education, funding, references, professional certification bodies, government agencies, researchers, survey providers</li>
                </ul>

                <h3>6. How we store and protect your information</h3>
                <ul>
                    <li>Stored information is archived under USC ICT Policy with retention and disposal measures.</li>
                    <li>Information destroyed upon request unless legally required otherwise; destruction ensures confidentiality.</li>
                    <li>Personal info stored as hard copies, electronic data, or within USC’s Integrated School Management Information System and related repositories.</li>
                </ul>

                <h3>7. Rights and Access to Information</h3>
                <p>Data subjects have rights to access, update, correct, or request deletion where applicable:</p>
                <ul>
                    <li>Rectify incorrect or incomplete data (<em>Right to Rectification</em>).</li>
                    <li>Request deletion if legal grounds exist (<em>Right to Erasure</em>).</li>
                    <li>Restrict or object to processing (<em>Right to Object/Restrict Processing</em>).</li>
                    <li>Obtain copies in electronic format or request sharing with authorized persons (<em>Right to Portability</em>).</li>
                </ul>

                <h3>8. Review</h3>
                <p>
                    The Board of Trustees shall review this policy every two (2) years and may amend it as
                    necessary.
                </p>

                <h3>9. Effectivity</h3>
                <p>
                    This Policy takes effect upon adoption by the Board of Trustees.
                    <br>Adopted this 11th day of May 2019.
                </p>

            </div>

            <div class="tc-modal-footer">
                <button type="button" id="tcAccept" class="tc-primary">Accept &amp; Continue</button>
                <button type="button" id="tcDismiss" class="tc-secondary">Close</button>
            </div>
        </div>
    </div>

    <!-- GSAP ANIMATIONS -->
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrollTrigger.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrollSmoother.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/GSDevTools.min.js"></script>

</body>

<script src="{{ asset('js/landing-page-script.js') }}"></script>



</html>