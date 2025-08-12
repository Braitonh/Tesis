<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class DashboardMain extends Component
{
    public $activeSection = 'dashboard';
    public $activeSubsection = null;

    protected $listeners = [
        'navigateTo' => 'handleNavigation'
    ];

    public function handleNavigation($section, $subsection = null)
    {
        $this->activeSection = $section;
        $this->activeSubsection = $subsection;
        
        // Emitir evento para que el componente de navegación actualice el estado activo
        $this->dispatch('sectionChanged', $section, $subsection);
    }

    public function mount()
    {
        // Sección por defecto
        $this->activeSection = 'dashboard';
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-main');
    }
}