<div class="page">
  <div class="navbar navbar-vertical navbar-expand navbar-dark" style="width:260px">
      <div class="container-fluid">
          <button class="navbar-toggler" type="button">
              <span class="navbar-toggler-icon"></span>
          </button>
          <h1 class="navbar-brand navbar-brand-autodark">
              <a href="#">
                  <img src="{{env('BACKEND_URL_IMAGE')}}/LogoPink.jpeg" style="width: 50px; height: 50px; border-radius: 50%;" alt="Tabler" class="navbar-brand-image">
              </a>
          </h1>
          <div class="collapse navbar-collapse" id="sidebar-menu">
              <ul class="navbar-nav pt-lg-3">
                  <li class="nav-item">
                      <a class="nav-link" href={{ route('home.index') }}>
                        <span class="nav-link-title">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                             <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                             <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
                             <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
                             <path d="M10 12h4v4h-4z"></path>
                          </svg>
                          Inicio
                        </span>
                      </a>
                  </li>
                  @if (Auth::user() && Auth::user()->role == 'ADMIN')
                  <li class="nav-item">
                      <a class="nav-link" href="{{ route('users.index') }}">
                    <span class="nav-link-title">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                         <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                         <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                         <path d="M6 21v-2a4 4 0 0 1 4 -4h4"></path>
                         <path d="M15 19l2 2l4 -4"></path>
                      </svg>
                      Usuarios
                    </span>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="{{ route('zooms.index') }}">
                    <span class="nav-link-title">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-zoom" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                         <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                         <path d="M17.011 9.385v5.128l3.989 3.487v-12z"></path>
                         <path d="M3.887 6h10.08c1.468 0 3.033 1.203 3.033 2.803v8.196a.991 .991 0 0 1 -.975 1h-10.373c-1.667 0 -2.652 -1.5 -2.652 -3l.01 -8a.882 .882 0 0 1 .208 -.71a.841 .841 0 0 1 .67 -.287z"></path>
                      </svg>
                       Zoom Credenciales
                    </span>
                      </a>
                  </li>
                  @endif

                  @if (Auth::user() && (Auth::user()->role == 'USER' || Auth::user()->role == 'MODERATOR' || Auth::user()->role == 'SPECIALIST'))
                    <li class="nav-item">
                      <a class="nav-link" href="{{ route('profile-user.show', Auth::user()->id ) }}">
                      <span class="nav-link-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                           <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                           <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                           <path d="M6 21v-2a4 4 0 0 1 4 -4h4"></path>
                           <path d="M15 19l2 2l4 -4"></path>
                        </svg>
                        Mi perfil 
                      </span>
                        </a>
                    </li>
                  @endif
                  @if (Auth::user() && Auth::user()->role == 'SPECIALIST')
                    <li class="nav-item">
                      <a class="nav-link" href="{{ route('specialist-specialiststimes.index') }}">
                        <span class="nav-link-title">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clock-hour-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                             <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                             <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                             <path d="M12 7v5"></path>
                             <path d="M12 12l2 -3"></path>
                          </svg>
                           Timesheets de Especialistas
                        </span>
                        </a>
                    </li>
                  @endif
                  @if (Auth::user() && (Auth::user()->role == 'USER' || Auth::user()->role == 'SPECIALIST'))
                    <li class="nav-item">
                      <a class="nav-link" href="{{ route('user-meets.index') }}">
                      <span class="nav-link-title">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                             <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                             <path d="M12.5 21h-6.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v5"></path>
                             <path d="M16 3v4"></path>
                             <path d="M8 3v4"></path>
                             <path d="M4 11h16"></path>
                             <path d="M16 19h6"></path>
                             <path d="M19 16v6"></path>
                          </svg>
                         Sesiones
                      </span>
                        </a>
                    </li>
                  @endif
                  @if (Auth::user() && (Auth::user()->role == 'ADMIN' || Auth::user()->role == 'MODERATOR'))

                      <li class="nav-item">
                          <a class="nav-link" href="{{ route('services.index') }}">
                        <span class="nav-link-title">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brain" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                             <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                             <path d="M15.5 13a3.5 3.5 0 0 0 -3.5 3.5v1a3.5 3.5 0 0 0 7 0v-1.8"></path>
                             <path d="M8.5 13a3.5 3.5 0 0 1 3.5 3.5v1a3.5 3.5 0 0 1 -7 0v-1.8"></path>
                             <path d="M17.5 16a3.5 3.5 0 0 0 0 -7h-.5"></path>
                             <path d="M19 9.3v-2.8a3.5 3.5 0 0 0 -7 0"></path>
                             <path d="M6.5 16a3.5 3.5 0 0 1 0 -7h.5"></path>
                             <path d="M5 9.3v-2.8a3.5 3.5 0 0 1 7 0v10"></path>
                          </svg>
                          Servicios 
                        </span>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" href="{{ route('discountcodes.index') }}">
                        <span class="nav-link-title">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-discount" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                             <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                             <path d="M9 15l6 -6"></path>
                             <circle cx="9.5" cy="9.5" r=".5" fill="currentColor"></circle>
                             <circle cx="14.5" cy="14.5" r=".5" fill="currentColor"></circle>
                             <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                          </svg>
                          Codigo de Descuento 
                        </span>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" href="{{ route('specialists.index') }}">
                        <span class="nav-link-title">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-school" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                             <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                             <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6"></path>
                             <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4"></path>
                          </svg>
                           Especialistas
                        </span>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" href="{{ route('specialiststimes.index') }}">
                        <span class="nav-link-title">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clock-hour-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                             <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                             <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                             <path d="M12 7v5"></path>
                             <path d="M12 12l2 -3"></path>
                          </svg>
                           Timesheets de Especialistas
                        </span>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" href="{{ route('meets.index') }}">
                        <span class="nav-link-title">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                             <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                             <path d="M12.5 21h-6.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v5"></path>
                             <path d="M16 3v4"></path>
                             <path d="M8 3v4"></path>
                             <path d="M4 11h16"></path>
                             <path d="M16 19h6"></path>
                             <path d="M19 16v6"></path>
                          </svg>
                           Sesiones
                        </span>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" href="{{ route('meethistories.index') }}">
                        <span class="nav-link-title">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-cash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                             <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                             <path d="M7 9m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z"></path>
                             <path d="M14 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                             <path d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2"></path>
                          </svg>
                           Sesiones Facturadas
                        </span>
                          </a>
                      </li>
                  @endif
                  <li class="nav-item">
                      <a class="nav-link" href="{{ route('calendars.index') }}">
                    <span class="nav-link-title">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-event" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                         <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                         <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                         <path d="M16 3l0 4"></path>
                         <path d="M8 3l0 4"></path>
                         <path d="M4 11l16 0"></path>
                         <path d="M8 15h2v2h-2z"></path>
                      </svg>
                      Calendario 
                    </span>
                      </a>
                  </li>
                  <li class="nav-item mt-auto">
                      <a class="nav-link mt-auto" href="{{ route('home.index') }}">
                          <img src="{{env('BACKEND_URL_IMAGE')}}/LogoPink.jpeg" alt="" width="32" height="32" class="rounded-circle me-2">
                          <strong>{{ Auth::user()->name }}</strong>
                      </a>
                  </li>
                  <li class="w-full mb-2 items-center">
                      <a class="btn btn-primary w-full" href="{{ route('logout') }}"
                         onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();">
                        <span class="nav-link-title">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-logout" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                             <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                             <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"></path>
                             <path d="M9 12h12l-3 -3"></path>
                             <path d="M18 15l3 -3"></path>
                          </svg>
                          Salir
                        </span>
                      </a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                          @csrf
                      </form>
                  </li>
              </ul>
          </div>
      </div>
  </div>
</div>
