@extends('layouts.app')
@section('content')
    <div class="container-fluid main-container">
    @extends('config.log')
    <!--header-->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Panel de Sesiones
                </div>
                <h2 class="page-title">
                    Sesiones
                </h2>
            </div>
        </div>
    </div>
    <form class="d-flex flex-row form-inline py-2 col-5 gap-2 mb-4" action="{{ route('user-meets.index') }}" method="GET">
        <input class="form-control mr-sm-2" type="date" name="date" placeholder="Buscar por Fecha" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0 col-3" type="submit">Buscar</button>
    </form>
    <!--table uers-->
    <div class="table-responsive">
        <table class="table table-vcenter">
            <thead>
            <tr>
                <th class="no-sort">#</th>
                <th>Usuario</th>
                <th>Especialista</th>
                <th>Servicio</th>
                <th>Duración</th>
                <th>Dia de la Sesión</th>
                <th>Cancelada?</th>
                <th>Enlace de Reunión</th>
                <th class="no-sort">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($user_meets as $user_meet)
                <tr>

                    <td>{{ $user_meet->id }}</td>
                    <td>{{ $user_meet->user->name }}</td>
                    <td>{{ $user_meet->specialist->user->name}}</td>
                    <td>{{ $user_meet->service->name  }}</td>
                    <td>{{ $user_meet->duration  }}</td>
                    <td>{{ $user_meet->date_meet  }}</td>
                    <td>{{ $user_meet->canceled ? "Si" : "No" }}</td>
                    <td>
                        <a href="{{$user_meet->link_meet}}" target="_blank">Enlace de reunion</a>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm gap-2" role="group">
                            <form action="{{ route('user-meets.show', $user_meet->id) }}" method="POST">
                                @csrf
                                @method('GET')
                                <button type="submit" class="btn btn-primary btn-md">
                                    <svg xmlns="http://www.w3.org/2000/svg"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                       <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                       <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                       <path d="M11.192 17.966c-3.242 -.28 -5.972 -2.269 -8.192 -5.966c2.4 -4 5.4 -6 9 -6c3.326 0 6.14 1.707 8.442 5.122"></path>
                                       <path d="M18.42 15.61a2.1 2.1 0 0 1 2.97 2.97l-3.39 3.42h-3v-3l3.42 -3.39z"></path>
                                    </svg>
                                </button>
                            </form>
                            @if(Auth::user()->role == 'USER')
                                <form action="{{ route('user-meets.edit', $user_meet) }}" method="POST">
                                    @csrf
                                    @method('GET')
                                    <button type="submit" class="btn btn-dark btn-md">
                                        <svg xmlns="http://www.w3.org/2000/svg"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                           <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                           <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                           <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                           <path d="M16 5l3 3"></path>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <nav aria-label="Page navigation example" class="mt-4">
            <ul class="pagination gap-2">
                <li class="btn btn-dark btn-sm rounded"><a class="text-white no-underline px-2" href="{{ route('user-meets.index', ['page' => ($user_meets->currentPage()-1)]) }}">Anterior</a></li>
                @for ($i = 1; $i <= $user_meets->lastPage(); $i++)
                    <li class="btn btn-dark btn-sm bg-dark {{ ($i == $user_meets->currentPage()) ? ' active' : '' }}"><a class="page-link bg-dark" href="{{ route('meets.index', ['page' => $i]) }}">{{ $i }}</a></li>
                @endfor
                <li class="btn btn-dark btn-sm rounded"><a class="text-white no-underline px-2" href="{{ route('user-meets.index', ['page' => ($user_meets->currentPage()+1)]) }}">Siguiente</a></li>
            </ul>
        </nav>
    </div>
    </div>
@endsection
