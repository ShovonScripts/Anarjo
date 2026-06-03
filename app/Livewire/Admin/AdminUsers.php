<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class AdminUsers extends Component
{
    use WithPagination;

    public $user_id;
    public $name;
    public $email;
    public $phone;
    public $status = 'active';
    public $search = '';
    public $isOpen = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user_id,
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function render()
    {
        $users = User::where(function ($q) {
            $q->where('name', 'like', '%' . $this->search . '%')
              ->orWhere('email', 'like', '%' . $this->search . '%');
        })->latest()->paginate(15);

        return view('livewire.admin.admin-users', compact('users'))
            ->layout('layouts.admin');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->status = $user->status ?? 'active';
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate();

        User::where('id', $this->user_id)->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
        ]);

        session()->flash('message', 'User updated.');
        $this->resetInput();
        $this->isOpen = false;
    }

    private function resetInput()
    {
        $this->user_id = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->status = 'active';
    }
}
