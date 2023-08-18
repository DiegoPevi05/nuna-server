@extends('layouts.app')
@section('content')
    <div class="container-fluid main-container">
        <!--header-->
        @extends('config.log')
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Actualizar Token Zoom
                    </h2>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('zooms.store') }}" class="col-6">
            @csrf
            @method('POST')
            <div class="form-group my-2">
                <label for="CLIENT_ID" class="my-2">CLIENT ID </label>
                <input type="text" class="form-control @error('CLIENT_ID') is-invalid @enderror" id="CLIENT_ID" name="CLIENT_ID" placeholder="CLIENT_ID" value="{{ old( 'CLIENT_ID', $credentials ? $credentials->CLIENT_ID_ZOOM: '')}}">
                @error('CLIENT_ID')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-2">
                <label class="form-label">
                    CLIENT SECRET
                    <span class="form-label-description">
              </span>
                </label>
                <div class="input-group input-group-flat">
                    <input id="CLIENT_SECRET" name="CLIENT_SECRET" type="password" class="form-control" placeholder="CLIENT_SECRET" autocomplete="off" value="{{  old('CLIENT_SECRET',$credentials ? $credentials->CLIENT_SECRET_ZOOM : '' ) }}">
                    <span class="input-group-text">
                        <a href="#" class="link-secondary" data-bs-toggle="tooltip" aria-label="Toggle password visibility" data-bs-original-title="Toggle password visibility" id="password-toggle">
                            <!-- Updated SVG icon to toggle password visibility -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path>
                            </svg>
                        </a>
                    </span>
                </div>
                @error('CLIENT_SECRET')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="d-flex justify-content-start my-4">
                @if($credentials)
                    <button type="submit" class="btn btn-dark w-auto">Actualizar credenciales</button>
                @else
                    <button type="submit" class="btn btn-dark w-auto">Crear credenciales</button>
                @endif
            </div>

        </form>

        <div class="row flex-column ">
            @if($credentials)
                <label for="name" class="my-2">Haciendo click generas un nuevo token</label>
                <a href="https://zoom.us/oauth/authorize?response_type=code&client_id={{ $credentials->CLIENT_ID_ZOOM }}&redirect_uri={{ env('REDIRECT_URI_ZOOM') }}" target="_blank" class="btn btn-primary col-4">Generate Token</a>
            @else
                <label for="name" class="my-2">Crea las credenciales para que puedas generar el token.</label>
            @endif
        </div>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('CLIENT_SECRET');
        const passwordToggle = document.getElementById('password-toggle');

        passwordToggle.addEventListener('click', function (event) {
            event.preventDefault();
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });
    });
</script>
@endsection
