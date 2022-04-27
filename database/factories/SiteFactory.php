<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Site>
 */
class SiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'url' => $this->faker->domainName,
            'name' => $this->faker->name,
            'description' => $this->faker->paragraph(1),
            'obs' => $this->faker->paragraph(3),
            'da' => $this->faker->randomNumber,
            'dr' => $this->faker->randomNumber,
            'traffic' => $this->faker->randomNumber,
            'tf' => $this->faker->randomNumber,
            'country' => $this->faker->country,
            'language' => $this->faker->languageCode,
            'category_id' => Category::factory(),
            'link_type' => $this->faker->randomElement(['NOFOLLOW', 'DOFOLLOW']),
            'gambling' => $this->faker->boolean,
            'cdb' => $this->faker->boolean,
            'cripto' => $this->faker->boolean,
            'sponsor' => $this->faker->boolean,
            'ssl' => $this->faker->boolean,
            'broken' => $this->faker->boolean,
            'cost' => $this->faker->randomNumber,
            'sale' => $this->faker->randomNumber,
            'last_posted' => $this->faker->date('Y-m-d'),
        ];
    }
}
