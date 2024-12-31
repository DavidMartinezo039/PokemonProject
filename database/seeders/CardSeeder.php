<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Rarity;
use App\Models\Supertype;
use App\Models\Type;
use App\Models\Subtype;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedCards();
    }

    /**
     * Seed cards from the Pokémon TCG API.
     */
    private function seedCards()
    {
        $page = 1;
        $perPage = 250;
        $totalCards = 0;

        // Cargar mapas de supertypes y rarities una vez
        $supertypes = Supertype::pluck('id', 'name')->toArray();
        $rarities = Rarity::pluck('id', 'name')->toArray();
        $types = Type::pluck('id', 'name')->toArray();
        $subtypes = Subtype::pluck('id', 'name')->toArray();

        do {
            $response = Http::timeout(30)->get('https://api.pokemontcg.io/v2/cards', [
                'page' => $page,
                'pageSize' => $perPage,
            ]);

            $cards = $response->json('data');

            if (!$cards) {
                break;
            }

            // Insertar todas las cartas en una transacción
            DB::transaction(function () use ($cards, $supertypes, $rarities, $types, $subtypes) {
                foreach ($cards as $cardData) {
                    $this->createCard($cardData, $supertypes, $rarities, $types, $subtypes);
                }
            });

            // Reconectar base de datos para liberar memoria
            DB::disconnect();
            DB::reconnect();

            $totalCards += count($cards);
            echo "Cartas procesadas hasta ahora: {$totalCards}\n";
            $page++;
        } while (count($cards) === $perPage);

        echo "Total de cartas insertadas: {$totalCards}\n";
    }

    /**
     * Create or update a card in the database.
     *
     * @param array $cardData
     */
    private function createCard(array $cardData, array $supertypes, array $rarities, array $types, array $subtypes)
    {
        // Validar y obtener los IDs de supertypes y rarities desde los mapas
        $supertypeId = isset($cardData['supertype']) && array_key_exists($cardData['supertype'], $supertypes)
            ? $supertypes[$cardData['supertype']]
            : null;

        $rarityId = isset($cardData['rarity']) && array_key_exists($cardData['rarity'], $rarities)
            ? $rarities[$cardData['rarity']]
            : null;

        // Insert the card
        $card = Card::Create(
            [
                'id' => $cardData['id'],
                'name' => $cardData['name'],
                'supertype_id' => $supertypeId,
                'level' => $cardData['level'] ?? null,
                'hp' => $cardData['hp'] ?? null,
                'evolvesFrom' => $cardData['evolvesFrom'] ?? null,
                'evolvesTo' => isset($cardData['evolvesTo']) ? json_encode($cardData['evolvesTo']) : null,
                'attacks' => isset($cardData['attacks']) ? json_encode($cardData['attacks']) : null,
                'weaknesses' => isset($cardData['weaknesses']) ? json_encode($cardData['weaknesses']) : null,
                'resistances' => isset($cardData['resistances']) ? json_encode($cardData['resistances']) : null,
                'retreatCost' => isset($cardData['retreatCost']) ? json_encode($cardData['retreatCost']) : null,
                'convertedRetreatCost' => $cardData['convertedRetreatCost'] ?? null,
                'set_id' => $cardData['set']['id'] ?? null,
                'number' => $cardData['number'] ?? null,
                'artist' => $cardData['artist'] ?? null,
                'rarity_id' => $rarityId,
                'flavorText' => $cardData['flavorText'] ?? null,
                'nationalPokedexNumbers' => isset($cardData['nationalPokedexNumbers']) ? json_encode($cardData['nationalPokedexNumbers']) : null,
                'legalities' => isset($cardData['legalities']) ? json_encode($cardData['legalities']) : null,
                'regulationMark' => $cardData['regulationMark'] ?? null,
                'images' => isset($cardData['images']) ? json_encode($cardData['images']) : null,
                'tcgplayer' => isset($cardData['tcgplayer']) ? json_encode($cardData['tcgplayer']) : null,
                'cardmarket' => isset($cardData['cardmarket']) ? json_encode($cardData['cardmarket']) : null,
            ]
        );

        // Relacionar tipos y subtipos
        $typeIds = isset($cardData['types']) && is_array($cardData['types'])
            ? array_map(fn($type) => $types[$type] ?? null, $cardData['types'])
            : [];

        $subtypeIds = isset($cardData['subtypes']) && is_array($cardData['subtypes'])
            ? array_map(fn($subtype) => $subtypes[$subtype] ?? null, $cardData['subtypes'])
            : [];

        // Filtrar IDs nulos
        $typeIds = array_filter($typeIds);
        $subtypeIds = array_filter($subtypeIds);

        // Sincronizar relaciones
        if (!empty($typeIds)) {
            $card->types()->sync($typeIds);
        }

        if (!empty($subtypeIds)) {
            $card->subtypes()->sync($subtypeIds);
        }
    }
}
