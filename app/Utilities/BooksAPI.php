<?php


namespace App\Utilities;


use Carbon\Carbon;

class BooksAPI
{
    private $bookEndpoint = "https://www.anapioficeandfire.com/api/books?";

    public function bookSearch($bookName)
    {
        $queryParams = http_build_query([
            "name" => $bookName
        ]);
        $ch = curl_init($this->bookEndpoint . $queryParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch);
        $responseStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($responseStatus !== 200) {
            $response = [
                "status_code" => $responseStatus,
                "status" => "error",
                "data" => []
            ];
        } else {
            $response = [
                "status_code" => $responseStatus,
                "status" => "success",
                "data" => $this->prepareData(json_decode($output))
            ];
        }
        curl_close($ch);

        return $response;
    }

    private function prepareData($data)
    {
        if (is_null($data))
            return [];

        $booksData = [];
        foreach ($data as $book) {
            $booksData[] = [
                "name" => $book->name,
                "isbn" => $book->isbn,
                "authors" => $book->authors,
                "number_of_pages" => $book->numberOfPages,
                "publisher" => $book->publisher,
                "country" => $book->country,
                "release_date" => Carbon::parse($book->released)->format("Y-m-d"),
            ];
        }
        return $booksData;
    }
}
