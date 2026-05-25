@extends('layouts.auth')
@section('title', 'Login')
@section('content')
    <main class="w-100 h-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card shadow-lg border-0 rounded-lg mt-5">
                        <div class="card-header">
                            <h3 class="text-center font-weight-light my-4">Reset Password</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <div class="form-floating mb-3">
                                    <input class="form-control @error('email') is-invalid @enderror" id="inputEmail"
                                        type="email" placeholder="name@example.com" name="email"
                                        value="{{ old('email', $email) }}" />
                                    <label for="inputEmail">Email address</label>
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control @error('password') is-invalid @enderror" id="inputPassword"
                                        type="password" placeholder="Password" name="password"
                                        value="{{ old('password') }}" />
                                    <label for="inputPassword">Password</label>
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="inputPassword" type="password" placeholder="Password"
                                        name="password_confirmation" value="{{ old('password') }}" />
                                    <label for="inputPassword">{{ __('Confirm Password') }}</label>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                    <button type="submit" class="btn btn-primary px-5 py-2">Update Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
