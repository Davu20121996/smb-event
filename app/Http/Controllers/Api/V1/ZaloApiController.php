<?php

namespace App\Http\Controllers\Api\V1;

use App\Amenity;
use App\Attendee;
use App\Event;
use App\Faq;
use App\Gallery;
use App\Hotel;
use App\Http\Controllers\Controller;
use App\KeyBenefit;
use App\Price;
use App\Schedule;
use App\Setting;
use App\Speaker;
use App\Sponsor;
use App\Venue;
use App\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ZaloApiController extends Controller
{
    /**
     * GET /api/v1/zalo/customer?phone=0901234567
     * Lấy thông tin khách hàng theo số điện thoại Zalo trả về.
     */
    public function customer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $attendee = $this->findByPhone($request->input('phone'));

        if (!$attendee) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'Không có số điện thoại liên kết với khách hàng.',
            ], 404);
        }

        $attendee->load('event', 'voucher');

        return response()->json([
            'status' => 'success',
            'data'   => [
                'attendee_id'    => $attendee->id,
                'name'           => $attendee->name,
                'email'          => $attendee->email,
                'phone'          => $attendee->phone,
                'company'        => $attendee->company,
                'tax_code'       => $attendee->tax_code,
                'company_size'   => $attendee->company_size,
                'company_size_label' => $attendee->company_size_label,
                'interested_products' => $attendee->interested_products,
                'ticket_type'    => $attendee->ticket_type,
                'status'         => $attendee->status,
                'status_label'   => $attendee->status_label,
                'qr'             => $attendee->qr,
                'checked_in'     => $attendee->is_checked_in,
                'checked_in_at'  => $attendee->checked_in_at ? $attendee->checked_in_at->toDateTimeString() : null,
                'registered_at'  => $attendee->created_at ? $attendee->created_at->toDateTimeString() : null,
                'event'          => $attendee->event ? [
                    'id'         => $attendee->event->id,
                    'name'       => $attendee->event->name,
                    'slug'       => $attendee->event->slug,
                    'start_date' => $attendee->event->start_date,
                    'end_date'   => $attendee->event->end_date,
                    'venue'      => $attendee->event->venue,
                ] : null,
                'voucher'        => $attendee->voucher ? [
                    'id'              => $attendee->voucher->id,
                    'code'            => $attendee->voucher->code,
                    'name'            => $attendee->voucher->name,
                    'type'            => $attendee->voucher->type,
                    'value'           => $attendee->voucher->value,
                    'discount_label'  => $attendee->voucher->discount_label,
                    'discount_amount' => $attendee->discount_amount,
                    'description'     => $attendee->voucher->description,
                    'valid_until'     => $attendee->voucher->valid_until ? $attendee->voucher->valid_until->toDateTimeString() : null,
                ] : null,
            ],
        ]);
    }

    /**
     * GET /api/v1/zalo/qr-checkin?phone=0901234567
     * Lấy mã QR check-in của khách theo số điện thoại.
     */
    public function qrCheckin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $attendee = $this->findByPhone($request->input('phone'));

        if (!$attendee) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'Không có số điện thoại liên kết với khách hàng.',
            ], 404);
        }

        if (empty($attendee->qr)) {
            $attendee->regenerateQr();
        }

        $attendee->load('event');

        return response()->json([
            'status' => 'success',
            'data'   => [
                'attendee_id'   => $attendee->id,
                'name'          => $attendee->name,
                'event_name'    => $attendee->event->name ?? null,
                'qr_code'       => $attendee->qr,
                'qr_code_url'   => url('/event/ticket-qr/' . $attendee->id),
                'qr_png_base64' => $this->qrBase64($attendee),
                'checked_in'    => $attendee->is_checked_in,
                'checked_in_at' => $attendee->checked_in_at ? $attendee->checked_in_at->toDateTimeString() : null,
            ],
        ]);
    }

    /**
     * GET /api/v1/zalo/vouchers?phone=0901234567
     * Lấy voucher đang active / voucher của khách theo số điện thoại.
     */
    public function vouchers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $attendee = $this->findByPhone($request->input('phone'));

        if (!$attendee) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'Không có số điện thoại liên kết với khách hàng.',
            ], 404);
        }

        $eventId = $attendee->event_id;

        $activeVouchers = Voucher::where('status', 'active')
            ->where(function ($q) use ($eventId) {
                $q->where('event_id', $eventId)->orWhereNull('event_id');
            })
            ->orderBy('code')
            ->get()
            ->filter(fn ($v) => $v->isAvailable())
            ->values()
            ->map(function ($voucher) {
                return $this->voucherPayload($voucher);
            });

        return response()->json([
            'status' => 'success',
            'data'   => [
                'current_voucher' => $attendee->voucher ? $this->voucherPayload($attendee->voucher, $attendee) : null,
                'active_vouchers' => $activeVouchers,
            ],
        ]);
    }

    /**
     * GET /api/v1/zalo/events?phone=0901234567
     * Lấy danh sách sự kiện đang active. Nếu có phone, trả kèm trạng thái đăng ký.
     */
    public function events(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $attendee = $request->filled('phone') ? $this->findByPhone($request->input('phone')) : null;

        $events = Event::where('is_active', 1)
            ->orderBy('start_date', 'asc')
            ->get()
            ->map(function ($event) use ($attendee) {
                $registered = $attendee && (int) $attendee->event_id === (int) $event->id;

                return [
                    'id'               => $event->id,
                    'name'             => $event->name,
                    'slug'             => $event->slug,
                    'description'      => $event->description,
                    'start_date'       => $event->start_date,
                    'end_date'         => $event->end_date,
                    'registration_deadline' => $event->registration_deadline ? $event->registration_deadline->toDateTimeString() : null,
                    'venue'            => $event->venue,
                    'zalo_url'         => $event->zalo_url,
                    'fanpage_url'      => $event->fanpage_url,
                    'is_registered'    => $registered,
                    'event_url'        => $event->slug ? url('/event/' . $event->slug) : null,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data'   => $events,
        ]);
    }

    /**
     * POST /api/v1/zalo/register
     * Tạo form đăng ký / đăng ký khách mới theo số điện thoại Zalo trả về.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone'               => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'name'                => ['required', 'min:2', 'max:255'],
            'email'               => ['nullable', 'email', 'max:255'],
            'company'             => ['nullable', 'string', 'max:255'],
            'tax_code'            => ['nullable', 'string', 'max:30'],
            'company_size'        => ['nullable', 'string', 'max:50'],
            'interested_products' => ['nullable', 'string', 'max:255'],
            'ticket_type'         => ['nullable', 'string', 'max:255'],
            'event_id'            => ['nullable', 'integer', 'exists:events,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $event = Event::find($request->input('event_id'));

        if (!$event) {
            $event = Event::where('is_active', 1)->first();
        }

        if (!$event) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Chưa có sự kiện nào đang hoạt động.',
            ], 404);
        }

        $phone = trim($request->input('phone'));

        $existing = Attendee::where('phone', $phone)
            ->where('event_id', $event->id)
            ->withTrashed()
            ->first();

        if ($existing) {
            $existing->restore();
            $existing->update([
                'name'                => $request->input('name'),
                'email'               => $request->input('email'),
                'company'             => $request->input('company'),
                'tax_code'            => $request->input('tax_code'),
                'company_size'        => $request->input('company_size'),
                'interested_products' => $request->input('interested_products'),
                'ticket_type'         => $request->input('ticket_type'),
                'status'              => $existing->status ?: 'pending',
            ]);

            $attendee = $existing;
        } else {
            $attendee = Attendee::create([
                'event_id'            => $event->id,
                'name'                => $request->input('name'),
                'email'               => $request->input('email'),
                'phone'               => $phone,
                'company'             => $request->input('company'),
                'tax_code'            => $request->input('tax_code'),
                'company_size'        => $request->input('company_size'),
                'interested_products' => $request->input('interested_products'),
                'ticket_type'         => $request->input('ticket_type'),
                'status'              => 'pending',
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Đăng ký sự kiện thành công.',
            'data'    => [
                'attendee_id' => $attendee->id,
                'name'        => $attendee->name,
                'email'       => $attendee->email,
                'phone'       => $attendee->phone,
                'event'       => [
                    'id'         => $event->id,
                    'name'       => $event->name,
                    'slug'       => $event->slug,
                    'start_date' => $event->start_date,
                    'end_date'   => $event->end_date,
                    'venue'      => $event->venue,
                ],
                'status'      => $attendee->status,
                'qr_code'     => $attendee->qr,
                'qr_code_url' => url('/event/ticket-qr/' . $attendee->id),
            ],
        ], 201);
    }

    /**
     * GET /api/v1/zalo/event/{event}
     * Trả về toàn bộ dữ liệu của một sự kiện (giống trang event trên web)
     * để mini app dựng lại trang chi tiết.
     */
    public function eventDetail($event)
    {
        if (!$event instanceof Event) {
            $event = Event::where('slug', $event)->orWhere('id', $event)->first();
        }

        if (!$event) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'Không tìm thấy sự kiện.',
            ], 404);
        }

        $speakers = Speaker::where('event_id', $event->id)->get();
        $schedules = Schedule::with('speaker')
            ->where('event_id', $event->id)
            ->orderBy('start_time', 'asc')
            ->get();
        $venues = Venue::where('event_id', $event->id)->get();
        $hotels = Hotel::where('event_id', $event->id)->get();
        $galleries = Gallery::where('event_id', $event->id)->get();
        $sponsors = Sponsor::where('event_id', $event->id)->get();
        $faqs = Faq::where('event_id', $event->id)->get();
        $prices = Price::with('amenities')->where('event_id', $event->id)->get();
        $amenities = Amenity::with('prices')->where('event_id', $event->id)->get();
        $keyBenefits = KeyBenefit::where('event_id', $event->id)->orderBy('sort_order')->get();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'id'                    => $event->id,
                'name'                  => $this->text($event->name),
                'slug'                  => $event->slug,
                'description'           => $this->text($event->description),
                'start_date'            => $event->start_date,
                'end_date'              => $event->end_date,
                'registration_deadline' => $event->registration_deadline ? $event->registration_deadline->toIso8601String() : null,
                'is_active'             => (bool) $event->is_active,
                'countdown_enabled'     => (bool) $event->countdown_enabled,
                'calendar_enabled'      => (bool) $event->calendar_enabled,
                'show_gallery'          => (bool) $event->show_gallery,
                'show_sponsors'         => (bool) $event->show_sponsors,
                'show_tickets'          => (bool) $event->show_tickets,
                'meta_title'            => $this->text($event->meta_title),
                'meta_description'      => $this->text($event->meta_description),
                'favicon_url'           => $event->favicon_url,
                'og_image'              => $event->og_image,
                'zalo_url'              => $event->zalo_url,
                'fanpage_url'           => $event->fanpage_url,
                'about_description'     => $this->text($event->about_description),
                'about_where'           => $this->text($event->about_where),
                'about_when'            => $this->text($event->about_when),
                'venue'                 => $event->venue,
                'pc_bg_image_url'       => $event->pc_bg_image_url,
                'mobile_bg_image_url'   => $event->mobile_bg_image_url,
                'hero_image'            => $event->pc_bg_image_url ?: $event->mobile_bg_image_url,
                'mobile_hero_image'     => $event->mobile_bg_image_url,
                'event_url'             => $event->slug ? url('/event/' . $event->slug) : null,

                'settings' => $this->settings($event),

                'key_benefits' => $keyBenefits->map(fn ($kb) => [
                    'icon'        => $kb->icon,
                    'title'       => $this->text($kb->title),
                    'description' => $this->text($kb->description),
                    'sort_order'  => $kb->sort_order,
                ])->values(),

                'speakers' => $speakers->map(fn ($s) => [
                    'id'               => $s->id,
                    'name'             => $this->text($s->name),
                    'role'             => $this->text($s->role),
                    'company'          => $this->text($s->company),
                    'twitter'          => $s->twitter,
                    'facebook'         => $s->facebook,
                    'linkedin'         => $s->linkedin,
                    'description'      => $this->text($s->description),
                    'full_description' => $this->text($s->full_description),
                    'photo_url'        => optional($s->photo)->url,
                    'photo_thumb'      => optional($s->photo)->thumbnail,
                ])->values(),

                'schedules' => $schedules->map(fn ($sc) => [
                    'id'         => $sc->id,
                    'title'      => $this->text($sc->title),
                    'subtitle'   => $this->text($sc->subtitle),
                    'day_number' => $sc->day_number,
                    'start_time' => $sc->start_time,
                    'desc'       => $this->text($sc->desc),
                    'speaker'    => $sc->speaker ? [
                        'id'         => $sc->speaker->id,
                        'name'       => $this->text($sc->speaker->name),
                        'role'       => $this->text($sc->speaker->role),
                        'photo_url'  => optional($sc->speaker->photo)->url,
                        'photo_thumb'=> optional($sc->speaker->photo)->thumbnail,
                    ] : null,
                ])->values(),

                'venues' => $venues->map(fn ($v) => [
                    'id'          => $v->id,
                    'name'        => $this->text($v->name),
                    'address'     => $this->text($v->address),
                    'latitude'    => $v->latitude,
                    'longitude'   => $v->longitude,
                    'description' => $this->text($v->description),
                    'photos'      => collect($v->photos)->map(fn ($p) => [
                        'url'       => $p->url,
                        'thumbnail' => $p->thumbnail,
                    ])->values(),
                ])->values(),

                'hotels' => $hotels->map(fn ($h) => [
                    'id'          => $h->id,
                    'name'        => $this->text($h->name),
                    'rating'      => $h->rating,
                    'address'     => $this->text($h->address),
                    'description' => $this->text($h->description),
                    'photo_url'   => optional($h->photo)->url,
                    'photo_thumb' => optional($h->photo)->thumbnail,
                ])->values(),

                'galleries' => $galleries->map(fn ($g) => [
                    'id'     => $g->id,
                    'name'   => $this->text($g->name),
                    'photos' => collect($g->photos)->map(fn ($p) => [
                        'url'       => $p->url,
                        'thumbnail' => $p->thumbnail,
                    ])->values(),
                ])->values(),

                'sponsors' => $sponsors->map(fn ($sp) => [
                    'id'        => $sp->id,
                    'name'      => $this->text($sp->name),
                    'link'      => safe_href($sp->link),
                    'logo_url'  => optional($sp->logo)->url,
                    'logo_thumb'=> optional($sp->logo)->thumbnail,
                ])->values(),

                'faqs' => $faqs->map(fn ($f) => [
                    'id'       => $f->id,
                    'question' => $this->text($f->question),
                    'answer'   => $this->text($f->answer),
                ])->values(),

                'amenities' => $amenities->map(fn ($a) => [
                    'id'   => $a->id,
                    'name' => $this->text($a->name),
                ])->values(),

                'prices' => $prices->map(fn ($p) => [
                    'id'        => $p->id,
                    'name'      => $this->text($p->name),
                    'price'     => $p->price,
                    'amenity_ids' => $p->amenities->pluck('id')->values(),
                ])->values(),
            ],
        ]);
    }

    protected function settings(Event $event): array
    {
        $global = Setting::whereNull('event_id')->pluck('value', 'key')->toArray();
        $eventSettings = Setting::where('event_id', $event->id)->pluck('value', 'key')->toArray();
        $merged = array_merge($global, $eventSettings);

        foreach ($merged as $key => $value) {
            $merged[$key] = $this->text($value);
        }

        return $merged;
    }

    protected function text($value)
    {
        if ($value === null || $value === '') {
            return $value;
        }

        $parsed = parse_locale_json($value);

        if (is_array($parsed)) {
            return $parsed[app()->getLocale()] ?? $parsed['vi'] ?? $parsed['en'] ?? null;
        }

        return $value;
    }

    protected function findByPhone(?string $phone): ?Attendee
    {
        $phone = trim((string) $phone);

        if ($phone === '') {
            return null;
        }

        $digits = preg_replace('/\D/', '', $phone);

        $candidates = [$phone, $digits];

        if (strlen($digits) >= 11 && str_starts_with($digits, '84')) {
            $candidates[] = '0' . substr($digits, 2);
        }

        if (strlen($digits) === 10 && str_starts_with($digits, '0')) {
            $candidates[] = '84' . substr($digits, 1);
        }

        $candidates = array_values(array_unique(array_filter($candidates)));

        return Attendee::withTrashed()
            ->with('voucher')
            ->whereIn('phone', $candidates)
            ->orderByDesc('created_at')
            ->first();
    }

    protected function voucherPayload(Voucher $voucher, ?Attendee $attendee = null): array
    {
        return [
            'id'              => $voucher->id,
            'code'            => $voucher->code,
            'name'            => $voucher->name,
            'type'            => $voucher->type,
            'type_label'      => $voucher->type_label,
            'value'           => $voucher->value,
            'discount_label'  => $voucher->discount_label,
            'description'     => $voucher->description,
            'valid_from'      => $voucher->valid_from ? $voucher->valid_from->toDateTimeString() : null,
            'valid_until'     => $voucher->valid_until ? $voucher->valid_until->toDateTimeString() : null,
            'remaining_uses'  => $voucher->remaining_uses,
            'is_available'    => $voucher->isAvailable(),
        ];
    }

    protected function qrBase64(Attendee $attendee): ?string
    {
        $png = Attendee::qrPng($attendee->qr, 240);

        return $png ? 'data:image/png;base64,' . base64_encode($png) : null;
    }
}
