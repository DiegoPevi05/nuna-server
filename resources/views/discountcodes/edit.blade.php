@extends('layouts.app')
@section('content')
    <div class="container-fluid main-container">
    <!--header-->

    @if ($discountcode)
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Editar Codigo de descuento 
                </h2>
            </div>
            <div class="col-auto ms-auto">
                <div class="btn-list">
                    <form action="{{ route('discountcodes.index') }}" method="POST">
                        @csrf
                        @method('GET')
                        <button type="submit" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-report">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-discountcodes-group" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1"></path>
                                <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                <path d="M17 10h2a2 2 0 0 1 2 2v1"></path>
                                <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                <path d="M3 13v-1a2 2 0 0 1 2 -2h2"></path>
                            </svg>
                            Volver a la lista de Descuentos 
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ route('discountcodes.update', $discountcode) }}" class="col-6">
        @csrf
        @method('PUT')
        <div class="form-group my-2">
            <label for="name" class="my-2">Nombre del Servicio </label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $discountcode->name) }}">
            @error('name')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group my-2">
            <label for="discount" class="my-2">Descuento </label>
            <input type="number" step="0.1" min="1" class="form-control @error('discount') is-invalid @enderror" id="discount" name="discount" value="{{ old('discount',$discountcode->discount) }}">
            @error('discount')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group my-2">
            <label for="quantity_discounts" class="my-2">Cantidad de descuentos </label>
            <input type="number"  min="1" class="form-control @error('quantity_discounts') is-invalid @enderror" id="quantity_discounts" name="quantity_discounts" value="{{ old('quantity_discounts',$discountcode->quantity_discounts) }}">
            @error('quantity_discounts')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group my-2">
            <label for="status" class="my-2">Estado</label>
            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                <option value="active"{{ old('status',$discountcode->status) === 'active' ? ' selected' : '' }}>Activo</option>
                <option value="inactive"{{ old('status', $discountcode->status) === 'inactive' ? ' selected' : '' }}>Inactivo</option>
            </select>
            @error('status')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group my-2">
            <label for="expired_date" class="my-2">Fecha de Vencimiento</label>
            <input type="date" class="form-control @error('expired_date') is-invalid @enderror" id="expired_date" name="expired_date" 
            value={{old('expired_date',$discountcode->expired_date ? \Carbon\Carbon::parse($discountcode->expired_date)->format('Y-m-d') : '')}}>
            @error('expired_date')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="d-flex justify-content-start my-4">
            <button type="submit" class="btn btn-dark w-auto">Actualizar Codigo de Descuento</button>
        </div>

    </form>
    @else
        <div class="row mt-3">
            <div class="row-5">
                <div class="alert alert-danger" role="alert">
                    "Hubo un error al intentar traer la información de descuento"
                </div>
                <a href={{ route('discountcodes.index') }} class="btn btn-primary">Voler a la lista de Descuentos</a>
            </div>
        </div>
    @endif
    </div>
@endsection
