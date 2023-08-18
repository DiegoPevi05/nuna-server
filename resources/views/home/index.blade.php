@extends('layouts.app')
@section('content')
    <div class="page-wrapper">
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            Panel de Inicio
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-xl">
                <div class="row row-deck row-cards">
                    <div class="col-lg-6">
                        <div class="row row-cards">
                            <div class="col-12">
                                <div class="card">
                                  <div class="card-body">
                                   <div class="d-flex align-items-center">
                                    <div class="subheader">Total Ingresos</div>
                                    <div class="ms-auto lh-1">
                                      <div class="dropdown">
                                       <a class="dropdown-toggle text-secondary" href="{{ route('home.index', ['page' => $logs->currentPage(), 'import' => 1]) }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Este Mes</a>
                                       <div class="dropdown-menu dropdown-menu-end">
                                         <a class="dropdown-item active" href="{{ route('home.index', ['page' => $logs->currentPage(), 'import' => 1]) }}">Este Mes</a>
                                        <a class="dropdown-item" href="{{ route('home.index', ['page' => $logs->currentPage(), 'import' => 2]) }}">El mes pasado</a>
                                        <a class="dropdown-item" href="{{ route('home.index', ['page' => $logs->currentPage(), 'import' => 3]) }}">Los ultimos tres meses</a>
                                       </div>
                                      </div>
                                    </div>
                                   </div>
                                   <div class="d-flex align-items-baseline">
                                    <div class="h1 mb-3 me-2">S/.{{$import_total}}</div>
                                   </div>
                                   <div class="mt-2">
                                    @php
                                        use Carbon\Carbon;

                                        $currentDate = Carbon::now();
                                        $currentWeek = $currentDate->weekOfMonth;
                                        $currentMonth = $currentDate->month;
                                    @endphp
                                    <div class="tracking">
                                        @for ($week = 1; $week <= 5; $week++)
                                            <div class="tracking-block
                                                @if ($week < $currentWeek)
                                                    bg-success
                                                @elseif ($week === $currentWeek)
                                                    bg-warning
                                                @endif"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="
                                                    @if ($week < $currentWeek)
                                                        Operational
                                                    @elseif ($week === $currentWeek)
                                                        Big load
                                                    @else
                                                        No data
                                                    @endif"
                                            ></div>
                                        @endfor
                                    </div>
                                   </div>
                                  </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card">
                                  <div class="card-body">
                                    <div id="chart-demo-line" class="chart-lg"></div>
                                  </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card">
                                  <div class="card-body">
                                    <div id="chart-demo-bar" class="chart-lg"></div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row row-cards">
                            <div class="card">

                                <h3 class="card-title p-3">Notificaciones</h3>
                                @if($logs)
                                    @foreach($logs as $index => $log)
                                    <div class="col-12">
                                        <div class="card mb-3 fade-in-notification" style="animation-delay: {{  $index * 100 }}ms;">
                                          <div class="card-body" style="padding-left:60px">
                                              <div class="row">
                                                  <div class="col-10">
                                                    <strong>Usuario:</strong>: {{$log->user_name}}
                                                  </div>
                                                  <div class="col-2">
                                                      <strong>
                                                          @php
                                                              $now = now();
                                                              $logDate = $log->created_at;
                                                              if ($logDate->isToday()) {
                                                                  echo $logDate->format('H:i');
                                                              } elseif ($logDate->isYesterday()) {
                                                                  echo 'Ayer';
                                                              } elseif ($logDate->isThisWeek()) {
                                                                  echo 'Esta semana';
                                                              } elseif ($logDate->isThisMonth()) {
                                                                  echo 'Este mes';
                                                              } else {
                                                                  echo $logDate->format('d/m/Y');
                                                              }
                                                          @endphp
                                                      </strong>
                                                  </div>
                                              </div>
                                              <div class="col">
                                                  <strong>Mensaje:</strong> {{$log->message}}
                                              </div>
                                          </div>
                                          @if ($log->type === 'SUCCESS')
                                            
                                            <div class="ribbon ribbon-top ribbon-start bg-green" style="height:20px">
                                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                 <path d="M5 12l5 5l10 -10"></path>
                                              </svg>
                                            </div>

                                          @elseif($log->type === 'WARNING')

                                            <div class="ribbon ribbon-top ribbon-start bg-yellow" style="height:20px">
                                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-triangle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                  <path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z"></path>
                                                  <path d="M12 9v4"></path>
                                                  <path d="M12 17h.01"></path>
                                              </svg>
                                            </div>

                                          @elseif($log->type === 'INFO')

                                            <div class="ribbon ribbon-top ribbon-start" style="height:20px">
                                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-exclamation-mark" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                 <path d="M12 19v.01"></path>
                                                 <path d="M12 15v-10"></path>
                                              </svg>
                                            </div>

                                          @elseif($log->type === 'ERROR')

                                            <div class="ribbon ribbon-top ribbon-start bg-red" style="height:20px">
                                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-exclamation-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                 <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                                                 <path d="M12 9v4"></path>
                                                 <path d="M12 16v.01"></path>
                                              </svg>
                                            </div>
                                          @endif
                                        </div>
                                    </div>
                                  @endforeach
                                @endif
                                <div class="col-12 text-center" style="height:60px">
                                  <a href="{{ route('home.index', ['page' => max(1, $logs->currentPage() - 1)]) }}" class="btn btn-pill">
                                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                          <path d="M15 6l-6 6l6 6"></path>
                                      </svg>
                                  </a>
                                  <a href="{{ route('home.index', ['page' => $logs->currentPage() + 1]) }}" class="btn btn-pill">
                                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-right" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                          <path d="M9 6l6 6l-6 6"></path>
                                      </svg>
                                  </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('config.home')
@endsection

