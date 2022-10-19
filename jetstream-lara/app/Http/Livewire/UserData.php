<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;


class UserData extends Component
{
    public $users;

    public function render()
    {
        $this->users = User::all();
        return view('livewire.user-data');
    }
}
