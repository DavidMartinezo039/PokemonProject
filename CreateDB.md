# Relaciones
## SET
  * 1 set tiene muchas cartas
  * 1 carta pertenece a 1 set

## SUPERTYPE
  * 1 supertype tiene muchas cartas
  * 1 carta pertenece a 1 supertype

## RARITIES
  * 1 rarity tiene muchas cartas
  * 1 carta pertenece a 1 rarity

## TYPES
  * 1 type tiene muchas cartas
  * 1 carta tiene muchos types
  * Crear tabla intermedia types_cards

## SUBTYPES
  * 1 subtype tiene muchas cartas
  * 1 carta tiene muchos subtypes
  * Crear tabla intermedia subtypes_cards
 
# Atributos
## Cards
  * id (string primary key)
  * name
  * supertype_id (foreign key)
  * level
  * hp
  * evolvesFrom
  * evolvesTo
  * rules
  * ancientTrait
  * abilities
  * attacks
  * weaknesses
  * resistances
  * retreatCost
  * convertedRetreatCost
  * set_id (foreign key)
  * number
  * artist
  * rarity_id (foreign key)
  * flavorText
  * nationalPokedexNumbers
  * legalities
  * regulationMark
  * images
  * tcgplayer
  * cardmarket

## Sets
  * id
  * name
  * series
  * printedTotal
  * total
  * legalities
  * ptcgoCode
  * releaseDate
  * updatedAt (no hace falta si tienes timestamps)
  * images

## Supertypes
  * id
  * name

## Rarities
  * id
  * name

## Types
  * id
  * name

## Subtypes
  * id
  * name

## TypesCards
  * type_id
  * card_id

## SubtypesCards
  * subtype_id
  * card_id
