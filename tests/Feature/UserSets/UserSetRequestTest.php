<?php

namespace Tests\Feature\UserSets;

use App\Http\Requests\UserSetRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UserSetRequestTest extends TestCase
{
    public function test_user_set_name_is_required()
    {
        $data = [
            'description' => 'Una descripción de ejemplo',
            'image' => null,
        ];

        $validator = Validator::make($data, (new UserSetRequest())->rules(), (new UserSetRequest())->messages());

        $this->assertTrue($validator->fails());
        $this->assertEquals('El nombre es obligatorio.', $validator->errors()->first('name'));
    }

    public function test_user_set_name_must_be_a_string()
    {
        $data = [
            'name' => 12345,
            'description' => 'Una descripción de ejemplo',
            'image' => null,
        ];

        $validator = Validator::make($data, (new UserSetRequest())->rules(), (new UserSetRequest())->messages());

        $this->assertTrue($validator->fails());
        $this->assertEquals('El nombre debe ser una cadena de texto.', $validator->errors()->first('name'));
    }

    public function test_user_set_name_must_not_exceed_255_characters()
    {
        $data = [
            'name' => str_repeat('a', 256),
            'description' => 'Una descripción de ejemplo',
            'image' => null,
        ];

        $validator = Validator::make($data, (new UserSetRequest())->rules(), (new UserSetRequest())->messages());

        $this->assertTrue($validator->fails());
        $this->assertEquals('El nombre no puede superar los 255 caracteres.', $validator->errors()->first('name'));
    }

    public function test_user_set_image_must_be_a_valid_image()
    {
        $data = [
            'name' => 'Nombre válido',
            'description' => 'Una descripción de ejemplo',
            'image' => 'archivo-no-valido.pdf',
        ];

        $validator = Validator::make($data, (new UserSetRequest())->rules(), (new UserSetRequest())->messages());

        $this->assertTrue($validator->fails());
        $this->assertEquals('El archivo debe ser una imagen.', $validator->errors()->first('image'));
    }

    public function test_user_set_request_passes_with_valid_data()
    {
        $data = [
            'name' => 'Nombre válido',
            'description' => 'Una descripción opcional',
            'image' => UploadedFile::fake()->image('imagen.jpg', 100, 100), // Imagen válida
        ];

        $validator = Validator::make($data, (new UserSetRequest())->rules(), (new UserSetRequest())->messages());

        $this->assertTrue($validator->passes());
    }

    public function test_user_set_description_can_be_null()
    {
        $data = [
            'name' => 'Nombre válido',
            'description' => null,
            'image' => null,
        ];

        $validator = Validator::make($data, (new UserSetRequest())->rules(), (new UserSetRequest())->messages());

        $this->assertTrue($validator->passes());
    }

    public function test_user_set_description_can_be_a_valid_string()
    {
        $data = [
            'name' => 'Nombre válido',
            'description' => 'Una descripción válida.',
            'image' => null,
        ];

        $validator = Validator::make($data, (new UserSetRequest())->rules(), (new UserSetRequest())->messages());

        $this->assertTrue($validator->passes());
    }

    public function test_user_set_description_must_be_a_string_if_provided()
    {
        $data = [
            'name' => 'Nombre válido',
            'description' => ['array_invalido'],
            'image' => null,
        ];

        $validator = Validator::make($data, (new UserSetRequest())->rules(), (new UserSetRequest())->messages());

        $this->assertTrue($validator->fails());
        $this->assertEquals('La descripción debe ser una cadena de texto.', $validator->errors()->first('description'));
    }
}
