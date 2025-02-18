<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\UserSet;

class DownloadImagesForPDF implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected UserSet $userSet;

    /**
     * Create a new job instance.
     */
    public function __construct(UserSet $userSet)
    {
        $this->userSet = $userSet;
    }

    /**
     * Execute the job.
     */

    public function handle()
    {
        $directory = "pdf_images/{$this->userSet->id}/";
        Log::info("Sets");


        Storage::disk('public')->makeDirectory($directory);

        if ($this->userSet->image) {
            $imageUrl = url('https://poketproject.test/storage/' . $this->userSet->image);
            $this->downloadImage($imageUrl, "{$directory}set_image_" . pathinfo($this->userSet->image, PATHINFO_FILENAME) . ".jpg");
        }

        foreach ($this->userSet->cards as $index => $card) {
            if (isset($card->images['small'])) {
                $this->downloadImage($card->images['small'], "{$directory}card_{$index}_{$card->id}.jpg");
            }
        }
    }


    private function downloadImage($url, $path)
    {
        Log::info("Intentando descargar imagen desde: $url");

        $response = Http::withOptions(['verify' => false])->get($url);

        if ($response->successful()) {
            Log::info("Descarga exitosa: $url -> Guardado en: $path");
            Storage::disk('public')->put($path, $response->body());
        } else {
            Log::error("Error al descargar imagen desde: $url");
        }
    }

}
