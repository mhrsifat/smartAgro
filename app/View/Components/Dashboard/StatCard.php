<?php

namespace App\View\Components\Dashboard;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatCard extends Component
{
    public string $title;
    public $count;
    public ?string $extra;
    public string $icon;
    public string $color;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $title,
        $count,
        string $icon = 'circle',
        string $color = 'blue',
        ?string $extra = null
    ) {
        $this->title = $title;
        $this->count = $count;
        $this->icon = $icon;
        $this->color = $color;
        $this->extra = $extra;
    }

    
    public function render()
    {
        return view('components.dashboard.stat-card');
    }
}
