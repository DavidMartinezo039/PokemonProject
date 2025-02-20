<?php

use App\Models\Card;
use App\Models\UserSet;
use App\Jobs\DownloadImagesForPDF;
use Illuminate\Support\Facades\Queue;
use function Pest\Laravel\actingAs;

it('should generate a PDF successfully', function () {
    $user = CreateUser();
    actingAs($user);

    $userSet = UserSet::factory()->create();
    $userSetId = $userSet->id;

    $response = $this->get(route('generar-pdf', ['userSet' => $userSet]));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/pdf');
    $response->assertHeader('Content-Disposition', 'attachment; filename=' . $userSet->id . '.pdf');
});
