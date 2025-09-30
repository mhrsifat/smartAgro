<?php

return [
    'name' => 'SmartAgro',
    'logoUrl' => null, // Or '/storage/logo.png'

    // Main nav
    'nav' => [
      ['key' => 'home', 'name' => 'Home', 'url' => '/', 'icon' => 'heroicon-o-home'],
        ['key' => 'research', 'name' => 'Research', 'url' => '/research', 'icon' => 'heroicon-o-beaker'],
        ['key' => 'service', 'name' => 'Service', 'url' => '#', 'hasChildren' => true, 'icon' => 'heroicon-o-wrench'],
        ['key' => 'blogs', 'name' => 'Blog', 'url' => '/blogs', 'icon' => 'heroicon-o-newspaper'],
        ['key' => 'impact', 'name' => 'Impact', 'url' => '/impact', 'icon' => 'heroicon-o-chart-bar'],
        ['key' => 'where', 'name' => 'Achievements', 'url' => '/achievements', 'icon' => 'heroicon-o-globe-alt'],
        ['key' => 'success', 'name' => 'Success Stories', 'url' => '/success-stories', 'icon' => 'heroicon-o-star'],
        ['key' => 'donation', 'name' => 'Donation', 'url' => '/donation', 'icon' => 'heroicon-o-users'],
    ],

    // Service dropdown
    'serviceItems' => [
        ['key' => 'crop-planner', 'name' => 'Crop Planner', 'url' => '/crop-planner', 'icon' => 'heroicon-o-sparkles'],
        ['key' => 'pesticide', 'name' => 'Pesticide', 'url' => '/pesticide-suggestor', 'icon' => 'heroicon-o-beaker'],
        ['key' => 'fertilizer', 'name' => 'Fertilizer', 'url' => '/fertilizer-suggestor', 'icon' => 'heroicon-o-cube'],
        ['key' => 'disease', 'name' => 'Disease', 'url' => '/disease', 'icon' => 'heroicon-o-exclamation-triangle'],
    ],

    // Languages
    'languages' => [
        ['code' => 'en', 'name' => 'English'],
        ['code' => 'bn', 'name' => 'বাংলা (BN)'],
        ['code' => 'ar', 'name' => 'العربية'],
    ],
];