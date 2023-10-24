@extends('layouts.app')
@section('content')
<div class="container container-tight py-4">
    @extends('config.log')
    <div class="text-center mb-4">
        <a href="{{env('FRONTEND_URL')}}" class="navbar-brand navbar-brand-autodark">
            <img src="{{env('BACKEND_URL_IMAGE')}}/LogoPink.jpeg" style="width: 100px; height: 100px; border-radius: 50%;" alt="Tabler" class="navbar-brand-image">
        </a>
    </div>
    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-4">Iniciar Sesión</h2>
            <form action="{{ route('login') }}" method="post" autocomplete="off" novalidate="">
                @csrf
                @method('POST')
                <div class="mb-3">
                    <label class="form-label">Correo Electronico</label>
                    <input id="email" name="email" type="email" class="form-control" placeholder="tu@email.com" autocomplete="off" value="{{ old('email') }}">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-2">
                    <label class="form-label">
                        Contraseña
                        <span class="form-label-description">
                    <a href="{{ route('recover-password') }}">Me olvide mi contraseña</a>
                  </span>
                    </label>
                    <div class="input-group input-group-flat">
                        <input id="password" name="password" type="password" class="form-control" placeholder="Tu Contraseña" autocomplete="off" value="{{ old('password') }}">
                        <span class="input-group-text">
                            <a href="#" class="link-secondary" data-bs-toggle="tooltip" aria-label="Toggle password visibility" data-bs-original-title="Toggle password visibility" id="password-toggle">
                                <!-- Updated SVG icon to toggle password visibility -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path>
                                </svg>
                            </a>
                        </span>
                    </div>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <!--<div class="mb-2">
                    <label class="form-check">
                        <input type="checkbox" class="form-check-input">
                        <span class="form-check-label">Remember me on this device</span>
                    </label>
                </div>-->
                <div class="form-footer">
                    <button type="submit" class="btn btn-dark w-100">Iniciar Sesión</button>
                </div>
            </form>
        </div>
        <!--<div class="hr-text">or</div>
        <div class="card-body">
            <div class="row">
                <div class="col"><a href="#" class="btn w-100">-->
                        <!-- Download SVG icon from http://tabler-icons.io/i/brand-github -->
                        <!--<svg xmlns="http://www.w3.org/2000/svg" class="icon text-github" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 19c-4.3 1.4 -4.3 -2.5 -6 -3m12 5v-3.5c0 -1 .1 -1.4 -.5 -2c2.8 -.3 5.5 -1.4 5.5 -6a4.6 4.6 0 0 0 -1.3 -3.2a4.2 4.2 0 0 0 -.1 -3.2s-1.1 -.3 -3.5 1.3a12.3 12.3 0 0 0 -6.2 0c-2.4 -1.6 -3.5 -1.3 -3.5 -1.3a4.2 4.2 0 0 0 -.1 3.2a4.6 4.6 0 0 0 -1.3 3.2c0 4.6 2.7 5.7 5.5 6c-.6 .6 -.6 1.2 -.5 2v3.5"></path></svg>
                                Login with Github
                                </a></div>
                                <div class="col"><a href="#" class="btn w-100">-->
                                <!-- Download SVG icon from http://tabler-icons.io/i/brand-twitter -->
                                <!--<svg xmlns="http://www.w3.org/2000/svg" class="icon text-twitter" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M22 4.01c-1 .49 -1.98 .689 -3 .99c-1.121 -1.265 -2.783 -1.335 -4.38 -.737s-2.643 2.06 -2.62 3.737v1c-3.245 .083 -6.135 -1.395 -8 -4c0 0 -4.182 7.433 4 11c-1.872 1.247 -3.739 2.088 -6 2c3.308 1.803 6.913 2.423 10.034 1.517c3.58 -1.04 6.522 -3.723 7.651 -7.742a13.84 13.84 0 0 0 .497 -3.753c0 -.249 1.51 -2.772 1.818 -4.013z"></path></svg>
                                Login with Twitter
                            </a></div>
                    </div>
                </div>-->
    </div>
    <div class="text-center text-secondary mt-3">
          No tienes cuenta? <a href="{{"register"}}" tabindex="-1">Registrate</a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('password-toggle');

        passwordToggle.addEventListener('click', function (event) {
            event.preventDefault();
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });
    });

    function storeValueInLocalStorage(key, value) {
        localStorage.setItem(key, value);
    }
</script>
@endsection
