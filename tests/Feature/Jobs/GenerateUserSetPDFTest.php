<?php

use App\Jobs\GenerateUserSetPdf;
use App\Jobs\DownloadImagesForPDF;
use App\Models\Card;
use App\Models\User;
use App\Models\UserSet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Queue;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    Storage::fake('public');
    Log::spy();
});

it('generates a PDF successfully', function () {
    $userSet = UserSet::factory()->create();

    Pdf::shouldReceive('loadView')->once()->andReturnSelf();
    Pdf::shouldReceive('output')->once()->andReturn('pdf-content');

    (new GenerateUserSetPdf($userSet))->handle();

    Storage::disk('public')->assertExists("pdfs/user_sets/{$userSet->id}.pdf");
    Log::shouldHaveReceived('info')->with("PDF generado y guardado en: pdfs/user_sets/{$userSet->id}.pdf");
});

it('logs an error if user set does not exist', function () {
    $userSet = UserSet::factory()->make();
    $userSet->id = 999;

    (new GenerateUserSetPdf($userSet))->handle();

    Log::shouldHaveReceived('error')->with("UserSet ID: 999 no encontrado.");
});


it('deletes images of removed cards when regenerating PDF', function () {
    Storage::fake('public'); // Simula el almacenamiento

    // 1️⃣ Crear usuario y UserSet
    $user = User::factory()->create();
    $userSet = UserSet::factory()->for($user)->create([
        'image' => 'set_image.jpg',
    ]);

    // 2️⃣ Crear cartas y agregarlas al UserSet
    $cards = Card::factory()->count(3)->create();
    $userSet->cards()->attach($cards->pluck('id'));

    // 📂 Simular imágenes iniciales en almacenamiento
    $imageDirectory = "pdf_images/{$userSet->id}/";
    $initialImages = [
        "{$imageDirectory}set_image.jpg",
        "{$imageDirectory}card_1.jpg",
        "{$imageDirectory}card_2.jpg",
        "{$imageDirectory}card_3.jpg",
    ];

    foreach ($initialImages as $image) {
        Storage::disk('public')->put($image, 'dummy content');
    }

    // 3️⃣ Generar el PDF por primera vez
    Queue::fake();
    GenerateUserSetPdf::dispatchSync($userSet);

    // Asegurar que todas las imágenes iniciales existen
    foreach ($initialImages as $image) {
        Storage::disk('public')->assertExists($image);
    }

    // 4️⃣ Eliminar una carta del UserSet
    $removedCard = $cards->first();
    $userSet->cards()->detach($removedCard->id);

    // 5️⃣ Regenerar el PDF
    GenerateUserSetPdf::dispatchSync($userSet);

    // 📂 Verificar que la imagen de la carta eliminada ha sido borrada
    Storage::disk('public')->assertMissing("{$imageDirectory}card_1.jpg");
})->todo();
