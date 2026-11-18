<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Stationery;
use App\Support\Sorting;
use Illuminate\View\View;

class StationeryController extends Controller
{
    public function index(): View
    {
        $sort = request('sort', 'newest');

        $items = Stationery::with('brand')
            ->where('status', 'published');

        Sorting::apply($items, $sort);

        $brands = Brand::where('status', 'active')->withCount('stationeries')->get();

        $items = $items->paginate(12);

        return view('frontend.stationeries.index', compact('items', 'brands', 'sort'));
    }

    public function show($slug): View
    {
        $item = Stationery::with('brand')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $related = Stationery::with('brand')
            ->where('brand_id', $item->brand_id)
            ->where('id', '!=', $item->id)
            ->where('status', 'published')
            ->latest()
            ->take(4)
            ->get();

        return view('frontend.stationeries.show', compact('item', 'related'));
    }

    public function brand($slug): View
    {
        $brand = Brand::where('slug', $slug)->where('status', 'active')->firstOrFail();
        $sort = request('sort', 'newest');

        $items = Stationery::with('brand')
            ->where('brand_id', $brand->id)
            ->where('status', 'published');

        Sorting::apply($items, $sort);

        $brands = Brand::where('status', 'active')->withCount('stationeries')->get();
        $items = $items->paginate(12);

        return view('frontend.stationeries.index', compact('items', 'brands', 'sort', 'brand'));
    }
}
