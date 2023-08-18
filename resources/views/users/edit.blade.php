@extends('layouts.app')
@section('content')
    <!--header-->
    <div class="container-fluid main-container">
    @if ($user)
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Editar Usuario:  "{{ $user->name }}"
                    </h2>
                </div>
                <div class="col-auto ms-auto">
                    <div class="btn-list">
                        <form action="{{ route('users.index') }}" method="POST">
                            @csrf
                            @method('GET')
                            <button type="submit" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-report">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users-group" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                    <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1"></path>
                                    <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                    <path d="M17 10h2a2 2 0 0 1 2 2v1"></path>
                                    <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                    <path d="M3 13v-1a2 2 0 0 1 2 -2h2"></path>
                                </svg>
                                Volver a la lista de usuarios
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('users.update', $user) }}" class="col-5">
            @csrf
            @method('PUT')
            <div class="form-group my-2">
                <label for="name" class="my-2">Nombre del Usuario </label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}">
                @error('name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group my-2">
                <label for="email" class="my-2">Correo del Usuario </label>
                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}">
                @error('email')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group my-2">
                <label for="password" class="my-2">Contraseña </label>
                <div class="input-group input-group-flat">
                    <input id="password" name="password" type="password" class="form-control" autocomplete="off" value="{{ old('password') }}">
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
            @if($user->role != 'SPECIALIST')
                <div class="form-group my-2">
                    <label for="role">Role</label>
                    <select class="form-control @error('role') is-invalid @enderror" id="role" name="role">
                        <option class="{{$user->role == "USER" ? 'selected':''}}" value="USER">Usuario</option>
                        <option class="{{$user->role == "MODERATOR" ? 'selected':''}}" value="MODERATOR">Moderador</option>
                    </select>
                    @error('role')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            @else
                <div class="form-group my-2">
                    <label for="role">Role</label>
                    <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" readonly>
                        <option class="{{$user->role == "SPECIALIST" ? 'selected':''}}" value="SPECIALIST">Especialista</option>
                    </select>
                    <span class="invalid-feedback">Si quieres cambiar a usuario o moderador tienes que eliminar primero el especialista</span>
                </div>
            @endif
            <div class="d-flex justify-content-start my-4">
                <button type="submit" class="btn btn-dark w-auto">Actualizar Usuario</button>
            </div>
        </form>
    @else
        <div class="row mt-3">
            <div class="row-5">
                <div class="alert alert-danger" role="alert">
                    "Hubo un error al intentar traer la información usuario"
                </div>
                <a href={{ route('users.index') }} class="btn btn-primary">Voler a la lista de Usuarios</a>
            </div>
        </div>
        @endif
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
    </script>
@endsection
