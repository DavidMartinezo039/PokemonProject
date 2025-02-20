<?php

use App\View\Components\AppLayout;
use function Pest\Laravel\get;

it('renders the app layout view', function () {
    $component = new AppLayout();

    $view = $component->render();

    expect($view->getName())->toBe('layouts.app');
});

it('loads the welcome page with the correct layout and content', function () {
    $response = get('/');

    $response->assertStatus(200);

    $response->assertSeeHtml('<title>Inicio</title>');

    $response->assertSeeHtml('href="http://poketproject.test/View/css/navbar.css');
});
