@extends('layouts/fullLayoutMaster')

@section('title', 'Login Page')

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/authentication.css')) }}">
@endsection

@section('content')
    <div class="auth-wrapper auth-basic px-2">
        <div class="auth-inner my-2">
            <!-- Login basic -->
            <div class="card mb-0">
                <div class="card-body">
                    <a href="#" class="brand-logo">                        
                        <h2 class="brand-text text-primary ms-1">MATCHAPRO</h2>
                    </a>

                    <h4 class="card-title mb-1">Welcome to MatchaPro! 👋</h4>
                    <p class="card-text mb-2">Please sign-in to your account and start the adventure</p>

                    <form class="auth-login-form mt-2" action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <div class="mb-1">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username"
                                value="{{ old('username') }}" placeholder="miku.miku" aria-describedby="username"
                                tabindex="1" autofocus />
                        </div>

                        <div class="mb-1">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password_default">Password</label>
                                <a href="{{ url('auth/forgot-password-basic') }}">
                                    <small>Forgot Password?</small>
                                </a>
                            </div>
                            <div class="input-group input-group-merge form-password-toggle">
                                <input type="password" class="form-control form-control-merge" id="password_default"
                                    name="password_default" tabindex="2"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password_default" />
                                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                            </div>
                        </div>
                        <div class="mb-1">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-me" tabindex="3" />
                                <label class="form-check-label" for="remember-me"> Remember Me </label>
                            </div>
                        </div>
                        <button class="btn btn-primary w-100" tabindex="4">Sign in</button>
                        <a href="{{ $authUrl }}" class="btn btn-secondary w-100 mt-1">Sign in with SSO BPS</a>
                    </form>

                    <p class="text-center mt-2">
                        <span>New on our platform?</span>
                        <a href="{{ url('auth/register-basic') }}">
                            <span>Create an account</span>
                        </a>
                    </p>

                    <div class="divider my-2">
                        <div class="divider-text">or</div>
                    </div>

                    <div class="auth-footer-btn d-flex justify-content-center">
                        <a href="#" class="btn btn-facebook">
                            <i data-feather="facebook"></i>
                        </a>
                        <a href="#" class="btn btn-twitter white">
                            <i data-feather="twitter"></i>
                        </a>
                        <a href="#" class="btn btn-google">
                            <i data-feather="mail"></i>
                        </a>
                        <a href="#" class="btn btn-github">
                            <i data-feather="github"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /Login basic -->
        </div>
    </div>
@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset(mix('js/scripts/pages/auth-login.js')) }}"></script>
@endsection
