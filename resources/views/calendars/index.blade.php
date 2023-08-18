@extends('layouts.app')
@section('content')
    <div class="container-fluid main-container">
    @extends('config.log')
    <!--header-->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Calendario 
                </div>
                <h2 class="page-title">
                   Calendario de Sesiones 
                </h2>
                <h3 class="page-title">
                    {{ \Carbon\Carbon::parse($currentDate)->format('Y') }} - {{ \Carbon\Carbon::parse($currentDate)->format('m') }}
                </h3>
            </div>
        </div>
    </div>
    <nav aria-label="Page navigation example" class="mt-4">
        <ul class="pagination gap-2">
            <li class="btn btn-dark btn-sm rounded"><a class="text-white no-underline p-2" href="{{ route('calendars.index', ['currentDate' => \Carbon\Carbon::parse($currentDate)->subMonth()->format('Y-m-d')]) }}">Anterior</a></li>
            <li class="btn btn-dark btn-sm rounded"><a class="text-white no-underline p-2" href="{{ route('calendars.index', ['currentDate' => \Carbon\Carbon::parse($currentDate)->addMonth()->format('Y-m-d')]) }}">Siguiente</a></li>
        </ul>
    </nav>
    <h2>Items del Calendario</h2>
    <ul>
        <li class="flex items-center gap-2">
            <p>Sesiones
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-event" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                   <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                   <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                   <path d="M16 3l0 4"></path>
                   <path d="M8 3l0 4"></path>
                   <path d="M4 11l16 0"></path>
                   <path d="M8 15h2v2h-2z"></path>
                </svg>
            </p>
        </li>
    </ul>
    <!--table uers-->
    <div  class="table-responsive">
        <div id="calendar-container" class="flex flex-col gap-y-2">

        </div>
    </div>
    @include('config.calendars')
@endsection
