<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
//            "id" => rand(1, 100),
            "name" => $this->faker->sentence(4, true),
            "isbn" => $this->faker->uuid,
            "authors" => [$this->faker->name],
            "number_of_pages" => rand(1, 1000),
            "publisher" => $this->faker->name,
            "country" => $this->faker->country,
            "release_date" => $this->faker->date("Y-m-d"),
        ];
    }
}
