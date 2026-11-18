<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\UploadsMedia;
use App\Models\Brand;
use App\Models\Stationery;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Stationeries extends Component
{
    use UploadsMedia, WithFileUploads, WithPagination;

    public $stationery_id;

    public $title;

    public $brand_id;

    public $description;

    public $price;

    public $sale_price;

    public $stock;

    public $image;

    public $new_image;

    public $featured = false;

    public $status = 'draft';

    public $search = '';

    public $isOpen = false;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'new_image' => 'nullable|image|max:2048',
            'featured' => 'boolean',
            'status' => 'required|in:draft,published,archived',
        ];
    }

    public function render()
    {
        $items = Stationery::with('brand')
            ->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(10);

        $brands = Brand::where('status', 'active')->get();

        return view('livewire.admin.stationeries', compact('items', 'brands'))
            ->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $item = Stationery::findOrFail($id);
        $this->stationery_id = $item->id;
        $this->title = $item->title;
        $this->brand_id = $item->brand_id;
        $this->description = $item->description;
        $this->price = $item->price;
        $this->sale_price = $item->sale_price;
        $this->stock = $item->stock;
        $this->image = $item->image;
        $this->featured = $item->featured;
        $this->status = $item->status;
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate();

        $data = $this->except([
            'stationery_id', 'image', 'new_image', 'isOpen', 'search',
        ]);

        if ($this->new_image) {
            $data['image'] = $this->storeMedia($this->new_image, 'stationeries');
        }

        $slug = Str::slug($this->title);
        $existing = Stationery::where('slug', $slug)->where('id', '!=', $this->stationery_id)->exists();
        if ($existing) {
            $slug .= '-'.Str::random(4);
        }
        $data['slug'] = $slug;

        Stationery::updateOrCreate(['id' => $this->stationery_id], $data);

        session()->flash('message', $this->stationery_id ? 'Stationery updated.' : 'Stationery created.');
        $this->resetInputFields();
        $this->isOpen = false;
    }

    public function delete($id)
    {
        Stationery::find($id)?->delete();
        session()->flash('message', 'Stationery deleted.');
    }

    private function resetInputFields()
    {
        $this->stationery_id = null;
        $this->title = '';
        $this->brand_id = null;
        $this->description = '';
        $this->price = 0;
        $this->sale_price = null;
        $this->stock = 0;
        $this->image = null;
        $this->new_image = null;
        $this->featured = false;
        $this->status = 'draft';
    }
}
