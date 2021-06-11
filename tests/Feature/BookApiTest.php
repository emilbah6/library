<?php

namespace Tests\Feature;

use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_books()
    {
        $randomBook = Book::factory()->create();
        $response = $this->getJson('/api/v1/books');
        $response
            ->assertStatus(200)
            ->assertJson([
                "status_code" => 200,
                "status" => "success",
                "data" => [
                    [
                        "id" => $randomBook->id,
                        "name" => $randomBook->name,
                        "isbn" => $randomBook->isbn,
                        "authors" => $randomBook->authors,
                        "number_of_pages" => $randomBook->number_of_pages,
                        "publisher" => $randomBook->publisher,
                        "country" => $randomBook->country,
                        "release_date" => $randomBook->release_date,
                    ],
                ]
            ]);
    }

    public function test_search_book_from_db()
    {
        $randomBook = Book::factory()->create();
        $searchParams = [$randomBook->name, $randomBook->country, $randomBook->publisher, Carbon::parse($randomBook->release_date)->format("Y")];
        $searchParamIndex = array_rand($searchParams);
        $response = $this->getJson('/api/v1/books?search_value=' . $searchParams[$searchParamIndex]);
        $response
            ->assertStatus(200)
            ->assertJson([
                "status_code" => 200,
                "status" => "success",
                "data" => [
                    [
                        "id" => $randomBook->id,
                        "name" => $randomBook->name,
                        "isbn" => $randomBook->isbn,
                        "authors" => $randomBook->authors,
                        "number_of_pages" => $randomBook->number_of_pages,
                        "publisher" => $randomBook->publisher,
                        "country" => $randomBook->country,
                        "release_date" => $randomBook->release_date,
                    ],
                ]
            ]);
    }

    public function test_save_book_to_db()
    {
        $randomBook = Book::factory()->make();
        $response = $this->postJson('/api/v1/books', $randomBook->toArray());
        $response
            ->assertStatus(201)
            ->assertJson([
                "status_code" => 201,
                "status" => "success",
                "data" => [[
                    "book" => [
                        "name" => $randomBook->name,
                        "isbn" => $randomBook->isbn,
                        "authors" => $randomBook->authors,
                        "number_of_pages" => $randomBook->number_of_pages,
                        "publisher" => $randomBook->publisher,
                        "country" => $randomBook->country,
                        "release_date" => $randomBook->release_date,
                    ],
                ]]
            ]);
    }

    public function test_edit_book_from_db()
    {
        $existingBook = Book::factory()->create();
        $updatedBook = Book::factory()->make();
        $updatedBook->authors = implode(",", $updatedBook->authors);
        $updatedBook->id = $existingBook->id;
        unset($updatedBook->created_at, $updatedBook->updated_at);
        $response = $this->patchJson('/api/v1/books/' . $existingBook->id, $updatedBook->toArray());
        $updatedBook->authors = explode(",", $updatedBook->authors);
        $response
            ->assertStatus(200)
            ->assertJson([
                "status_code" => 200,
                "status" => "success",
                "message" => "The book $existingBook->name was updated successfully",
                "data" => $updatedBook->toArray()
            ]);
    }

    public function test_delete_book_from_db()
    {
        $existingBook = Book::factory()->create();
        $book_name = $existingBook->name;
        $response = $this->deleteJson('/api/v1/books/' . $existingBook->id, ["name" => $book_name]);
        $response
            ->assertJson([
                "status_code" => 204,
                "status" => "success",
                "message" => "The book $book_name was deleted successfully",
                "data" => []
            ]);
    }
}
