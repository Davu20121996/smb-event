<?php

namespace Database\Seeders;

use App\Amenity;
use App\ContactMessage;
use App\Faq;
use App\Gallery;
use App\Hotel;
use App\Price;
use App\Schedule;
use App\Setting;
use App\Speaker;
use App\Sponsor;
use App\Venue;
use Illuminate\Database\Seeder;

class SampleEventTwoAndContactsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding Tech Summit 2026 (event_id=2) + sample contact messages...');

        $eventSettings = [
            ['key' => 'title', 'value' => 'Tech<br><span>Summit</span> 2026'],
            ['key' => 'subtitle', 'value' => '15-17 March, Silicon Valley Convention Hall, San Francisco'],
            ['key' => 'youtube_link', 'value' => 'https://www.youtube.com/watch?v=jDDaplaOz7Q'],
            ['key' => 'about_description', 'value' => 'Nơi hội tụ những bộ óc xuất sắc nhất trong lĩnh vực công nghệ để chia sẻ, học hỏi và truyền cảm hứng. Ba ngày với các bài phát biểu chính, hội thảo chuyên sâu và kết nối cùng các chuyên gia đầu ngành về AI, điện toán đám mây, an ninh mạng và kỹ thuật sản phẩm.'],
            ['key' => 'about_where', 'value' => 'Silicon Valley Convention Hall, San Francisco'],
            ['key' => 'about_when', 'value' => 'Thứ Hai đến Thứ Tư<br>15-17 tháng 3'],
        ];
        foreach ($eventSettings as $setting) {
            $setting['event_id'] = 2;
            Setting::create($setting);
        }

        $speakers = [
            ['name' => 'Alice Nguyen', 'description' => 'AI Research Lead', 'full_description' => 'Alice leads applied AI research with a focus on large language models and responsible deployment of generative systems.'],
            ['name' => 'David Patel', 'description' => 'CTO, CloudScale', 'full_description' => 'David has spent 15 years building distributed systems and now heads platform engineering at CloudScale.'],
            ['name' => 'Maria Lopez', 'description' => 'Head of Product, InnoWorks', 'full_description' => 'Maria is a product leader who has shipped developer tools used by millions of engineers worldwide.'],
            ['name' => 'Tom Becker', 'description' => 'Security Architect, Guardline', 'full_description' => 'Tom specializes in zero-trust architectures and has led security programs at several Fortune 500 companies.'],
            ['name' => 'Sara Kim', 'description' => 'Data Engineering Director, Quantix', 'full_description' => 'Sara builds real-time data platforms and mentors the next generation of data engineers.'],
            ['name' => 'James Okafor', 'description' => 'VP Engineering, FinTech Labs', 'full_description' => 'James oversees engineering at a fast-growing fintech, balancing velocity with reliability at scale.'],
        ];
        $speakerIds = [];
        foreach ($speakers as $key => $speaker) {
            $speaker['twitter'] = '#';
            $speaker['facebook'] = '#';
            $speaker['linkedin'] = '#';
            $speaker['event_id'] = 2;
            $created = Speaker::create($speaker);
            $created->addMedia(storage_path() . "/seeders/speakers/" . ($key + 1) . ".jpg")->preservingOriginal()->toMediaCollection('photo');
            $speakerIds[] = $created->id;
        }

        $schedules = [
            ['day_number' => 1, 'start_time' => '09:00:00', 'title' => 'Registration & Coffee', 'subtitle' => 'Pick up your badge and network with fellow attendees.', 'speaker_id' => null],
            ['day_number' => 1, 'start_time' => '10:00:00', 'title' => 'Opening Keynote: The Future of AI', 'subtitle' => 'Where artificial intelligence is heading over the next decade.', 'speaker_id' => $speakerIds[0]],
            ['day_number' => 1, 'start_time' => '11:30:00', 'title' => 'Building Resilient Cloud Platforms', 'subtitle' => 'Lessons from operating infrastructure at global scale.', 'speaker_id' => $speakerIds[1]],
            ['day_number' => 1, 'start_time' => '14:00:00', 'title' => 'Product-Led Growth for Developers', 'subtitle' => 'How to design developer tools people love.', 'speaker_id' => $speakerIds[2]],
            ['day_number' => 1, 'start_time' => '16:00:00', 'title' => 'Security in the Zero-Trust Era', 'subtitle' => 'Protecting modern applications in a perimeter-less world.', 'speaker_id' => $speakerIds[3]],
            ['day_number' => 2, 'start_time' => '09:30:00', 'title' => 'Real-Time Data at Scale', 'subtitle' => 'Streaming architectures that power millions of events per second.', 'speaker_id' => $speakerIds[4]],
            ['day_number' => 2, 'start_time' => '11:00:00', 'title' => 'Scaling Engineering Teams', 'subtitle' => 'Growing teams while keeping quality and velocity high.', 'speaker_id' => $speakerIds[5]],
            ['day_number' => 2, 'start_time' => '14:00:00', 'title' => 'Hands-on AI Workshop', 'subtitle' => 'Build and ship your first LLM-powered application.', 'speaker_id' => $speakerIds[0]],
            ['day_number' => 2, 'start_time' => '16:00:00', 'title' => 'Panel: The Human Side of Tech', 'subtitle' => 'Diversity, ethics and wellbeing in the industry.', 'speaker_id' => null],
            ['day_number' => 3, 'start_time' => '09:30:00', 'title' => 'Designing Great Developer Experiences', 'subtitle' => 'APIs, docs and tools that developers actually enjoy using.', 'speaker_id' => $speakerIds[2]],
            ['day_number' => 3, 'start_time' => '11:00:00', 'title' => 'Fireside Chat: Lessons from the Trenches', 'subtitle' => 'Honest stories of failures and recoveries.', 'speaker_id' => $speakerIds[1]],
            ['day_number' => 3, 'start_time' => '14:00:00', 'title' => 'Closing Keynote: What Comes Next', 'subtitle' => 'A vision for the next wave of technology innovation.', 'speaker_id' => $speakerIds[5]],
        ];
        foreach ($schedules as $schedule) {
            $schedule['event_id'] = 2;
            Schedule::create($schedule);
        }

        $venue = Venue::create([
            'name'        => 'Silicon Valley Convention Hall, San Francisco',
            'address'     => '747 Howard St, San Francisco, CA 94103',
            'latitude'    => '37.78497',
            'longitude'   => '-122.40105',
            'description' => 'A modern convention center in the heart of San Francisco, minutes from tech hubs and public transit.',
            'event_id'    => 2,
        ]);
        foreach (range(1, 8) as $id) {
            $venue->addMedia(storage_path() . "/seeders/venue-gallery/$id.jpg")->preservingOriginal()->toMediaCollection('photos');
        }

        $hotels = [
            ['name' => 'Grand Bay Hotel', 'description' => '0.3 Mile from the Venue', 'rating' => 5],
            ['name' => 'City Lights Inn', 'description' => '0.6 Mile from the Venue', 'rating' => 4],
            ['name' => 'Union Square Suites', 'description' => '1.1 Miles from the Venue', 'rating' => 3],
        ];
        foreach ($hotels as $key => $hotel) {
            $hotel['event_id'] = 2;
            $created = Hotel::create($hotel);
            $created->addMedia(storage_path() . "/seeders/hotels/" . ($key + 1) . ".jpg")->preservingOriginal()->toMediaCollection('photo');
        }

        $gallery = Gallery::create(['name' => 'Tech Summit 2026', 'event_id' => 2]);
        foreach (range(1, 8) as $id) {
            $gallery->addMedia(storage_path() . "/seeders/gallery/$id.jpg")->preservingOriginal()->toMediaCollection('photos');
        }

        $sponsors = ['Google Cloud', 'AWS', 'Microsoft', 'NVIDIA', 'Datadog', 'Stripe', 'Cloudflare', 'GitHub'];
        foreach ($sponsors as $key => $name) {
            $created = Sponsor::create(['name' => $name, 'link' => '#', 'event_id' => 2]);
            $created->addMedia(storage_path() . "/seeders/supporters/" . ($key + 1) . ".png")->preservingOriginal()->toMediaCollection('logo');
        }

        $faqs = [
            ['question' => 'Where can I find the full agenda?', 'answer' => 'The agenda is published on this page. Registered attendees receive a detailed schedule with session rooms by email one week before the event.'],
            ['question' => 'Is there parking available near the venue?', 'answer' => 'Yes, the convention hall offers paid on-site parking. We recommend public transit or rideshare as parking fills up quickly on event days.'],
            ['question' => 'Are meals included in the ticket price?', 'answer' => 'Coffee breaks are included for all tickets. Lunch is included with Pro and Premium passes.'],
            ['question' => 'Can I get a refund if I can no longer attend?', 'answer' => 'Full refunds are available up to 30 days before the event. Within 30 days, tickets can be transferred to another attendee at no cost.'],
            ['question' => 'Will sessions be recorded?', 'answer' => 'All keynote and panel sessions are recorded and made available to ticket holders for 90 days after the event.'],
            ['question' => 'Is there a dress code?', 'answer' => 'No formal dress code. We recommend comfortable business casual attire.'],
        ];
        foreach ($faqs as $faq) {
            $faq['event_id'] = 2;
            Faq::create($faq);
        }

        $amenityNames = ['Regular Seating', 'Coffee Break', 'Custom Badge', 'Community Access', 'Workshop Access', 'After Party'];
        $amenityIds = [];
        foreach ($amenityNames as $name) {
            $amenity = Amenity::create(['name' => $name, 'event_id' => 2]);
            $amenityIds[] = $amenity->id;
        }

        $prices = [
            ['name' => 'Standard Access', 'price' => 120, 'amenities' => [$amenityIds[0], $amenityIds[1], $amenityIds[2]]],
            ['name' => 'Pro Access', 'price' => 220, 'amenities' => [$amenityIds[0], $amenityIds[1], $amenityIds[2], $amenityIds[3]]],
            ['name' => 'Premium Access', 'price' => 320, 'amenities' => $amenityIds],
        ];
        foreach ($prices as $price) {
            $amenities = $price['amenities'];
            unset($price['amenities']);
            $price['event_id'] = 2;
            $created = Price::create($price);
            $created->amenities()->sync($amenities);
        }

        $contactMessages = [
            ['name' => 'John Smith', 'email' => 'john@example.com', 'subject' => 'Question about ticket upgrade', 'message' => 'Hi, I currently have a Standard ticket for the marketing conference. Can I upgrade to Pro on-site?', 'event_id' => 1],
            ['name' => 'Emily Davis', 'email' => 'emily@example.com', 'subject' => 'Group discount', 'message' => 'We would like to register a team of 10 people. Do you offer group discounts?', 'event_id' => 1],
            ['name' => 'Michael Brown', 'email' => 'michael@example.com', 'subject' => 'Sponsorship inquiry', 'message' => 'Our company is interested in sponsoring the Tech Summit. Please share the sponsorship deck.', 'event_id' => 2],
            ['name' => 'Sarah Wilson', 'email' => 'sarah@example.com', 'subject' => 'Accommodation help', 'message' => 'Could you recommend hotels near the venue with shuttle service for the summit days?', 'event_id' => 2],
            ['name' => 'Robert Garcia', 'email' => 'robert@example.com', 'subject' => 'Partnership opportunity', 'message' => 'We would love to partner with your company on our next marketing campaign. Let us discuss details.', 'event_id' => null],
        ];
        foreach ($contactMessages as $message) {
            ContactMessage::create($message);
        }

        $this->command->info('Done. Event 2 now has settings, 6 speakers, 12 schedules, venue, hotels, gallery, sponsors, faqs, prices & amenities. Contact messages: 2 (event 1), 2 (event 2), 1 (home).');
    }
}
