<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ProyectoInvestigadoresList extends Component
{
    public $proyecto;

    public function mount($proyecto)
    {
        $this->proyecto = $proyecto;
    }

    public function render()
    {
        return view('livewire.proyecto-investigadores-list');
    }
}
