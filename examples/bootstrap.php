<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Factory\Guzzle\RequestFactory;
use Http\Factory\Guzzle\StreamFactory;

require_once __DIR__.'/../vendor/autoload.php';

if (!file_exists('.env')) {
    $env = <<<TEXT
CLIENT_iD=123
API_KEY=123
TEXT;
    file_put_contents('.env', $env);
    unset($env);
}

$dotenv = Dotenv::createImmutable(__DIR__);
$env = $dotenv->load();

return [
    [
        'clientId' => $env['CLIENT_iD'],
        'apiKey'   => $env['API_KEY'],
    ],
    new GuzzleAdapter(new GuzzleClient()),
    new RequestFactory(),
    new StreamFactory(),
];
