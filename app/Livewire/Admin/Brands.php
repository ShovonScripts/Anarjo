<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\UploadsMedia;
use App\Models\Brand;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Brands extends Component
{
    use UploadsMedia, WithFileUploads, WithPagination;

    public $brand_id;

    public $name;

    public $description;

    public $status = 'active';

    public $logo;

    public $existing_logo;

    public $search = '';

    public $isOpen = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    public function render()
    {
        $brands = Brand::where('name', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.brands', compact('brands'))
            ->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetInput();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        $this->brand_id = $brand->id;
        $this->name = $brand->name;
        $this->description = $brand->description;
        $this->status = $brand->status;
        $this->existing_logo = $brand->logo;
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
            'status' => $this->status,
        ];

        if ($this->logo) {
            $data['logo'] = $this->storeMedia($this->logo, 'brands');
        } elseif ($this->brand_id && ! $this->existing_logo) {
            $data['logo'] = null;
        }

        Brand::updateOrCreate(['id' => $this->brand_id], $data);

        session()->flash('message', $this->brand_id ? 'Brand updated.' : 'Brand created.');
        $this->resetInput();
        $this->isOpen = false;
    }

    public function delete($id)
    {
        Brand::find($id)?->delete();
        session()->flash('message', 'Brand deleted.');
    }

    private function resetInput()
    {
        $this->brand_id = null;
        $this->name = '';
        $this->description = '';
        $this->status = 'active';
        $this->logo = null;
        $this->existing_logo = null;
    }
}
