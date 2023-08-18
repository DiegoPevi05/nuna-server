@extends('layouts.app')
@section('content')
    <div class="container-fluid main-container">
    @if ($specialist_specialiststime)
    @extends('config.log')
    <!--header-->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Actualizar Horas de Especialista
                </h2>
            </div>
            <div class="col-auto ms-auto">
                <div class="btn-list">
                    <form action="{{ route('specialist-specialiststimes.index') }}" method="POST">
                        @csrf
                        @method('GET')
                        <button type="submit" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-report">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-specialiststimes-group" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1"></path>
                                <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                <path d="M17 10h2a2 2 0 0 1 2 2v1"></path>
                                <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                <path d="M3 13v-1a2 2 0 0 1 2 -2h2"></path>
                            </svg>
                            Volver a la lista de Horas
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ route('specialist-specialiststimes.update', $specialist_specialiststime)}}" class="col-6">
        @csrf
        @method('PUT')
        <div class="form-group my-2">
            <label for="specialist_id" class="my-2">Horas de Especialista
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                   <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                   <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                   <path d="M6 21v-2a4 4 0 0 1 4 -4h4"></path>
                   <path d="M15 19l2 2l4 -4"></path>
                </svg>
            </label>
            <input type="hidden" id="specialist_id" name="specialist_id" value="{{ old('specialist_id', $specialist_specialiststime->specialist_id) }}">
            <div class="row g-2">
                <div class="col">
                    <input type="text" class="form-control @error('specialist_id') is-invalid @enderror" id="specialist_name" name="specialist_name" placeholder="Buscar Especialista" value="{{ old('specialist_name', $specialist_specialiststime->specialist->user->name) }}" readonly>
                </div>
            </div>
            <ul id="specialists-list" class="list-group mt-2"></ul>
            @error('specialist_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group my-2">
            <label for="start_date" class="my-2">Fecha y Hora de Inicio</label>
            <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" 
            value={{old('start_date',$specialist_specialiststime->start_date ? \Carbon\Carbon::parse($specialist_specialiststime->start_date)->format('Y-m-d\TH:i:s') : '')}}>
            @error('start_date')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group my-2">
            <label for="end_date" class="my-2">Fecha y Hora de Fin</label>
            <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" 
            value={{old('end_date',$specialist_specialiststime->end_date ? \Carbon\Carbon::parse($specialist_specialiststime->end_date)->format('Y-m-d\TH:i:s') : '')}}>
            @error('end_date')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="d-flex justify-content-start my-4">
            <button type="submit" class="btn btn-dark w-auto">Actualizar TimeSheet</button>
        </div>

    </form>
    </div>
    @else
        <div class="row mt-3">
            <div class="row-5">
                <div class="alert alert-danger" role="alert">
                    "Hubo un error al intentar traer las horas del Especialista"
                </div>
                <a href={{ route('specialist-specialiststimes.index') }} class="btn btn-primary">Voler a la lista de Horas</a>
            </div>
        </div>
    @endif
@endsection
