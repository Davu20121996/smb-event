<?php

namespace Database\Seeders;


use App\Schedule;
use Illuminate\Database\Seeder;

class SchedulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schedules = [
            [
                'day_number' => 1,
                'start_time' => '09:00:00',
                'title' => 'Registration & Welcome Coffee',
                'subtitle' => 'Pick up your badge and network with fellow attendees before the conference begins.',
                'speaker_id' => null,
            ],
            [
                'day_number' => 1,
                'start_time' => '10:00:00',
                'title' => 'Opening Keynote: The State of Modern Marketing',
                'subtitle' => 'Where marketing is heading over the next decade, from data-driven strategy to creative excellence.',
                'speaker_id' => 1,
            ],
            [
                'day_number' => 1,
                'start_time' => '11:30:00',
                'title' => 'Building Brand Stories That Resonate',
                'subtitle' => 'How to craft narratives that cut through the noise and connect with real customers.',
                'speaker_id' => 3,
            ],
            [
                'day_number' => 1,
                'start_time' => '14:00:00',
                'title' => 'Digital Advertising Trends 2026',
                'subtitle' => 'A practical look at the channels, formats and measurement methods shaping paid media this year.',
                'speaker_id' => 5,
            ],
            [
                'day_number' => 1,
                'start_time' => '16:00:00',
                'title' => 'Social Media Playbook for Business',
                'subtitle' => 'Building engaged communities and turning social presence into pipeline.',
                'speaker_id' => 6,
            ],
            [
                'day_number' => 2,
                'start_time' => '09:30:00',
                'title' => 'Content Marketing That Converts',
                'subtitle' => 'Designing editorial strategies that attract, educate and convert at every stage of the funnel.',
                'speaker_id' => 5,
            ],
            [
                'day_number' => 2,
                'start_time' => '11:00:00',
                'title' => 'Email & Lifecycle Marketing Masterclass',
                'subtitle' => 'Automation, segmentation and retention tactics that compound over time.',
                'speaker_id' => 2,
            ],
            [
                'day_number' => 2,
                'start_time' => '14:00:00',
                'title' => 'Measuring Marketing ROI',
                'subtitle' => 'Frameworks for connecting campaigns to revenue and reporting with confidence.',
                'speaker_id' => 4,
            ],
            [
                'day_number' => 2,
                'start_time' => '16:00:00',
                'title' => 'Panel: The Future of Customer Experience',
                'subtitle' => 'Leaders discuss personalisation, privacy and the customer journey of tomorrow.',
                'speaker_id' => null,
            ],
            [
                'day_number' => 3,
                'start_time' => '09:30:00',
                'title' => 'SEO & Growth Hacking Workshop',
                'subtitle' => 'Hands-on tactics to improve search visibility and experiment your way to growth.',
                'speaker_id' => 4,
            ],
            [
                'day_number' => 3,
                'start_time' => '11:00:00',
                'title' => 'Fireside Chat: Lessons from a CMO',
                'subtitle' => 'Honest stories of wins, failures and leadership lessons from the top of the marketing org.',
                'speaker_id' => 1,
            ],
            [
                'day_number' => 3,
                'start_time' => '14:00:00',
                'title' => 'Closing Keynote: Marketing in the Age of AI',
                'subtitle' => 'A vision for how generative AI will reshape strategy, creativity and the marketing team.',
                'speaker_id' => 2,
            ],
        ];

        foreach($schedules as $schedule)
        {
            $schedule['event_id'] = 1;
            Schedule::create($schedule);
        }
    }
}
