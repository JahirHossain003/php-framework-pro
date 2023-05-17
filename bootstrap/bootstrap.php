<?php

$providers = [
  \App\provider\EventServiceProvider::class
];

foreach ($providers as $provider) {
    $serviceProvider = $container->get($provider);
    $serviceProvider->register();
}