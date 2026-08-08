<?php

namespace Database\Seeders;


use App\Faq;
use Illuminate\Database\Seeder;

class FaqsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faqs = [
            [
                'question' => 'What topics will be covered at the conference?',
                'answer' => 'The agenda spans brand strategy, digital advertising, content marketing, social media and marketing analytics across three days of keynotes and hands-on workshops.'
            ],
            [
                'question' => 'Is this event suitable for small business owners?',
                'answer' => 'Yes. Sessions are designed for marketing professionals at every company size, with practical takeaways you can apply immediately after the conference.'
            ],
            [
                'question' => 'Will there be networking opportunities?',
                'answer' => 'Yes. We host a welcome reception, dedicated networking breaks and an expo hall where you can meet speakers, sponsors and fellow marketers.'
            ],
            [
                'question' => 'Are meals included in the ticket price?',
                'answer' => 'Coffee breaks are included for all tickets. Lunch is included with VIP and Executive passes.'
            ],
            [
                'question' => 'Can I get a refund if I can no longer attend?',
                'answer' => 'Full refunds are available up to 30 days before the event. Within 30 days, tickets can be transferred to another attendee at no cost.'
            ],
            [
                'question' => 'Is there a discount for group bookings?',
                'answer' => 'Groups of five or more receive 15% off General and VIP passes. Contact our team for a custom quote.'
            ],
        ];

        foreach ($faqs as $faq) {
            $faq['event_id'] = 1;
            Faq::create($faq);
        }
    }
}
