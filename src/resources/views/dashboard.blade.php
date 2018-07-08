@extends('multi-tenant::master')

@section('content')
    <div class="title m-b-md">
        Welcome to Multi Tenant
    </div>
    <p>Please select the tenant you'd like to manage</p>

    @if(\Auth::user()->owns()->count() > 0)
        <div class="blocks">
            @foreach(\Auth::user()->owns as $tenant)
                <form class="block" method="POST" action="{{ route('select-tenant') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{$tenant->id}}" />

                    <button type="submit">{{$tenant->name}}</button>
                </form>
            @endforeach
        </div>
    @else
        You don't own any tenants
    @endif
@endsection
