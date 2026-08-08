<?php

namespace Database\Seeders;


use App\Amenity;
use Illuminate\Database\Seeder;

class AmenitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $amenities = [
            [
                'name' => 'General Admission'
            ],
            [
                'name' => 'Conference Bag'
            ],
            [
                'name' => 'Expo Hall Access'
            ],
            [
                'name' => 'Networking Lunch'
            ],
            [
                'name' => 'VIP Lounge Access'
            ],
            [
                'name' => 'Workshop Pass'
            ],
        ];

        foreach($amenities as $amenity)
        {
            $amenity['event_id'] = 1;
            Amenity::create($amenity);
        }
    }
}
