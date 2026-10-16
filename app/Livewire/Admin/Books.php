<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\UploadsMedia;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookFairEdition;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Books extends Component
{
    use UploadsMedia, WithFileUploads, WithPagination;

    public $book_id;

    public $title;

    public $isbn;

    public $author_id;

    public $selectedCategories = [];

    public $selectedFairEditions = [];

    public $description;

    public $excerpt;

    public $price;

    public $sale_price;

    public $stock;

    public $cover_image;

    public $new_cover_image;

    public $language;

    public $pages;

    public $edition;

    public $publish_date;

    public $weight;

    public $featured = false;

    public $bestseller = false;

    public $new_arrival = false;

    public $pre_order = false;

    public $publisher_id;

    public $preview_pdf;

    public $new_preview_pdf;

    public $seo_title;

    public $seo_description;

    public $status = 'draft';

    public $search = '';

    public $isOpen = false;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:books,isbn,'.$this->book_id,
            'author_id' => 'nullable|exists:authors,id',
            'selectedCategories' => 'nullable|array',
            'selectedCategories.*' => 'exists:categories,id',
            'selectedFairEditions' => 'nullable|array',
            'selectedFairEditions.*' => 'exists:book_fair_editions,id',
            'description' => 'nullable|string',
            'excerpt' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'new_cover_image' => 'nullable|image|max:2048',
            'language' => 'nullable|string|max:50',
            'pages' => 'nullable|integer|min:1',
            'edition' => 'nullable|string|max:50',
            'publish_date' => 'nullable|date',
            'weight' => 'nullable|numeric|min:0',
            'featured' => 'boolean',
            'bestseller' => 'boolean',
            'new_arrival' => 'boolean',
            'pre_order' => 'boolean',
            'publisher_id' => 'nullable|exists:users,id',
            'new_preview_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published,archived',
        ];
    }

    public function render()
    {
        $books = Book::with('author', 'categories')
            ->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('isbn', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(10);

        $authors = Author::where('status', 'active')->get();
        $categories = Category::all();
        $publishers = User::all();
        $fairEditions = BookFairEdition::all();

        return view('livewire.admin.books', compact('books', 'authors', 'categories', 'publishers', 'fairEditions'))
            ->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $book = Book::with('categories', 'bookFairEditions')->findOrFail($id);
        $this->book_id = $book->id;
        $this->title = $book->title;
        $this->isbn = $book->isbn;
        $this->author_id = $book->author_id;
        $this->selectedCategories = $book->categories->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        $this->selectedFairEditions = $book->bookFairEditions->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        $this->description = $book->description;
        $this->excerpt = $book->excerpt;
        $this->price = $book->price;
        $this->sale_price = $book->sale_price;
        $this->stock = $book->stock;
        $this->cover_image = $book->cover_image;
        $this->language = $book->language;
        $this->pages = $book->pages;
        $this->edition = $book->edition;
        $this->publish_date = $book->publish_date?->format('Y-m-d');
        $this->weight = $book->weight;
        $this->featured = $book->featured;
        $this->bestseller = $book->bestseller;
        $this->new_arrival = $book->new_arrival;
        $this->pre_order = $book->pre_order;
        $this->publisher_id = $book->publisher_id;
        $this->preview_pdf = $book->preview_pdf;
        $this->seo_title = $book->seo_title;
        $this->seo_description = $book->seo_description;
        $this->status = $book->status;
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate();

        $data = $this->except([
            'book_id', 'cover_image', 'new_cover_image', 'preview_pdf', 'new_preview_pdf',
            'isOpen', 'search', 'selectedCategories', 'selectedFairEditions',
        ]);

        $current = $this->book_id ? Book::find($this->book_id) : null;

        if ($this->new_cover_image) {
            $data['cover_image'] = $this->replaceMedia($this->new_cover_image, 'books', $current?->cover_image);
        }

        if ($this->new_preview_pdf) {
            $data['preview_pdf'] = $this->replaceMedia($this->new_preview_pdf, 'books/pdfs', $current?->preview_pdf);
        }

        $slug = Str::slug($this->title);
        $existing = Book::where('slug', $slug)->where('id', '!=', $this->book_id)->exists();
        if ($existing) {
            $slug .= '-'.Str::random(4);
        }
        $data['slug'] = $slug;

        $book = Book::updateOrCreate(['id' => $this->book_id], $data);

        if ($this->selectedCategories) {
            $book->categories()->sync($this->selectedCategories);
        } else {
            $book->categories()->sync([]);
        }

        if ($this->selectedFairEditions) {
            $book->bookFairEditions()->sync($this->selectedFairEditions);
        } else {
            $book->bookFairEditions()->sync([]);
        }

        session()->flash('message', $this->book_id ? 'Book updated.' : 'Book created.');
        $this->resetInputFields();
        $this->isOpen = false;
    }

    public function delete($id)
    {
        Book::find($id)?->delete();
        session()->flash('message', 'Book deleted.');
    }

    private function resetInputFields()
    {
        $this->book_id = null;
        $this->title = '';
        $this->isbn = '';
        $this->author_id = null;
        $this->selectedCategories = [];
        $this->selectedFairEditions = [];
        $this->description = '';
        $this->excerpt = '';
        $this->price = 0;
        $this->sale_price = null;
        $this->stock = 0;
        $this->cover_image = null;
        $this->new_cover_image = null;
        $this->language = 'Bengali';
        $this->pages = null;
        $this->edition = '';
        $this->publish_date = null;
        $this->weight = null;
        $this->featured = false;
        $this->bestseller = false;
        $this->new_arrival = false;
        $this->pre_order = false;
        $this->publisher_id = null;
        $this->preview_pdf = null;
        $this->new_preview_pdf = null;
        $this->seo_title = '';
        $this->seo_description = '';
        $this->status = 'draft';
    }
}
