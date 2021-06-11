<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Utilities\BooksAPI;

class BookController extends Controller
{
    private $booksAPI;

    public function __construct()
    {
        $this->booksAPI = new BooksAPI();
    }

    public function index()
    {
        $search_value = \request()->query("search_value");
        $books = Book::select('id', 'name', 'isbn', 'authors', 'country', 'number_of_pages', 'publisher', 'release_date');
        if (isset($search_value))
            $books = $books->where("name", "LIKE", "%$search_value%")
                ->orWhere("country", "LIKE", "%$search_value%")
                ->orWhere("publisher", "LIKE", "%$search_value%")
                ->orWhere("release_date", "LIKE", "%$search_value%")
                ->get();
        else
            $books = $books->get();

        return response()->json([
            "status_code" => 200,
            "status" => "success",
            "data" => $books
        ], 200);
    }

    public function createBook()
    {
        \request()->validate([
            'name' => "required|unique:books,name",
            'isbn' => "required|unique:books,isbn",
            'authors' => "required",
            'country' => "required",
            'number_of_pages' => "required|integer",
            'publisher' => "required",
            'release_date' => "required|date"
        ]);

        try {
            $newBook = Book::create([
                'name' => \request()->name,
                'isbn' => \request()->isbn,
                'authors' => \request()->authors,
                'country' => \request()->country,
                'number_of_pages' => \request()->number_of_pages,
                'publisher' => \request()->publisher,
                'release_date' => \request()->release_date
            ]);

            return response()->json([
                "status_code" => 201,
                "status" => "success",
                "data" => [[
                    "book" => [
                        "name" => $newBook->name,
                        "isbn" => $newBook->isbn,
                        "authors" => $newBook->authors,
                        "number_of_pages" => $newBook->number_of_pages,
                        "publisher" => $newBook->publisher,
                        "country" => $newBook->country,
                        "release_date" => $newBook->release_date,
                    ],
                ]]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "status_code" => $e->getCode(),
                "status" => "error",
                "data" => []
            ], 500);
        }
    }

    public function editBook()
    {
        \request()->validate([
            'name' => "required",
            'isbn' => "required",
            'authors' => "required",
            'country' => "required",
            'number_of_pages' => "required|integer",
            'publisher' => "required",
            'release_date' => "required|date"
        ]);

        $book_id = \request()->route("id");
        $book_id = (int)$book_id;

        // if the book_id is not integer from the parsing then the default value will be 0
        if (!$book_id)
            $book = null;
        else
            $book = Book::where("id", $book_id)->first();

        if (!is_null($book)) {
            $oldBookName = $book->name;
            $book->name = \request()->name;
            $book->isbn = \request()->isbn;
            $book->authors = explode(",", \request()->authors);
            $book->country = \request()->country;
            $book->number_of_pages = \request()->number_of_pages;
            $book->publisher = \request()->publisher;
            $book->release_date = \request()->release_date;
            $book->save();

            return response()->json([
                "status_code" => 200,
                "status" => "success",
                "message" => "The book $oldBookName was updated successfully",
                "data" => $book
            ], 200);
        } else {
            return response()->json([
                "status_code" => 404,
                "status" => "error",
                "message" => "The book is not found",
                "data" => []
            ], 404);
        }
    }

    public function deleteBook()
    {
        $book_id = \request()->route("id");
        $book_id = (int)$book_id;
        $book_name = \request()->name;

        // if the book_id is not integer from the parsing then the default value will be 0
        if ($book_id)
            $book = Book::where("id", $book_id)->delete();


        return response()->json([
            "status_code" => !$book ? 404 : 204,
            "status" => !$book ? "error" : "success",
            "message" => "The book $book_name was deleted successfully",
            "data" => []
        ]);
    }

    public function viewSpecificBook()
    {
        $book_id = \request()->route("id");
        $book_id = (int)$book_id;

        // if the book_id is not integer from the parsing then the default value will be 0
        if (!$book_id)
            $book = null;
        else
            $book = Book::select('id', 'name', 'isbn', 'authors', 'country', 'number_of_pages', 'publisher', 'release_date')
                ->where("id", $book_id)
                ->first();

        return response()->json([
            "status_code" => is_null($book) ? 404 : 200,
            "status" => is_null($book) ? "error" : "success",
            "data" => is_null($book) ? [] : $book
        ], is_null($book) ? 404 : 200);
    }

    public function externalBookSearch()
    {
        \request()->validate([
            "name_of_book" => "required"
        ]);
        $bookName = \request()->query("name_of_book");
        $result = $this->booksAPI->bookSearch($bookName);

        return response()->json($result);
    }
}
