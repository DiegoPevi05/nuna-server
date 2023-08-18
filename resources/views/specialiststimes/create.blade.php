@extends('layouts.app')
@section('content')
    <div class="container-fluid main-container">
    @extends('config.log')
    <!--header-->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Crear Horas de Especialista
                </h2>
            </div>
            <div class="col-auto ms-auto">
                <div class="btn-list">
                    <form action="{{ route('specialiststimes.index') }}" method="POST">
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
                            Volver a la lista de Horas de Especialista 
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ route('specialiststimes.store') }}" class="col-6">
        @csrf
        @method('POST')

        <div class="form-group my-2">
            <label for="specialist_id" class="my-2">Especialista
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                   <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                   <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                   <path d="M6 21v-2a4 4 0 0 1 4 -4h4"></path>
                   <path d="M15 19l2 2l4 -4"></path>
                </svg>
            </label>
            <input type="hidden" id="specialist_id" name="specialist_id" value="{{ old('specialist_id') }}">
            <div class="row g-2">
                <div class="col">
                    <input type="text" class="form-control @error('specialist_id') is-invalid @enderror" id="specialist_name" name="specialist_name" placeholder="Buscar Especialista" value="{{ old('specialist_name') }}" >
                </div>
                <div class="col-auto">
                  <button onclick="fetchSpecialists(event)" class="btn btn-icon" aria-label="Button">

                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <circle cx="10" cy="10" r="7" />
                      <line x1="21" y1="21" x2="15" y2="15" />
                    </svg>
                  </button>
                </div>
            </div>
            <ul id="specialists-list" class="list-group mt-2"></ul>
            @error('specialist_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group my-2">
            <label for="start_date" class="my-2">Fecha y Hora de Inicio</label>
            <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value={{old('start_date')}}>
            @error('start_date')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group my-2">
            <label for="end_date" class="my-2">Fecha y Hora de Fin</label>
            <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value={{old('end_date')}}>
            @error('end_date')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="d-flex justify-content-start my-4">
            <button type="submit" class="btn btn-dark w-auto">Crear TimeSheet</button>
        </div>

    </form>
    </div>
    @include('config.specialisttimes')
@endsection
