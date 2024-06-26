@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" id="signup-form">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const signupForm = document.getElementById('signup-form');

                                if (signupForm) {
                                    signupForm.addEventListener('submit', function (event) {
                                        event.preventDefault();

                                        const formData = new FormData(signupForm);
                                        const requestOptions = {
                                            method: 'POST',
                                            headers: {
                                                'Accept': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            },
                                            body: formData,
                                        };

                                        fetch(signupForm.action, requestOptions)
                                            .then(response => {
                                                if (response.headers.get('content-type')?.includes('application/json')) {
                                                    return response.json();
                                                } else {
                                                    return response.text().then(text => { throw new Error(text); });
                                                }
                                            })
                                            .then(data => {
                                                if (data.token) {
                                                    localStorage.setItem('authToken', data.token);
                                                    window.location.href = '{{ url('/') }}'; // Redirect to the home page or another page
                                                } else {
                                                    console.error('Signup failed');
                                                    alert('Signup failed');
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Signup error:', error);
                                                alert('An error occurred. Please try again.');
                                            });
                                    });
                                }
                            });
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
