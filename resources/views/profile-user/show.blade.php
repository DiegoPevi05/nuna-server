@extends('layouts.app')
@section('content')
    <!--header-->
    <div class="container-fluid main-container">
    @if ($user)
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Usuario:  {{ $user->name }}
                    </h2>
                </div>
                <div class="col-auto ms-auto">
                    <div class="btn-list">
                        <form action="{{ route('profile-user.edit', $user->id) }}" method="POST">
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
                                Editar Información
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="form-group my-2">
                <label for="name" class="my-2">Nombre del Usuario </label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" readonly>
            </div>
            <div class="form-group my-2">
                <label for="email" class="my-2">Correo del Usuario </label>
                <input type="text" class="form-control"  id="email" name="email" value="{{ old('email', $user->email) }}" readonly>
            </div>
        </div>
    @else
        <div class="row mt-3">
            <div class="row-5">
                <div class="alert alert-danger" role="alert">
                    "Hubo un error al intentar traer la información usuario"
                </div>
                <a href={{ route('profile-user.show', $user->id ) }} class="btn btn-primary">Recargar la pagina</a>
            </div>
        </div>
        @endif
    </div>
@endsection
