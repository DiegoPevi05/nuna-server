@extends('layouts.app')
@section('content')
    <div class="page-wrapper">
        <div class="card">
          <div class="card-status-start bg-green"></div>
          <div class="card-body">
           <h3 class="card-title">Bienvenido a Nuna</h3>
           <p class="text-secondary">Hola {{ $user->name }}</p>
           <p class="text-secondary">En este panel vas a poder ver tus sesiones programadas y modificar tu información de usuario</p>
          </div>
        </div>
    </div>
@endsection

