<?php

namespace Database\Factories\Cuisine;

use App\Models\Cuisine\Cuisine;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CuisineFactory extends Factory
{
    protected $model = Cuisine::class;

    public function definition(): array
    {
        $name = $this->faker->randomElement([
            'Georgian Cuisine',
            'Italian Cuisine', 
            'Asian Cuisine',
            'Mediterranean Cuisine',
            'European Cuisine',
            'Fast Food',
            'Vegetarian',
            'Seafood',
            'Mexican Cuisine',
            'Indian Cuisine'
        ]);

        return [
            'slug' => Str::slug($name),
            'rank' => $this->faker->numberBetween(1, 100),
            'image' => null,
            'image_link' => $this->faker->optional()->imageUrl(400, 300, 'food'),
            'status' => $this->faker->randomElement([
                Cuisine::STATUS_ACTIVE,
                Cuisine::STATUS_INACTIVE,
                Cuisine::STATUS_MAINTENANCE
            ]),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Cuisine::STATUS_ACTIVE,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Cuisine::STATUS_INACTIVE,
        ]);
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Cuisine $cuisine) {
            // Create Georgian translation
            $cuisine->translateOrNew('ka')->name = match($cuisine->slug) {
                'georgian-cuisine' => 'ქართული სამზარეულო',
                'italian-cuisine' => 'იტალიური სამზარეულო',
                'asian-cuisine' => 'აზიური სამზარეულო',
                'mediterranean-cuisine' => 'ხმელთაშუაზღვისპირული სამზარეულო',
                'european-cuisine' => 'ევროპული სამზარეულო',
                'fast-food' => 'ფასტ ფუდი',
                'vegetarian' => 'ვეგეტარიანული',
                'seafood' => 'ზღვის პროდუქტები',
                'mexican-cuisine' => 'მექსიკური სამზარეულო',
                'indian-cuisine' => 'ინდური სამზარეულო',
                default => $this->faker->words(2, true),
            };

            // Create English translation
            $cuisine->translateOrNew('en')->name = match($cuisine->slug) {
                'georgian-cuisine' => 'Georgian Cuisine',
                'italian-cuisine' => 'Italian Cuisine',
                'asian-cuisine' => 'Asian Cuisine',
                'mediterranean-cuisine' => 'Mediterranean Cuisine',
                'european-cuisine' => 'European Cuisine',
                'fast-food' => 'Fast Food',
                'vegetarian' => 'Vegetarian',
                'seafood' => 'Seafood',
                'mexican-cuisine' => 'Mexican Cuisine',
                'indian-cuisine' => 'Indian Cuisine',
                default => $this->faker->words(2, true),
            };

            // Create Russian translation
            $cuisine->translateOrNew('ru')->name = match($cuisine->slug) {
                'georgian-cuisine' => 'Грузинская кухня',
                'italian-cuisine' => 'Итальянская кухня',
                'asian-cuisine' => 'Азиатская кухня',
                'mediterranean-cuisine' => 'Средиземноморская кухня',
                'european-cuisine' => 'Европейская кухня',
                'fast-food' => 'Фаст-фуд',
                'vegetarian' => 'Вегетарианская',
                'seafood' => 'Морепродукты',
                'mexican-cuisine' => 'Мексиканская кухня',
                'indian-cuisine' => 'Индийская кухня',
                default => $this->faker->words(2, true),
            };

            $cuisine->save();
        });
    }
}