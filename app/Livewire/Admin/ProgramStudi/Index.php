<?php

namespace App\Livewire\Admin\ProgramStudi;

use Livewire\Component;
use App\Models\ProgramStudi;

class Index extends Component
{




    public function render()
    {
        $programStudi = ProgramStudi::all();
        // dd($programStudi);
        return view('livewire.admin.program-studi.index', [
            'programStudi' => $programStudi
        ]);
    }
}
