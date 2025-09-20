<?php

return [
    'name' => 'SmartAgro',
    'logoUrl' => null, // Or '/storage/logo.png'

    // Main nav
    'nav' => [
        ['key' => 'admin', 'name' => 'Admin Panel', 'url' => '/admin', 'icon' => 'heroicon-o-briefcase'],
        ['key' => 'home', 'name' => 'Home', 'url' => '/', 'icon' => 'heroicon-o-home'],
        ['key' => 'research', 'name' => 'Research', 'url' => '/research', 'icon' => 'heroicon-o-beaker'],
        ['key' => 'service', 'name' => 'Service', 'url' => '#', 'hasChildren' => true, 'icon' => 'heroicon-o-wrench'],
        ['key' => 'impact', 'name' => 'Impact', 'url' => '/impact', 'icon' => 'heroicon-o-chart-bar'],
        ['key' => 'where', 'name' => 'Where We Work', 'url' => '/where-we-work', 'icon' => 'heroicon-o-globe-alt'],
        ['key' => 'team', 'name' => 'Our Team', 'url' => '/our-team', 'icon' => 'heroicon-o-users'],
        ['key' => 'blog', 'name' => 'Blog', 'url' => '/blogs', 'icon' => 'heroicon-o-briefcase'],
    ],

    // Service dropdown
    'serviceItems' => [
        ['key' => 'crop-planner', 'name' => 'Crop Planner', 'url' => '/crop-planner', 'icon' => 'heroicon-o-sparkles'],
        ['key' => 'pesticide', 'name' => 'Pesticide', 'url' => '/pesticide', 'icon' => 'heroicon-o-beaker'],
        ['key' => 'fertilizer', 'name' => 'Fertilizer', 'url' => '/fertilizer', 'icon' => 'heroicon-o-cube'],
        ['key' => 'disease', 'name' => 'Disease', 'url' => '/disease', 'icon' => 'heroicon-o-exclamation-triangle'],
    ],

    // Languages
    'languages' => [
        ['code' => 'en', 'name' => 'English'],
        ['code' => 'bn', 'name' => 'বাংলা (BN)'],
        ['code' => 'ar', 'name' => 'العربية'],
    ],
];