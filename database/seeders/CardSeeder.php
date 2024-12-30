<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Rarity;
use App\Models\Supertype;
use App\Models\Type;
use App\Models\Subtype;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

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

        do {
            $response = Http::timeout(30)->get('https://api.pokemontcg.io/v2/cards', [
                'page' => $page,
                'pageSize' => $perPage,
            ]);

            $cards = $response->json('data');

            if (!$cards) {
                break;
            }

            foreach ($cards as $cardData) {
                $this->createCard($cardData);
            }

            $totalCards += count($cards);
            $page++;
        } while (count($cards) === $perPage);

        echo "Total cards seeded: {$totalCards}\n";
    }

    /**
     * Create or update a card in the database.
     *
     * @param array $cardData
     */
    private function createCard(array $cardData)
    {
        echo "Procesando carta: {$cardData['id']} - {$cardData['name']}\n";
        $id = $cardData['id'];
        // Insert the card
        $card = Card::updateOrCreate(
            [
                'id' => $cardData['id'],
                'name' => $cardData['name'],
                'supertype_id' => $this->getSupertypeId($cardData['supertype']),
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
                'rarity_id' => isset($cardData['rarity']) ? $this->getRarityId($cardData['rarity']) : null,
                'flavorText' => $cardData['flavorText'] ?? null,
                'nationalPokedexNumbers' => isset($cardData['nationalPokedexNumbers']) ? json_encode($cardData['nationalPokedexNumbers']) : null,
                'legalities' => isset($cardData['legalities']) ? json_encode($cardData['legalities']) : null,
                'regulationMark' => $cardData['regulationMark'] ?? null,
                'images' => isset($cardData['images']) ? json_encode($cardData['images']) : null,
                'tcgplayer' => isset($cardData['tcgplayer']) ? json_encode($cardData['tcgplayer']) : null,
                'cardmarket' => isset($cardData['cardmarket']) ? json_encode($cardData['cardmarket']) : null,
            ]
        );

        $cards = Card::all();

// Asegúrate de que el ID es válido

        $card = Card::where('id', $id)->first();

        if (!$card) {
            throw new \Exception("La carta con ID {$id} no existe.");
        }

// Validar la existencia de 'types' y 'subtypes' antes de acceder a ellas
        $typeIds = isset($cardData['types']) && is_array($cardData['types'])
            ? Type::whereIn('name', $cardData['types'])->pluck('id')->toArray()
            : [];

        $subtypeIds = isset($cardData['subtypes']) && is_array($cardData['subtypes'])
            ? Subtype::whereIn('name', $cardData['subtypes'])->pluck('id')->toArray()
            : [];

// Sincronizar los tipos y subtipos con la carta
        if (!empty($typeIds)) {
            $card->types()->sync($typeIds);
        } else {
            echo "La carta con ID {$cardData['id']} no tiene tipos asociados.\n";
        }

        if (!empty($subtypeIds)) {
            $card->subtypes()->sync($subtypeIds);
        } else {
            echo "La carta con ID {$cardData['id']} no tiene subtipos asociados.\n";
        }
    }

    /**
     * Get the supertype ID by name.
     *
     * @param string|null $supertypeName
     * @return int|null
     */
    private function getSupertypeId(?string $supertypeName): ?int
    {
        return $supertypeName ? Supertype::where('name', $supertypeName)->value('id') : null;
    }

    /**
     * Get the rarity ID by name.
     *
     * @param string|null $rarityName
     * @return int|null
     */
    private function getRarityId(?string $rarityName): ?int
    {
        return $rarityName ? Rarity::where('name', $rarityName)->value('id') : null;
    }
}
