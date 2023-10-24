@extends('layouts.app')
@section('content')
<div class="container container-tight py-4">
    <div class="text-center mb-4">
        <a href="{{env('FRONTEND_URL')}}" class="navbar-brand navbar-brand-autodark">
            <img src="{{env('BACKEND_URL_IMAGE')}}/LogoPink.jpeg" style="width: 100px; height: 100px; border-radius: 50%;" alt="Tabler" class="navbar-brand-image">
        </a>
    </div>
    <form class="card card-md" action="{{ route('register') }}" method="post" autocomplete="off" novalidate="">
        @csrf
        @method('POST')
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Crear nueva Cuenta</h2>
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Ingresar nombre">
            </div>
            <div class="mb-3">
                <label class="form-label">Correo Electronico</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Ingresar correo">
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <div class="input-group input-group-flat">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Ingresar contraseña" autocomplete="off">
                    <span class="input-group-text">
                  <a href="#" class="link-secondary" data-bs-toggle="tooltip" aria-label="Show password" data-bs-original-title="Show password"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path></svg>
                  </a>
                </span>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-check">
                    <input type="checkbox" class="form-check-input" name="terms">
                    <span class="form-check-label">Aceptas los <a href="./terms-of-service.html" tabindex="-1">terminos y politicas</a>.</span>
                </label>
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-dark w-100">Crear cuenta nueva</button>
            </div>
        </div>
    </form>
    <div class="text-center text-secondary mt-3">
        Ya tienes una cuenta? <a href={{ route('login') }} tabindex="-1">Iniciar Sesión</a>
    </div>
</div>
@endsection
