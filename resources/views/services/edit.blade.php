@extends('layouts.app')
@section('content')
    <div class="container-fluid main-container">
    <!--header-->

    @if ($service)
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Editar Servicio 
                </h2>
            </div>
            <div class="col-auto ms-auto">
                <div class="btn-list">
                    <form action="{{ route('services.index') }}" method="POST">
                        @csrf
                        @method('GET')
                        <button type="submit" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-report">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-services-group" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1"></path>
                                <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                <path d="M17 10h2a2 2 0 0 1 2 2v1"></path>
                                <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                <path d="M3 13v-1a2 2 0 0 1 2 -2h2"></path>
                            </svg>
                            Volver a la lista de Servicios
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ route('services.update', $service) }}" class="col-6">
        @csrf
        @method('PUT')
        <div class="form-group my-2">
            <label for="name" class="my-2">Nombre del Servicio </label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name',$service->name) }}">
            @error('name')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <label>Opciones:</label>
        <div id="options-container" class="my-2">
        </div>
        @error('options')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
        <div class="flex flex-row">
            <button type="button" class="btn btn-dark" id="add-option">Agregar Opcion</button>
            <button type="button" class="btn btn-dark"id="remove-option" disabled>Remover Ultima Opcion</button>
        </div>

        <div class="form-group my-2">
            <label for="is_active">El servicio esta activo?</label>
            <div class="form-check">
                <input type="checkbox" class="form-check-input @error('is_active') is-invalid @enderror" id="is_active" name="is_active" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Si</label>
            </div>
            @error('is_active')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
        <div class="d-flex justify-content-start my-4">
            <button type="submit" class="btn btn-dark w-auto">Actualizar Servicio</button>
        </div>

    </form>
    @else
        <div class="row mt-3">
            <div class="row-5">
                <div class="alert alert-danger" role="alert">
                    "Hubo un error al intentar traer la información servicio"
                </div>
                <a href={{ route('services.index') }} class="btn btn-primary">Voler a la lista de Servicios</a>
            </div>
        </div>
    @endif
    </div>
    @include('config.services')
@endsection
