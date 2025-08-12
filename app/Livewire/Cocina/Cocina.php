<?php

namespace App\Livewire\Cocina;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.dashboard-layout')]
class Cocina extends Component
{
    public function render()
    {
        return view('livewire.cocina.cocina');
    }
}
