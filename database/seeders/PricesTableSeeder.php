<?php

namespace Database\Seeders;


use App\Price;
use Illuminate\Database\Seeder;

class PricesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prices = [
            [
                'name'  => 'General Pass',
                'price' => 149
            ],
            [
                'name'  => 'VIP Pass',
                'price' => 249
            ],
            [
                'name'  => 'Executive Pass',
                'price' => 349
            ],
        ];

        foreach($prices as $price)
        {
            $price['event_id'] = 1;
            Price::create($price);
        }
    }
}
