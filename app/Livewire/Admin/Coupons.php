<?php

namespace App\Livewire\Admin;

use App\Models\Coupon;
use Livewire\Component;
use Livewire\WithPagination;

class Coupons extends Component
{
    use WithPagination;

    public $coupon_id;

    public $code;

    public $type = 'fixed';

    public $value;

    public $min_amount;

    public $start_date;

    public $end_date;

    public $usage_limit;

    public $status = 'active';

    public $search = '';

    public $isOpen = false;

    protected function rules(): array
    {
        return [
            'code' => 'required|string|max:50|unique:coupons,code,'.$this->coupon_id,
            'type' => 'required|in:fixed,percent',
            'value' => [
                'required', 'numeric', 'min:0',
                function ($attribute, $value, $fail) {
                    if ($this->type === 'percent' && $value > 100) {
                        $fail('Percent value cannot exceed 100.');
                    }
                },
            ],
            'min_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function render()
    {
        $coupons = Coupon::where('code', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.coupons', compact('coupons'))
            ->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetInput();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        $this->coupon_id = $coupon->id;
        $this->code = $coupon->code;
        $this->type = $coupon->type;
        $this->value = $coupon->value;
        $this->min_amount = $coupon->min_amount;
        $this->start_date = $coupon->start_date?->format('Y-m-d');
        $this->end_date = $coupon->end_date?->format('Y-m-d');
        $this->usage_limit = $coupon->usage_limit;
        $this->status = $coupon->status;
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate();

        Coupon::updateOrCreate(['id' => $this->coupon_id], [
            'code' => strtoupper($this->code),
            'type' => $this->type,
            'value' => $this->value,
            'min_amount' => $this->min_amount,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'usage_limit' => $this->usage_limit,
            'status' => $this->status,
        ]);

        session()->flash('message', $this->coupon_id ? 'Coupon updated.' : 'Coupon created.');
        $this->resetInput();
        $this->isOpen = false;
    }

    public function delete($id)
    {
        Coupon::find($id)?->delete();
        session()->flash('message', 'Coupon deleted.');
    }

    private function resetInput()
    {
        $this->coupon_id = null;
        $this->code = '';
        $this->type = 'fixed';
        $this->value = '';
        $this->min_amount = null;
        $this->start_date = '';
        $this->end_date = '';
        $this->usage_limit = null;
        $this->status = 'active';
    }
}
