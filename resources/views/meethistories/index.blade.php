@extends('layouts.app')
@section('content')
    <div class="container-fluid main-container">
    @extends('config.log')
    <!--header-->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Panel de Sesiones  Realizadas
                </div>
                <h2 class="page-title">
                    Sesiones Realizadas
                </h2>
            </div>
        </div>
    </div>
    <form class="d-flex flex-row form-inline py-2 col-5 gap-2 mb-4" action="{{ route('meethistories.index') }}" method="GET">
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
                <th>Estado del pago</th>
                <th class="no-sort">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($meethistories as $meethistory)
                <tr>
                    <td>{{ $meethistory->meet->id }}</td>
                    <td>{{ $meethistory->meet->user->name }}</td>
                    <td>{{ $meethistory->meet->specialist->user->id}}</td>
                    <td>{{ $meethistory->meet->service->name  }}</td>
                    <td>{{ $meethistory->meet->duration  }}</td>
                    <td>{{ $meethistory->meet->date_meet  }}</td>
                    <td>{{ $meethistory->meet->canceled ? "Si" : "No" }}</td>
                    <td>{{ $meethistory->meet->link_meet }}</td>
                    @if($meethistory->meet->payment_status == 'PENDING')
                        <td>Pendiente</td>
                    @elseif($meethistory->meet->payment_status  == 'DENIED')
                        <td>Denegado</td>
                    @elseif($meethistory->meet->payment_status == 'BILLED')
                        <td>Facturado</td>
                    @elseif($meethistory->meet->payment_status == 'PROCESSING' )
                        <td>Procesando</td>
                    @endif
                    <td>
                        <div class="btn-group btn-group-sm gap-2" role="group">
                            <form action="{{ route('meets.show', $meethistory->meet->id) }}" method="POST">
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
                            <form action="{{ route('downloadBill', $meethistory->id) }}" method="POST">
                                @csrf
                                @method('GET')
                                <button type="submit" class="btn btn-dark btn-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-download" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                       <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                       <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path>
                                       <path d="M7 11l5 5l5 -5"></path>
                                       <path d="M12 4l0 12"></path>
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
                <li class="btn btn-dark btn-sm rounded"><a class="text-white no-underline px-2" href="{{ route('meethistories.index', ['page' => ($meethistories->currentPage()-1)]) }}">Anterior</a></li>
                @for ($i = 1; $i <= $meethistories->lastPage(); $i++)
                    <li class="btn btn-dark btn-sm bg-dark {{ ($i == $meethistories->currentPage()) ? ' active' : '' }}"><a class="page-link bg-dark" href="{{ route('meethistories.index', ['page' => $i]) }}">{{ $i }}</a></li>
                @endfor
                <li class="btn btn-dark btn-sm rounded"><a class="text-white no-underline px-2" href="{{ route('meethistories.index', ['page' => ($meethistories->currentPage()+1)]) }}">Siguiente</a></li>
            </ul>
        </nav>
    </div>

    </div>
@endsection
