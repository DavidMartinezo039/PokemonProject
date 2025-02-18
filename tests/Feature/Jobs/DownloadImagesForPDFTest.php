<?php

use App\Jobs\DownloadImagesForPDF;
use App\Models\Card;
use App\Models\UserSet;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

it('creates a directory for images', function () {
    $userSet = UserSet::factory()->create();

    Storage::fake('public');

    (new DownloadImagesForPDF($userSet))->handle();

    Storage::disk('public')->assertExists("pdf_images/{$userSet->id}");
});

it('downloads card images', function () {
    $userSet = UserSet::factory()->create();

    $card = Card::factory()->create([
        'images' => ['small' => 'http://example.com/card_image.jpg'],
    ]);

    $userSet->cards()->attach($card->id);

    Storage::fake('public');
    Http::fake([
        'http://example.com/card_image.jpg' => Http::response('fake-card-image-content', 200),
    ]);

    (new DownloadImagesForPDF($userSet))->handle();

    Storage::disk('public')->assertExists("pdf_images/{$userSet->id}/card_0_{$card->id}.jpg");
});


it('does not download images if not successful', function () {
    $userSet = UserSet::factory()->create();

    Storage::fake('public');
    Http::fake([
        'http://example.com/card_image.jpg' => Http::response('Error', 500),
    ]);

    (new DownloadImagesForPDF($userSet))->handle();

    Storage::disk('public')->assertMissing("pdf_images/{$userSet->id}/card_0.jpg");
});
