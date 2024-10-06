<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::query()
            ->withTrashed()
            ->chunk(100, function ($orders) {
                $orders->each(function ($order) {
                    $order->update([
                        'number' => Str::upper(Str::random(6)),
                    ]);
                });
            });
    }
}
