@extends('layouts.app')
@section('content')
<div class="container container-tight py-4">
    @extends('config.log')
    <div class="text-center mb-4">
        <a href="." class="navbar-brand navbar-brand-autodark">
            <img src="{{env('BACKEND_URL_IMAGE')}}/LogoPink.jpeg" style="width: 100px; height: 100px; border-radius: 50%;" alt="Tabler" class="navbar-brand-image">
        </a>
    </div>
    <form class="card card-md" action="{{ route('recover-password') }}" method="post" autocomplete="off" novalidate="">
        @csrf
        @method('POST')
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Me olvide mi contraseña</h2>
            <p class="text-secondary mb-4">Ingresa tu correo y tu contraseña sera reseteada y enviada a tu correo.</p>
            <div class="mb-3">
                <label class="form-label">Correo Electronico</label>
                <input id="name" name="email" type="email" class="form-control" placeholder="Enter email" value="{{ old('email') }}">
                @error('email')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-dark w-100">
                    <!-- Download SVG icon from http://tabler-icons.io/i/mail -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"></path><path d="M3 7l9 6l9 -6"></path></svg>
                    Enviame una nueva contraseña a mi correo.
                </button>
            </div>
        </div>
    </form>
    <div class="text-center text-secondary mt-3">
        Olvidalo, <a href="{{ route('login') }}">atrás</a> al inicio de Sesión.
    </div>
</div>
@endsection
