<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class AdminUsers extends Component
{
    use WithPagination;

    public $user_id;

    public $name;

    public $email;

    public $phone;

    public $password;

    public $password_confirmation;

    public $status = 'active';

    public $search = '';

    public $isOpen = false;

    public $deleteId = null;

    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$this->user_id,
            'phone' => 'nullable|string|max:20',
            'password' => '',
            'password_confirmation' => 'required_with:password|same:password',
            'status' => 'required|in:active,inactive',
        ];

        if ($this->user_id) {
            $rules['password'] = 'nullable|string|min:8';
        } else {
            $rules['password'] = 'required|string|min:8';
        }

        return $rules;
    }

    public function render()
    {
        $users = User::role(['Super Admin', 'Admin'])
            ->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.admin-users', compact('users'))
            ->layout('layouts.admin');
    }

    public function create(): void
    {
        $this->resetInput();
        $this->isOpen = true;
    }

    public function edit($id): void
    {
        $user = User::findOrFail($id);
        if ($user->hasRole('Super Admin') && ! auth()->user()->hasRole('Super Admin')) {
            session()->flash('message', 'Only Super Admins can edit other Super Admins.');

            return;
        }
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->status = $user->status ?? 'active';
        $this->password = '';
        $this->password_confirmation = '';
        $this->isOpen = true;
    }

    public function store(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->user_id) {
            $user = User::findOrFail($this->user_id);
            if ($user->hasRole('Super Admin') && ! auth()->user()->hasRole('Super Admin')) {
                session()->flash('message', 'Only Super Admins can update other Super Admins.');

                return;
            }
            $user->update($data);
            session()->flash('message', 'Admin updated successfully.');
        } else {
            $user = User::create($data);
            $user->assignRole('Admin');
            session()->flash('message', 'Admin created successfully.');
        }

        $this->resetInput();
        $this->isOpen = false;
    }

    public function confirmDelete($id): void
    {
        $this->deleteId = $id;
    }

    public function delete(): void
    {
        $user = User::find($this->deleteId);
        if (! $user || $user->id === auth()->id()) {
            $this->deleteId = null;

            return;
        }
        if ($user->hasRole('Super Admin') && ! auth()->user()->hasRole('Super Admin')) {
            session()->flash('message', 'Only Super Admins can delete other Super Admins.');
            $this->deleteId = null;

            return;
        }
        $user->removeRole('Admin');
        $user->delete();
        session()->flash('message', 'Admin deleted successfully.');
        $this->deleteId = null;
    }

    public function cancelDelete(): void
    {
        $this->deleteId = null;
    }

    private function resetInput(): void
    {
        $this->user_id = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->status = 'active';
    }
}
