<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Rarity;
use App\Models\Supertype;
use App\Models\Type;
use App\Models\Subtype;
use Illuminate\Database\Seeder;
use GuzzleHttp\Client;
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

        $supertypes = Supertype::pluck('id', 'name')->toArray();
        $rarities = Rarity::pluck('id', 'name')->toArray();
        $types = Type::pluck('id', 'name')->toArray();
        $subtypes = Subtype::pluck('id', 'name')->toArray();

        $client = new Client([
            'verify' => false,
        ]);

        do {
            $response = $client->get('https://api.pokemontcg.io/v2/cards', [
                'query' => [
                    'page' => $page,
                    'pageSize' => $perPage,
                ],
            ]);

            $cards = json_decode($response->getBody()->getContents(), true)['data'];

            if (!$cards) {
                break;
            }

            DB::transaction(function () use ($cards, $supertypes, $rarities, $types, $subtypes) {
                foreach ($cards as $cardData) {
                    $this->createCard($cardData, $supertypes, $rarities, $types, $subtypes);
                }
            });

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
        $supertypeId = isset($cardData['supertype']) && array_key_exists($cardData['supertype'], $supertypes)
            ? $supertypes[$cardData['supertype']]
            : null;

        $rarityId = isset($cardData['rarity']) && array_key_exists($cardData['rarity'], $rarities)
            ? $rarities[$cardData['rarity']]
            : null;

        $card = Card::Create(
            [
                'id' => $cardData['id'],
                'name' => $cardData['name'],
                'supertype_id' => $supertypeId,
                'level' => $cardData['level'] ?? null,
                'hp' => $cardData['hp'] ?? null,
                'evolvesFrom' => $cardData['evolvesFrom'] ?? null,
                'evolvesTo' => $cardData['evolvesTo'] ?? null,
                'attacks' => $cardData['attacks'] ?? null,
                'weaknesses' => $cardData['weaknesses'] ?? null,
                'resistances' => $cardData['resistances'] ?? null,
                'retreatCost' => $cardData['retreatCost'] ?? null,
                'convertedRetreatCost' => $cardData['convertedRetreatCost'] ?? null,
                'set_id' => $cardData['set']['id'] ?? null,
                'number' => $cardData['number'] ?? null,
                'artist' => $cardData['artist'] ?? null,
                'rarity_id' => $rarityId,
                'flavorText' => $cardData['flavorText'] ?? null,
                'nationalPokedexNumbers' => $cardData['nationalPokedexNumbers'] ?? null,
                'legalities' => $cardData['legalities'] ?? null,
                'regulationMark' => $cardData['regulationMark'] ?? null,
                'images' => $cardData['images'] ?? null,
                'tcgplayer' => $cardData['tcgplayer'] ?? null,
                'cardmarket' => $cardData['cardmarket'] ?? null,
            ]
        );

        $typeIds = isset($cardData['types']) && is_array($cardData['types'])
            ? array_map(fn($type) => $types[$type] ?? null, $cardData['types'])
            : [];

        $subtypeIds = isset($cardData['subtypes']) && is_array($cardData['subtypes'])
            ? array_map(fn($subtype) => $subtypes[$subtype] ?? null, $cardData['subtypes'])
            : [];

        $typeIds = array_filter($typeIds);
        $subtypeIds = array_filter($subtypeIds);

        if (!empty($typeIds)) {
            $card->types()->sync($typeIds);
        }

        if (!empty($subtypeIds)) {
            $card->subtypes()->sync($subtypeIds);
        }
    }
}
