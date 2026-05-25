@extends('layouts.auth')
@section('title', 'Register')
@section('content')
    <main class="w-100 h-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="card shadow-lg border-0 rounded-lg mt-5">
                        <div class="card-header">
                            <h3 class="text-center font-weight-light my-4">Create Account</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input class="form-control @error('name') is-invalid @enderror" id="fullName" type="text"
                                        placeholder="Enter full name" name="name" value="{{ old('name') }}" />
                                    <label for="fullName">Full Name</label>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control @error('email') is-invalid @enderror" id="inputEmail"
                                        type="email" placeholder="name@example.com" name="email"
                                        value="{{ old('email') }}" />
                                    <label for="inputEmail">Email address</label>
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 mb-md-0">
                                            <input class="form-control @error('password') is-invalid @enderror"
                                                id="inputPassword" type="password" placeholder="Create a password"
                                                name="password" value="" />
                                            <label for="inputPassword">Password</label>
                                            @error('password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 mb-md-0">
                                            <input class="form-control" id="inputPasswordConfirm" type="password"
                                                placeholder="Confirm password" name="password_confirmation" />
                                            <label for="inputPasswordConfirm">Confirm Password</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 mb-0">
                                    <div class="d-grid"><button class="btn btn-primary btn-block"
                                            type="submit">Create
                                            Account</button></div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center py-3">
                            <div class="small"><a href="{{ route('login') }}">Have an account? Go to login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
