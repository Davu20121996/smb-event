<?php

namespace Database\Seeders;


use App\Hotel;
use Illuminate\Database\Seeder;

class HotelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hotels = [
            [
                'name'          => 'The Manhattan Grand',
                'description'   => '0.3 Mile from the Venue',
                'rating'        =>  5
            ],
            [
                'name'          => 'Broadway Boutique Hotel',
                'description'   => '0.5 Mile from the Venue',
                'rating'        =>  4
            ],
            [
                'name'          => 'Financial District Suites',
                'description'   => '0.7 Miles from the Venue',
                'rating'        =>  3
            ],
        ];

        foreach($hotels as $key => $hotel)
        {
            $photo_id = $key+1;
            $hotel['event_id'] = 1;
            $hotel = Hotel::create($hotel);
            $hotel->addMedia(storage_path()."/seeders/hotels/$photo_id.jpg")->preservingOriginal()->toMediaCollection('photo');
        }
    }
}
