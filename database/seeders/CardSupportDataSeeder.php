<?php

namespace Database\Seeders;

use App\Models\Set;
use App\Models\Type;
use App\Models\Supertype;
use App\Models\Rarity;
use App\Models\Subtype;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class CardSupportDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener datos desde la API
        $sets = $this->getSetsFromApi();
        $types = $this->getTypesFromApi();
        $supertypes = $this->getSupertypesFromApi();
        $rarities = $this->getRaritiesFromApi();
        $subtypes = $this->getSubtypesFromApi();

        // Insertar sets
        foreach ($sets as $set) {
            Set::updateOrCreate(
                ['id' => $set['id']],
                [
                    'name' => $set['name'],
                    'series' => $set['series'],
                    'printedTotal' => $set['printedTotal'] ?? null,
                    'total' => $set['total'] ?? null,
                    'legalities' => json_encode($set['legalities']),
                    'ptcgoCode' => $set['ptcgoCode'] ?? null,
                    'releaseDate' => $set['releaseDate'] ?? null,
                    'updatedAt' => $set['updatedAt'] ?? null,
                    'images' => json_encode($set['images']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Insertar tipos
        foreach ($types as $type) {
            Type::updateOrCreate(['name' => $type]);
        }

        // Insertar supertypes
        foreach ($supertypes as $supertype) {
            Supertype::updateOrCreate(['name' => $supertype]);
        }

        // Insertar raridades
        foreach ($rarities as $rarity) {
            Rarity::updateOrCreate(['name' => $rarity]);
        }

        // Insertar subtipos
        foreach ($subtypes as $subtype) {
            Subtype::updateOrCreate(['name' => $subtype]);
        }
    }

    /**
     * Obtener los sets desde la API.
     *
     * @return array
     */
    private function getSetsFromApi()
    {
        $response = Http::timeout(30) // Aumentar el tiempo de espera a 30 segundos
        ->get('https://api.pokemontcg.io/v2/sets');
        return $response->json('data');
    }

    /**
     * Obtener los tipos desde la API.
     *
     * @return array
     */
    private function getTypesFromApi()
    {
        $response = Http::get('https://api.pokemontcg.io/v2/types');
        return $response->json('data');
    }

    /**
     * Obtener los supertypes desde la API.
     *
     * @return array
     */
    private function getSupertypesFromApi()
    {
        $response = Http::get('https://api.pokemontcg.io/v2/supertypes');
        return $response->json('data');
    }

    /**
     * Obtener las raridades desde la API.
     *
     * @return array
     */
    private function getRaritiesFromApi()
    {
        $response = Http::get('https://api.pokemontcg.io/v2/rarities');
        return $response->json('data');
    }

    /**
     * Obtener los subtipos desde la API.
     *
     * @return array
     */
    private function getSubtypesFromApi()
    {
        $response = Http::get('https://api.pokemontcg.io/v2/subtypes');
        return $response->json('data');
    }
}
