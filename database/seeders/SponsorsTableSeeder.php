<?php

namespace Database\Seeders;


use App\Sponsor;
use Illuminate\Database\Seeder;

class SponsorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sponsors = [
            [
                'name' => 'HubSpot',
                'link' => '#'
            ],
            [
                'name' => 'Mailchimp',
                'link' => '#'
            ],
            [
                'name' => 'SEMrush',
                'link' => '#'
            ],
            [
                'name' => 'Canva',
                'link' => '#'
            ],
            [
                'name' => 'Sprout Social',
                'link' => '#'
            ],
            [
                'name' => 'Hootsuite',
                'link' => '#'
            ],
            [
                'name' => 'Salesforce',
                'link' => '#'
            ],
            [
                'name' => 'AdRoll',
                'link' => '#'
            ],
        ];

        foreach($sponsors as $key => $sponsor)
        {
            $photo_id = $key + 1;
            $sponsor['event_id'] = 1;
            $sponsor = Sponsor::create($sponsor);
            $sponsor->addMedia(storage_path()."/seeders/supporters/$photo_id.png")->preservingOriginal()->toMediaCollection('logo');
        }
    }
}
