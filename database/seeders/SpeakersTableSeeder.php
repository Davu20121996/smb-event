<?php

namespace Database\Seeders;


use App\Speaker;
use Illuminate\Database\Seeder;

class SpeakersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $speakers = [
            [
                'name'              => 'Sarah Mitchell',
                'description'       => 'CMO, BrightLane',
                'twitter'           => '#',
                'facebook'          => '#',
                'linkedin'          => '#',
                'full_description'  => 'Sarah has led global brand and growth teams for over 15 years and now drives marketing strategy at BrightLane, a fast-growing marketing automation platform.'
            ],
            [
                'name'              => 'James Carter',
                'description'       => 'Growth Lead, NovaPay',
                'twitter'           => '#',
                'facebook'          => '#',
                'linkedin'          => '#',
                'full_description'  => 'James specialises in acquisition funnels and lifecycle marketing for fast-scaling fintech products, with a track record of doubling activation rates.'
            ],
            [
                'name'              => 'Elena Rossi',
                'description'       => 'Brand Director, Vantage',
                'twitter'           => '#',
                'facebook'          => '#',
                'linkedin'          => '#',
                'full_description'  => 'Elena has shaped award-winning brand campaigns across Europe and the US, with a focus on storytelling and creative strategy.'
            ],
            [
                'name'              => 'Michael Chen',
                'description'       => 'Digital Strategy Consultant',
                'twitter'           => '#',
                'facebook'          => '#',
                'linkedin'          => '#',
                'full_description'  => 'Michael advises Fortune 500 companies on SEO, paid media and marketing measurement, turning complex data into simple growth plans.'
            ],
            [
                'name'              => 'Amanda Foster',
                'description'       => 'Head of Content, Pulse Media',
                'twitter'           => '#',
                'facebook'          => '#',
                'linkedin'          => '#',
                'full_description'  => 'Amanda builds content engines that turn ideas into measurable pipeline, leading editorial teams at one of the industry\'s top media houses.'
            ],
            [
                'name'              => 'Daniel Brooks',
                'description'       => 'Social Media Lead, Orbit Labs',
                'twitter'           => '#',
                'facebook'          => '#',
                'linkedin'          => '#',
                'full_description'  => 'Daniel runs social strategy and community programs for consumer tech brands, growing engaged audiences of millions across platforms.'
            ],
        ];
        foreach($speakers as $key => $speaker)
        {
            $photo_id = $key+1;
            $speaker['event_id'] = 1;
            $speaker = Speaker::create($speaker);
            $speaker->addMedia(storage_path()."/seeders/speakers/$photo_id.jpg")->preservingOriginal()->toMediaCollection('photo');
        }
    }
}
