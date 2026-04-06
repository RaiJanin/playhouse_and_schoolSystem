<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ReportsLinkBtn extends Component
{
    public string $title;
    public string $link;
    public string $width;
    /**
     * Create a new component instance.
     */
    public function __construct($title, $link, $width = '')
    {
        $this->title = $title;
        $this->link = $link;
        $this->width = $width;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.reports-link-btn');
    }
}
