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
                    Sesión 
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
                    <form action="{{ route('user-meets.edit', $user_meet) }}" method="POST">
                        @csrf
                        @method('GET')
                        <button type="submit" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-report">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                               <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                               <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                               <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                               <path d="M16 5l3 3"></path>
                            </svg>
                            Editar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row flex-column flex-md-row">
        <div class="col-12 col-md-6">
            <div class="form-group my-2">
                <label for="user_id" class="my-2">Usuario 
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                       <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                       <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                       <path d="M6 21v-2a4 4 0 0 1 4 -4h4"></path>
                       <path d="M15 19l2 2l4 -4"></path>
                    </svg>
                </label>
                <input type="hidden" id="user_id" name="user_id" value="{{  $user_meet->user_id }}" readonly>
                <div class="row g-2">
                    <div class="col">
                        <input type="text" class="form-control @error('user_id') is-invalid @enderror" id="user_name" name="user_name" value="{{  $user_meet->user->name }}" readonly>
                    </div>
                </div>
            </div>
            <div class="form-group my-2">
                <label for="specialist_id" class="my-2">Especialista
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                       <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                       <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                       <path d="M6 21v-2a4 4 0 0 1 4 -4h4"></path>
                       <path d="M15 19l2 2l4 -4"></path>
                    </svg>
                </label>
                <input type="hidden" id="specialist_id" name="specialist_id" value="{{ $user_meet->specialist_id }}">
                <div class="row g-2">
                    <div class="col">
                        <input type="text" class="form-control @error('specialist_id') is-invalid @enderror" id="specialist_name" name="specialist_name" value="{{  $user_meet->specialist->user->name }}" readonly>
                    </div>
                </div>
            </div>

            <div class="form-group my-2">
                <label for="service_id" class="my-2">Servicio 
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brain" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                       <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                       <path d="M15.5 13a3.5 3.5 0 0 0 -3.5 3.5v1a3.5 3.5 0 0 0 7 0v-1.8"></path>
                       <path d="M8.5 13a3.5 3.5 0 0 1 3.5 3.5v1a3.5 3.5 0 0 1 -7 0v-1.8"></path>
                       <path d="M17.5 16a3.5 3.5 0 0 0 0 -7h-.5"></path>
                       <path d="M19 9.3v-2.8a3.5 3.5 0 0 0 -7 0"></path>
                       <path d="M6.5 16a3.5 3.5 0 0 1 0 -7h.5"></path>
                       <path d="M5 9.3v-2.8a3.5 3.5 0 0 1 7 0v10"></path>
                    </svg>
                </label>
                <input type="hidden" id="service_id" name="service_id" value="{{ $user_meet->service_id }}">
                <input type="hidden" id="service_option_id" name="service_option_id" value="{{ $user_meet->service_id }}">
                <div class="row g-2">
                    <div class="col">
                        <input type="text" class="form-control @error('service_id') is-invalid @enderror" id="service_name" name="service_name" value="{{ $user_meet->service->name }}" readonly>
                    </div>
                </div>
            </div>
            <div class="form-group my-2">
                <label for="duration" class="my-2">Duración en Minutos</label>
                <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value={{ $user_meet->duration }} readonly>
            </div>
            <div class="form-group my-2">
                <label for="date_meet" class="my-2">Fecha y hora de la Sesión</label>
                <input type="datetime-local" class="form-control @error('date_meet') is-invalid @enderror" id="date_meet" name="date_meet" 
                value={{ $user_meet->date_meet ? \Carbon\Carbon::parse($user_meet->date_meet)->format('Y-m-d\TH:i:s') : ''}} readonly>
            </div>
            <div class="form-group my-2">
                <label for="price" class="my-2">Precio</label>
                <input type="number" step="0.1" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value={{ $user_meet->price }} readonly>
            </div>

            <div class="form-group my-2">
                <label for="discount_code_id" class="my-2"> Descuento
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-discount-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                       <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                       <path d="M9 15l6 -6"></path>
                       <circle cx="9.5" cy="9.5" r=".5" fill="currentColor"></circle>
                       <circle cx="14.5" cy="14.5" r=".5" fill="currentColor"></circle>
                       <path d="M5 7.2a2.2 2.2 0 0 1 2.2 -2.2h1a2.2 2.2 0 0 0 1.55 -.64l.7 -.7a2.2 2.2 0 0 1 3.12 0l.7 .7a2.2 2.2 0 0 0 1.55 .64h1a2.2 2.2 0 0 1 2.2 2.2v1a2.2 2.2 0 0 0 .64 1.55l.7 .7a2.2 2.2 0 0 1 0 3.12l-.7 .7a2.2 2.2 0 0 0 -.64 1.55v1a2.2 2.2 0 0 1 -2.2 2.2h-1a2.2 2.2 0 0 0 -1.55 .64l-.7 .7a2.2 2.2 0 0 1 -3.12 0l-.7 -.7a2.2 2.2 0 0 0 -1.55 -.64h-1a2.2 2.2 0 0 1 -2.2 -2.2v-1a2.2 2.2 0 0 0 -.64 -1.55l-.7 -.7a2.2 2.2 0 0 1 0 -3.12l.7 -.7a2.2 2.2 0 0 0 .64 -1.55v-1"></path>
                    </svg>
                </label>
                <input type="hidden" id="discount_code_id" name="discount_code_id" value="{{ $user_meet->discount_code_id ? $user_meet->discount_code_id : 0  }}">
                <div class="row g-2">
                    <div class="col">
                        <input type="text" class="form-control @error('discount_code_id') is-invalid @enderror" id="discount_name" name="discount_name" value="{{ $user_meet->discountCode ? $user_meet->discountCode->name : '' }}" readonly>
                    </div>
                </div>
            </div>
            
            <div class="form-group my-2">
                <label for="discount" class="my-2">Descuento 
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-discount-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                   <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                   <path d="M9 15l6 -6"></path>
                   <circle cx="9.5" cy="9.5" r=".5" fill="currentColor"></circle>
                   <circle cx="14.5" cy="14.5" r=".5" fill="currentColor"></circle>
                   <path d="M5 7.2a2.2 2.2 0 0 1 2.2 -2.2h1a2.2 2.2 0 0 0 1.55 -.64l.7 -.7a2.2 2.2 0 0 1 3.12 0l.7 .7a2.2 2.2 0 0 0 1.55 .64h1a2.2 2.2 0 0 1 2.2 2.2v1a2.2 2.2 0 0 0 .64 1.55l.7 .7a2.2 2.2 0 0 1 0 3.12l-.7 .7a2.2 2.2 0 0 0 -.64 1.55v1a2.2 2.2 0 0 1 -2.2 2.2h-1a2.2 2.2 0 0 0 -1.55 .64l-.7 .7a2.2 2.2 0 0 1 -3.12 0l-.7 -.7a2.2 2.2 0 0 0 -1.55 -.64h-1a2.2 2.2 0 0 1 -2.2 -2.2v-1a2.2 2.2 0 0 0 -.64 -1.55l-.7 -.7a2.2 2.2 0 0 1 0 -3.12l.7 -.7a2.2 2.2 0 0 0 .64 -1.55v-1"></path>
                </svg>
                </label>
                <input type="number" class="form-control @error('discount') is-invalid @enderror" id="discount" name="discount" value={{ $user_meet->discount }} readonly>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="form-group my-2">
                <label for="discounted_price" class="my-2">Precio con Descuento</label>
                <input type="number" step="0.1" class="form-control @error('discounted_price') is-invalid @enderror" id="discounted_price" name="discounted_price" value={{ $user_meet->discounted_price }} readonly>
            </div>

            <div class="form-group my-2 ">
                <label for="canceled">Cancelada?</label>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input @error('canceled') is-invalid @enderror" id="canceled" name="canceled" {{ $user_meet->canceled ? 'checked': '' }} readonly>
                    <label class="form-check-label" for="canceled">Si</label>
                </div>
            </div>

            <div class="form-group my-2">
                <label for="canceled_reason" class="my-2">Razon de Cancelación</label>
                <textarea class="form-control @error('canceled_reason') is-invalid @enderror" id="canceled_reason" name="canceled_reason" readonly>{{ $user_meet->canceled_reason }}</textarea>
            </div>

            <div class="form-group my-2">
                <label for="link_meet" class="my-2">Enlace de Meet </label>
                <input type="text" class="form-control @error('link_meet') is-invalid @enderror" id="link_meet" name="link_meet" value="{{ $user_meet->link_meet }}" readonly>
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
                <input type="number" min="0" max="5" class="form-control @error('rate') is-invalid @enderror" id="rate" name="rate" value={{$user_meet->rate}} readonly>
            </div>
            <div class="form-group my-2">
                <label for="comment" class="my-2">Comentarios</label>
                <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" readonly>{{ $user_meet->comment }}</textarea>
            </div>
        </div>
    </div>
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
