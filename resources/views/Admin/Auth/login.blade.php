<body class="account-page">

    <div class="main-wrapper">
        <div class="account-content">
            <div class="login-wrapper">
                <div class="login-content">
                    <div class="login-userset">

                        <div class="login-logo">
                            <img src="{{asset('back/img/logo.png')}}" alt="img">
                        </div>
                        <div class="login-userheading">
                            <h3>Sign In</h3>
                            <h4>Please login to your account</h4>
                        </div>
                        <form id="adminLogin" method="POST" action="{{route('admin.autheticate')}}">
                            @csrf
                            <div class="form-login">
                                <label for="email">Email</label>
                                <div class="form-addons">
                                    <input type="text" name="email" id="email" placeholder="Enter your email address" value="{{ old('email') }}">
                                    <img src="{{asset('back/img/icons/mail.svg')}}" alt="img">
                                </div>
                                <label id="email-error" class="error" for="email"></label>
                                @error('email')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-login">
                                <label for="password">Password</label>
                                <div class="pass-group">
                                    <input type="password" name="password" id="password" class="pass-input" placeholder="Enter your password">
                                    <span class=" toggle-password fas fa-key"></span>
                                </div>
                                <label id="password-error" class="error" for="password"></label>
                                @error('password')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-login">
                                <div class="alreadyuser">
                                    <h4><a href="forgetpassword.html" class="hover-a">Forgot Password?</a></h4>
                                </div>
                            </div>
                            <div class="form-login">
                                <input type="submit" class="btn btn-login" value="Sign In">
                            </div>
                        </form>
                        <div class="signinform text-center">
                            <h4>Don’t have an account? <a href="signup.html" class="hover-a">Sign Up</a></h4>
                        </div>
                        <!-- <div class="form-setlogin">
                            <h4>Or sign up with</h4>
                        </div>
                        <div class="form-sociallink">
                            <ul>
                                <li>
                                    <a href="javascript:void(0);">
                                        <img src="assets/img/icons/google.png" class="me-2" alt="google">
                                        Sign Up using Google
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <img src="assets/img/icons/facebook.png" class="me-2" alt="google">
                                        Sign Up using Facebook
                                    </a>
                                </li>
                            </ul>
                        </div> -->
                    </div>
                </div>
                <div class="login-img">
                    <img src="{{asset('back/img/login.jpg')}}" alt="img">
                </div>
            </div>
        </div>
    </div>
