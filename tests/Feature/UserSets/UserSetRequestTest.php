<?php

namespace Tests\Feature\UserSets;

use App\Http\Requests\UserSetRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\UploadedFile;

it('validates that the name is required', function () {
    $data = [
        'description' => 'Una descripción de ejemplo',
        'image' => null,
    ];

    $validator = Validator::make($data, (new UserSetRequest())->rules(), (new UserSetRequest())->messages());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->first('name'))->toEqual('El nombre es obligatorio.');
});

it('validates that the name must be a string', function () {
    $data = [
        'name' => 12345,
        'description' => 'Una descripción de ejemplo',
        'image' => null,
    ];

    $validator = Validator::make($data, (new UserSetRequest())->rules(), (new UserSetRequest())->messages());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->first('name'))->toEqual('El nombre debe ser una cadena de texto.');
});

it('validates that the name must not exceed 255 characters', function () {
    $data = [
        'name' => str_repeat('a', 256),
        'description' => 'Una descripción de ejemplo',
        'image' => null,
    ];

    $validator = Validator::make($data, (new UserSetRequest())->rules(), (new UserSetRequest())->messages());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->first('name'))->toEqual('El nombre no puede superar los 255 caracteres.');
});

it('validates that the image must be a valid image', function () {
    $data = [
        'name' => 'Nombre válido',
        'description' => 'Una descripción de ejemplo',
        'image' => 'archivo-no-valido.pdf',
    ];

    $validator = Validator::make($data, (new UserSetRequest())->rules(), (new UserSetRequest())->messages());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->first('image'))->toEqual('El archivo debe ser una imagen.');
});

it('passes with valid data', function () {
    $data = [
        'name' => 'Nombre válido',
        'description' => 'Una descripción opcional',
        'image' => UploadedFile::fake()->image('imagen.jpg', 100, 100),
    ];

    $validator = Validator::make($data, (new UserSetRequest())->rules(), (new UserSetRequest())->messages());

    expect($validator->passes())->toBeTrue();
});

it('passes when description can be null', function () {
    $data = [
        'name' => 'Nombre válido',
        'description' => null,
        'image' => null,
    ];

    $validator = Validator::make($data, (new UserSetRequest())->rules(), (new UserSetRequest())->messages());

    expect($validator->passes())->toBeTrue();
});

it('passes when description is a valid string', function () {
    $data = [
        'name' => 'Nombre válido',
        'description' => 'Una descripción válida.',
        'image' => null,
    ];

    $validator = Validator::make($data, (new UserSetRequest())->rules(), (new UserSetRequest())->messages());

    expect($validator->passes())->toBeTrue();
});

it('fails when description must be a string if provided', function () {
    $data = [
        'name' => 'Nombre válido',
        'description' => ['array_invalido'],
        'image' => null,
    ];

    $validator = Validator::make($data, (new UserSetRequest())->rules(), (new UserSetRequest())->messages());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->first('description'))->toEqual('La descripción debe ser una cadena de texto.');
});
