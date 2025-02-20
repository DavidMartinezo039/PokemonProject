@extends('layouts.navbar-layout')

@section('title', 'Crear User Set')

@section('additional-css')
    <link rel="stylesheet" href="{{ asset('View/css/User-Sets/create-user-set.css') }}">
@endsection

@section('content')
    <div class="user-set-container">
        <h1>{{__('Create New Set')}}</h1>
        <form action="{{ route('user-sets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">{{__('Set Name')}}:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="description">{{__('Description')}}:</label>
                <textarea id="description" name="description" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="image">{{__('Set Image')}}:</label>
                <input type="file" id="image" name="image" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">{{__('Create Set')}}</button>
        </form>
    </div>
@endsection
