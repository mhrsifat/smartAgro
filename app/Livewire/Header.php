<?php

namespace App\Livewire;

use Livewire\Component;

class Header extends Component
{
    // Panels / dropdown states
    public $mobileMenuOpen = false;
    public $mobileSearchOpen = false;
    public $mobileServiceOpen = false;
    public $desktopLangOpen = false;
    public $mobileLangOpen = false;

    // Site/logo info
    public $siteName = 'SmartAgro';
    public $logoUrl = null; // '/storage/logo.png' if you have one

    // MAIN NAV - keep the icon as the blade component name (easy to manage)
    public $nav = [
        ['key' => 'home',     'name' => 'Home',            'url' => '/',                'icon' => 'heroicon-o-home'],
        ['key' => 'research', 'name' => 'Research',        'url' => '/research',       'icon' => 'heroicon-o-beaker'],
        ['key' => 'service',  'name' => 'Service',         'url' => '#',               'hasChildren' => true, 'icon' => 'heroicon-o-wrench'],
        ['key' => 'impact',   'name' => 'Impact',          'url' => '/impact',         'icon' => 'heroicon-o-chart-bar'],
        ['key' => 'where',    'name' => 'Where We Work',   'url' => '/where-we-work',  'icon' => 'heroicon-o-globe-alt'],
        ['key' => 'team',     'name' => 'Our Team',        'url' => '/our-team',       'icon' => 'heroicon-o-users'],
        ['key' => 'career',   'name' => 'Career',          'url' => '/career',         'icon' => 'heroicon-o-briefcase'],
    ];

    // SERVICE children (icons as component names)
    public $serviceItems = [
        ['key' => 'crop-planner', 'name' => 'Crop Planner', 'url' => '/crop-planner', 'icon' => 'heroicon-o-sparkles'],
        ['key' => 'pesticide',    'name' => 'Pesticide',    'url' => '/pesticide',     'icon' => 'heroicon-o-beaker'],
        ['key' => 'fertilizer',   'name' => 'Fertilizer',   'url' => '/fertilizer',    'icon' => 'heroicon-o-cube'],
        ['key' => 'disease',      'name' => 'Disease',      'url' => '/disease',       'icon' => 'heroicon-o-exclamation-triangle'],
    ];

    protected $listeners = ['closeAll' => 'closeAll'];

    private function resetDropdowns()
    {
        $this->mobileMenuOpen = false;
        $this->mobileSearchOpen = false;
        $this->mobileServiceOpen = false;
        $this->desktopLangOpen = false;
        $this->mobileLangOpen = false;
    }

    public function toggleMobileMenu() { $this->resetDropdowns(); $this->mobileMenuOpen = true; }
    public function toggleMobileSearch() { $this->resetDropdowns(); $this->mobileSearchOpen = true; }
    public function toggleMobileService() { $this->mobileServiceOpen = ! $this->mobileServiceOpen; }
    public function toggleDesktopLang() { $this->desktopLangOpen = ! $this->desktopLangOpen; }
    public function toggleMobileLang() { $this->mobileLangOpen = ! $this->mobileLangOpen; }
    public function closeAll() { $this->resetDropdowns(); }

    public function render()
    {
        return view('livewire.header', [
            'nav'               => $this->nav,
            'serviceItems'      => $this->serviceItems,
            'siteName'          => $this->siteName,
            'logoUrl'           => $this->logoUrl,
            'desktopLangOpen'   => $this->desktopLangOpen,
            'mobileLangOpen'    => $this->mobileLangOpen,
            'mobileServiceOpen' => $this->mobileServiceOpen,
            'mobileMenuOpen'    => $this->mobileMenuOpen,
            'mobileSearchOpen'  => $this->mobileSearchOpen,
        ]);
    }
}