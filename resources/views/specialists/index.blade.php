@extends('layouts.app')
@section('content')
    <div class="container-fluid main-container">
    @extends('config.log')
    <!--header-->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Panel de Especialistas
                </div>
                <h2 class="page-title">
                    Especialistas
                </h2>
            </div>
            <div class="col-auto ms-auto">
                <div class="btn-list">
                    <form action="{{ route('specialists.create') }}" method="POST">
                        @csrf
                        @method('GET')
                        <button type="submit" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-report">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                            Crear nuevo especialista
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <form class="d-flex flex-row form-inline py-2 col-5 gap-2 mb-4" action="{{ route('specialists.index') }}" method="GET">
        <input class="form-control mr-sm-2" type="search" name="name" placeholder="Buscar por nombre" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0 col-3" type="submit">Buscar</button>
    </form>
    <!--table uers-->
    <div class="table-responsive">
        <table class="table table-vcenter">
            <thead>
            <tr>
                <th class="no-sort">#</th>
                <th>id de Usuario</th>
                <th>Nombre</th>
                <th>Servicios</th>
                <th>Imagen de perfil</th>
                <th>Esta activo</th>
                <th class="no-sort">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($specialists as $specialist)
                <tr>
                    <td>{{ $specialist->id }}</td>
                    <td>{{ $specialist->user->id  }}</td>
                    <td>{{ $specialist->user->name  }}</td>
                    <td>
                        <ul>
                                @foreach ($specialist->services as $service)
                                    <li>id: {{ $service->id_service }} | nombre: {{ $service->name_service }}</li>
                                @endforeach

                        </ul>
                    </td>
                    <td>
                        @if ($specialist->profile_image)
                            <a href="{{env('BACKEND_URL_IMAGE')}}{{ $specialist->profile_image }}" target="_blank">Imagen de Perfil</a>
                        @else
                            <label>No hay imagen cargada</label>

                        @endif
                    </td>
                    <td>{{ $specialist->is_active ? "Si" : "No" }}</td>
                    <td>
                        <div class="btn-group btn-group-sm gap-2" role="group">
                            <form action="{{ route('specialists.show', $specialist) }}" method="POST">
                                @csrf
                                @method('GET')
                                <button type="submit" class="btn btn-primary btn-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                       <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                       <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                       <path d="M11.102 17.957c-3.204 -.307 -5.904 -2.294 -8.102 -5.957c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6a19.5 19.5 0 0 1 -.663 1.032"></path>
                                       <path d="M15 19l2 2l4 -4"></path>
                                    </svg>
                                </button>
                            </form>
                            <form action="{{ route('specialists.edit', $specialist) }}" method="POST">
                                @csrf
                                @method('GET')
                                <button type="submit" class="btn btn-dark btn-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                       <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                       <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                       <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                       <path d="M16 5l3 3"></path>
                                    </svg>
                                </button>
                            </form>
                            <form action="{{ route('specialists.destroy', $specialist) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-red btn-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                       <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                       <path d="M4 7l16 0"></path>
                                       <path d="M10 11l0 6"></path>
                                       <path d="M14 11l0 6"></path>
                                       <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                       <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <nav aria-label="Page navigation example" class="mt-4">
            <ul class="pagination gap-2">
                <li class="btn btn-dark btn-sm rounded"><a class="text-white no-underline px-2" href="{{ route('specialists.index', ['page' => ($specialists->currentPage()-1)]) }}">Anterior</a></li>
                @for ($i = 1; $i <= $specialists->lastPage(); $i++)
                    <li class="btn btn-dark btn-sm bg-dark {{ ($i == $specialists->currentPage()) ? ' active' : '' }}"><a class="page-link bg-dark" href="{{ route('specialists.index', ['page' => $i]) }}">{{ $i }}</a></li>
                @endfor
                <li class="btn btn-dark btn-sm rounded"><a class="text-white no-underline px-2" href="{{ route('specialists.index', ['page' => ($specialists->currentPage()+1)]) }}">Siguiente</a></li>
            </ul>
        </nav>
    </div>
    </div>
@endsection
