<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatusCard extends Component
{
    public string $title;
    public string|int $value;
    public string $icon;
    public string  $bg;
    public string $width;

    /**
     * Create a new component instance.
     */
    public function __construct($title, $value, $icon, $bg = 'bg-gray-700', $width = '')
    {
        $this->title = $title;
        $this->value = $value;
        $this->icon = $icon;
        $this->bg = $bg;
        $this->width = $width;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.status-card');
    }
}
