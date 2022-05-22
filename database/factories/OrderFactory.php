<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Seller;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'site_id' => Site::factory(),
            'client_id' => Client::factory(),
            'seller_id' => Seller::factory(),
            'url' => $this->faker->url,
            'ssl' => $this->faker->boolean,
            'broken' => $this->faker->boolean,
            'receipt_date' => $this->faker->date('Y-m-d'),
            'delivery_date' => $this->faker->date('Y-m-d'),
            'payment_date' => $this->faker->date('Y-m-d'),
            'charged' => $this->faker->randomNumber,
            'paid' => $this->faker->randomNumber,
            'markup' => $this->faker->randomNumber,
            'comission' => $this->faker->randomNumber,
            'status' => $this->faker->randomElement(['WAITING', 'PRODUCTION', 'SUBMITTED', 'PROCESSING', 'PUBLISHED', 'INVOICE', 'COMPLETED']),
        ];
    }
}
