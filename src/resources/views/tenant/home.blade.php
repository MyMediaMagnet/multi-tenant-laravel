@extends('multi-tenant::master')

@section('content')
    <div class="title m-b-md">
        Welcome to {{ \Auth::user()->activeTenant()->name }}
    </div>

    Starting doing some stuff since you are now active on a tenant
@endsection
