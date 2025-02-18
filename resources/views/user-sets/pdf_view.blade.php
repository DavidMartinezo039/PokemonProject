<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $userSet->id }} - Cards</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .set-container {
            margin: 20px;
        }

        .set-logo {
            max-width: 200px;
            height: auto;
        }
    </style>

</head>
<body>

<div class="set-container">
    <h2>{{ $userSet->name }}</h2>
    <img src="{{ public_path($imageDirectory . 'set_image_' . pathinfo($userSet->image, PATHINFO_FILENAME) . '.jpg') }}" alt="Imagen del set" class="set-logo">
</div>

<table style="width: 100%; text-align: center; border-collapse: collapse;">
    <tr>
        @foreach ($userSet->cards as $index => $card)
            <td style="padding: 10px;">
                <img src="{{ public_path($imageDirectory . 'card_' . $index . '_' . $card->id . '.jpg') }}"
                     alt="{{ $card->name }}"
                     style="max-width: 120px; height: auto;">
            </td>
            @if (($index + 1) % 3 == 0)
    </tr>
    <tr>
        @endif
        @endforeach
    </tr>
</table>


</body>
</html>
