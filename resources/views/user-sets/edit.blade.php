@extends('layouts.navbar-layout')

@section('title', 'Editar User Set')

@section('additional-css')
    <link rel="stylesheet" href="{{ asset('View/css/User-Sets/create-user-set.css') }}">
@endsection

@section('content')
    <div class="user-set-container">
        <h1>{{__('Set Update')}}</h1>
        <form action="{{ route('user-sets.update', $userSet->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">{{__('Set Name')}}:</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $userSet->name) }}" required>
            </div>

            <div class="form-group">
                <label for="description">{{__('Description')}}:</label>
                <textarea id="description" name="description" class="form-control">{{ old('description', $userSet->description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="image">{{__('Set Image')}}:</label>
                <input type="file" id="image" name="image" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">{{__('Set Update')}}</button>
        </form>
    </div>
@endsection
