@extends('layouts.navbar-layout')

@section('title', 'Lista de Sets')

@section('content')
    <h1>Lista de Sets</h1>

    @if(session('message'))
        <div class="alert alert-info">
            {{ session('message') }}
        </div>
    @endif

    <ul>
        @foreach ($sets as $set)
            <li>
                <a href="{{ route('user-sets.show', $set->id) }}">
                    <img src="{{ $set->image }}" alt="Logo de {{ $set->name }}" style="width: 100px; height: 100px; object-fit: contain;">
                    {{ $set->name }}
                </a>
                <a href="{{ route('user-sets.edit', $set->id) }}">Editar</a>
                <form action="{{ route('user-sets.destroy', $set->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Eliminar</button>
                </form>
            </li>
        @endforeach
    </ul>

    <a href="{{ route('user-sets.create') }}">Crear nuevo set</a>
@endsection
