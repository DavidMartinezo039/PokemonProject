<div class="container">
    <h1>Detalles del Set: {{ $set->name }}</h1>

    <!-- Información del Set -->
    <div class="card">
        <div class="card-body">
            <h3>{{ $set->name }}</h3>
            <p><strong>Fecha de lanzamiento:</strong> {{ $set->releaseDate }}</p>
            <p><strong>Serie:</strong> {{ $set->series }}</p>
            <p><strong>Cartas totales:</strong> {{ $set->printedTotal }}</p>
            <p><strong>Cartas totales incluyendo especiales:</strong> {{ $set->total }}</p>
            <p><strong>Codigo de coleccion de TCG:</strong> {{ $set->ptcgoCode }}</p>

            <!-- Mostrar las imágenes con tamaño ajustado -->
            <div class="mb-4">
                <h4>Logo:</h4>
                <img src="{{ $set->images['logo'] }}" alt="Logo de {{ $set->name }}" style="width: 100px; height: 100px; object-fit: contain;">
            </div>
            <div>
                <h4>Símbolo:</h4>
                <img src="{{ $set->images['symbol'] }}" alt="Símbolo de {{ $set->name }}" style="width: 100px; height: 100px; object-fit: contain;">
            </div>
        </div>
    </div>
</div>
