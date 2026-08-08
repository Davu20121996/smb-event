<?php

namespace Database\Seeders;

use App\Event;
use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    public function run()
    {
        $events = [
            [
                'id'          => 1,
                'name'        => 'The Annual Marketing Conference',
                'slug'        => 'annual-marketing-conference',
                'description' => 'Sed nam ut dolor qui repellendus iusto odit. Possimus inventore eveniet accusamus error amet eius aut accusantium et.',
                'start_date'  => '2026-12-10',
                'end_date'    => '2026-12-12',
                'is_active'   => 1,
            ],
            [
                'id'          => 2,
                'name'        => 'Tech Summit 2026',
                'slug'        => 'tech-summit-2026',
                'description' => 'A gathering of the brightest minds in technology to share, learn and inspire.',
                'start_date'  => '2027-03-15',
                'end_date'    => '2027-03-17',
                'is_active'   => 1,
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
