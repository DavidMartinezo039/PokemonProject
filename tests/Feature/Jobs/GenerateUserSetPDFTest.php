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
    $userSet = UserSet::factory()->create([
        'image' => 'View/predetermined/default2.png',
    ]);

    Pdf::shouldReceive('loadView')->once()->andReturnSelf();
    Pdf::shouldReceive('output')->once()->andReturn('pdf-content');

    (new GenerateUserSetPdf($userSet))->handle();

    Storage::disk('public')->assertExists("pdfs/user_sets/{$userSet->id}.pdf");
});
