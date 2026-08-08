<?php

namespace Database\Seeders;

use App\Attendee;
use App\ContactMessage;
use Illuminate\Database\Seeder;

class MigrateContactMessagesToAttendeesSeeder extends Seeder
{
    public function run()
    {
        $messages = ContactMessage::where(function ($q) {
            $q->whereNotNull('event_id')
                ->where('event_id', '>', 0)
                ->orWhere('subject', 'like', 'Event Registration%');
        })->orderBy('id')->get();

        $count = 0;

        foreach ($messages as $message) {
            $eventId = ((int) $message->event_id) > 0 ? (int) $message->event_id : null;

            $exists = Attendee::where('event_id', $eventId)
                ->where('email', $message->email)
                ->where('name', $message->name)
                ->exists();

            if ($exists) {
                continue;
            }

            $parsed = $this->parseMessage($message->message);

            Attendee::create([
                'event_id'            => $eventId,
                'name'                => $message->name,
                'email'               => $message->email,
                'company'             => $parsed['company'],
                'tax_code'            => null,
                'phone'               => $parsed['phone'],
                'company_size'        => null,
                'interested_products' => null,
                'ticket_type'         => $parsed['ticket_type'],
                'status'              => 'pending',
                'notes'               => 'Migrated from contact_messages #' . $message->id,
                'created_at'          => $message->created_at ?: now(),
                'updated_at'          => now(),
            ]);

            $count++;
        }

        $this->command?->info("Đã chuyển {$count} đăng ký cũ sang bảng attendees.");
    }

    private function parseMessage(string $message): array
    {
        $result = [
            'company'     => null,
            'phone'       => null,
            'ticket_type' => null,
        ];

        $lines = preg_split('/\r\n|\r|\n/', trim((string) $message));

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            if (stripos($line, 'Company:') === 0) {
                $result['company'] = trim(substr($line, 8)) ?: null;
            } elseif (stripos($line, 'Phone:') === 0) {
                $result['phone'] = trim(substr($line, 6)) ?: null;
            } elseif (stripos($line, 'Ticket') !== false) {
                $value = trim((string) preg_replace('/^Ticket type\s*:\s*/i', '', $line), " \"'");
                $result['ticket_type'] = $value && $value !== 'Not selected' ? $value : null;
            } elseif (!$result['company'] && $result['phone']) {
                $result['company'] = $line ?: null;
            }
        }

        return $result;
    }
}