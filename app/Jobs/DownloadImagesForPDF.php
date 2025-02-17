<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\UserSet;

class DownloadImagesForPDF implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userSet;

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

        Storage::disk('public')->makeDirectory($directory);

        if ($this->userSet->image) {
            $imageUrl = asset('storage/' . $this->userSet->image);
            $this->downloadImage($imageUrl, "{$directory}set_image.jpg");
        }

        foreach ($this->userSet->cards as $index => $card) {
            if (isset($card->images['small'])) {
                $this->downloadImage($card->images['small'], "{$directory}card_{$index}.jpg");
            }
        }
    }


    private function downloadImage($url, $path)
    {
            $response = Http::get($url);

            if ($response->successful()) {
                Storage::disk('public')->put($path, $response->body());
            }
    }
}
