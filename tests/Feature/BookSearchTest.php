<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_books_page_shows_published_books(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'status' => 'published',
            'title' => 'Test Book',
        ]);

        $response = $this->get(route('books.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Book');
    }

    public function test_books_page_hides_draft_books(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'status' => 'draft',
            'title' => 'Draft Book',
        ]);

        $response = $this->get(route('books.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Draft Book');
    }

    public function test_book_detail_page_shows_book(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'status' => 'published',
            'title' => 'Detail Book',
            'slug' => 'detail-book',
        ]);

        $response = $this->get(route('books.show', $book->slug));

        $response->assertStatus(200);
        $response->assertSee('Detail Book');
    }

    public function test_category_filter_works(): void
    {
        $author = Author::factory()->create();
        $category = Category::factory()->create(['name' => 'Fiction']);
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'category_id' => $category->id,
            'status' => 'published',
            'title' => 'Fiction Book',
        ]);

        $response = $this->get(route('category.books', $category->slug));

        $response->assertStatus(200);
        $response->assertSee('Fiction Book');
    }
}
