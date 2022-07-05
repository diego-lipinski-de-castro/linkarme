<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Country;
use App\Models\Language;
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
            'category_id' => Category::factory(),
            'language_id' => Language::factory(),
            'country_id' => Country::factory(),
            'link_type' => $this->faker->randomElement(['NOFOLLOW', 'DOFOLLOW']),
            'gambling' => $this->faker->boolean,
            'cdb' => $this->faker->boolean,
            'cripto' => $this->faker->boolean,
            'sponsor' => $this->faker->boolean,
            'ssl' => $this->faker->boolean,
            'broken' => $this->faker->boolean,
            'cost' => $this->faker->randomNumber,
            'sale' => $this->faker->randomNumber,
            'cost_coin' => $this->faker->randomElement(['BRL', 'EUR', 'USD']),
            'sale_coin' => $this->faker->randomElement(['BRL', 'EUR', 'USD']),
            'last_posted' => $this->faker->date('Y-m-d'),
        ];
    }
}
