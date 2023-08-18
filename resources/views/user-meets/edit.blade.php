@extends('layouts.app')
@section('content')
    <div class="container-fluid main-container">
    @if ($user_meet)
    @extends('config.log')
    <!--header-->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Editar  Sesión
                </h2>
            </div>
            <div class="col-auto ms-auto">
                <div class="btn-list">
                    <form action="{{ route('user-meets.index') }}" method="POST">
                        @csrf
                        @method('GET')
                        <button type="submit" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-report">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-group" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1"></path>
                                <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                <path d="M17 10h2a2 2 0 0 1 2 2v1"></path>
                                <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                <path d="M3 13v-1a2 2 0 0 1 2 -2h2"></path>
                            </svg>
                            Volver a la lista de Sesiones
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ route('user-meets.update', $user_meet) }}" class="row flex-column flex-md-row" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="col-12 ">
            <div class="form-group my-2 ">
                <label for="canceled">Cancelada?</label>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input @error('canceled') is-invalid @enderror" id="canceled" name="canceled" {{ $user_meet->canceled ? 'checked': '' }}>
                    <label class="form-check-label" for="canceled">Si</label>
                </div>
                @error('canceled')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group my-2">
                <label for="canceled_reason" class="my-2">Razon de Cancelación</label>
                <textarea class="form-control @error('canceled_reason') is-invalid @enderror" id="canceled_reason" name="canceled_reason">{{ old('canceled_reason', $user_meet->canceled_reason) }}</textarea>
                @error('canceled_reason')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group my-2">
                <label for="rate" class="my-2">Estrellas valuadas
                    @for ($i = 0; $i < 5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-star-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                           <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                           <path d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z" stroke-width="0" fill="currentColor"></path>
                        </svg>
                    @endfor
                </label>
                <input type="number" min="0" max="5" class="form-control @error('rate') is-invalid @enderror" id="rate" name="rate" value={{old('rate', $user_meet->rate)}}>
                @error('rate')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group my-2">
                <label for="comment" class="my-2">Comentarios</label>
                <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment">{{ old('comment', $user_meet->comment) }}</textarea>
                @error('comment')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="d-flex justify-content-start my-4">
                <button type="submit" class="btn btn-dark w-auto">Actualizar Sesión</button>
            </div>
        </div>
    </form>
    </div>
    @else
        <div class="row mt-3">
            <div class="row-5">
                <div class="alert alert-danger" role="alert">
                    "Hubo un error al intentar traer la información del meet"
                </div>
                <a href={{ route('user-meets.index') }} class="btn btn-primary">Voler a la lista de Sesiones</a>
            </div>
        </div>
    @endif
@endsection
