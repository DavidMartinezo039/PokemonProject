<?php

namespace Database\Seeders;

use App\Models\Card;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $url = 'https://api.pokemontcg.io/v2/cards';
        $page = 1;
        $pageSize = 250; // Tamaño máximo permitido por la API

        do {
            // Realizamos la solicitud a la API con paginación
            $response = Http::withHeaders([
                'Authorization' => 'Bearer 5cc3c6ec-af79-4bc8-b926-530b88a3d37b',
            ])->get($url, [
                'page' => $page,
                'pageSize' => $pageSize,
            ]);

            if ($response->failed()) {
                $this->command->error('Failed to fetch data from the API at page ' . $page);
                break;
            }

            $cards = $response->json('data');
            $this->command->info('Fetched ' . count($cards) . ' cards from page ' . $page);

            // Guardamos cada carta en la base de datos
            foreach ($cards as $card) {
                Card::updateOrCreate(
                    ['id' => $card['id']],
                    [
                        'name' => $card['name'],
                        'supertype' => $card['supertype'] ?? null,
                        'subtypes' => $card['subtypes'] ?? null,
                        'level' => $card['level'] ?? null,
                        'hp' => $card['hp'] ?? null,
                        'types' => $card['types'] ?? null,
                        'evolves_to' => $card['evolvesTo'] ?? null,
                        'attacks' => $card['attacks'] ?? null,
                        'weaknesses' => $card['weaknesses'] ?? null,
                        'retreat_cost' => $card['retreatCost'] ?? null,
                        'converted_retreat_cost' => $card['convertedRetreatCost'] ?? null,
                        'set_id' => $card['set']['id'] ?? null,
                        'set_name' => $card['set']['name'] ?? null,
                        'rarity' => $card['rarity'] ?? null,
                        'flavor_text' => $card['flavorText'] ?? null,
                        'national_pokedex_numbers' => $card['nationalPokedexNumbers'] ?? null,
                        'small_image_url' => $card['images']['small'] ?? null,
                        'large_image_url' => $card['images']['large'] ?? null,
                        'tcgplayer_url' => $card['tcgplayer']['url'] ?? null,
                        'cardmarket_url' => $card['cardmarket']['url'] ?? null,
                        'prices' => $card['cardmarket']['prices'] ?? null,
                    ]
                );
            }

            // Incrementamos la página para la siguiente solicitud
            $page++;
        } while (count($cards) > 0); // Si ya no hay más cartas, el bucle termina
    }
}
