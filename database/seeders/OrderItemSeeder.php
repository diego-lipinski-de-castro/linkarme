<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::query()
            ->withTrashed()
            ->get();

        foreach ($orders as $order) {

            if($order->site_id) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'site_id' => $order->site_id,
                    
                    'seller_id' => $order->seller_id,
    
                    'link' => $order->url,
    
                    'cost' => $order->paid,
                    'sale' => $order->charged,
                    'comission' => $order->comission,
                ]);
            }
        }
    }
}
