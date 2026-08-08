<?php

namespace App\Http\Controllers;

use App\CompanyProfileItem;
use App\LandingLead;
use App\LandingPage;
use App\Post;
use App\Setting;
use App\Speaker;
use App\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        $settings = Setting::whereNull('event_id')->pluck('value', 'key');
        $posts = Post::where('is_published', 1)->orderBy('created_at', 'desc')->get();
        $profileItems = CompanyProfileItem::orderBy('sort_order')->get()->groupBy('section');

        return view('home', compact('settings', 'posts', 'profileItems'));
    }

    public function posts()
    {
        $settings = Setting::whereNull('event_id')->pluck('value', 'key');
        $posts = Post::where('is_published', 1)->orderBy('created_at', 'desc')->get();

        return view('posts', compact('settings', 'posts'));
    }

    public function projects()
    {
        $settings = Setting::whereNull('event_id')->pluck('value', 'key');
        $logos = collect(\File::files(public_path('img/smbplus/case-studies')))
            ->filter(fn ($file) => in_array(strtolower($file->getExtension()), ['png', 'jpg', 'jpeg', 'svg', 'webp', 'gif']))
            ->map(fn ($file) => asset('img/smbplus/case-studies/' . $file->getFilename()))
            ->values();

        return view('projects', compact('settings', 'logos'));
    }

    public function postsByTag($tag)
    {
        $settings = Setting::whereNull('event_id')->pluck('value', 'key');
        $posts = Post::where('is_published', 1)
            ->where('tag', $tag)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('posts', compact('settings', 'posts', 'tag'));
    }

    public function post(Post $post)
    {
        abort_if(!$post->is_published, 404);

        $settings = Setting::whereNull('event_id')->pluck('value', 'key');
        $related = Post::where('is_published', 1)
            ->where('id', '!=', $post->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('post', compact('settings', 'post', 'related'));
    }

    public function view(Speaker $speaker)
    {
        $global = Setting::whereNull('event_id')->pluck('value', 'key');
        $event = Setting::where('event_id', $speaker->event_id)->pluck('value', 'key');
        $settings = $global->merge($event);

        return view('speaker', compact('settings', 'speaker'));
    }

    public function freshwork()
    {
        $settings = Setting::whereNull('event_id')->pluck('value', 'key');

        return view('freshwork', compact('settings'));
    }

    public function shareIndex()
    {
        $settings = Setting::whereNull('event_id')->pluck('value', 'key');
        $landingPages = LandingPage::where('is_published', 1)->orderBy('created_at', 'desc')->get();

        return view('share', compact('settings', 'landingPages'));
    }

    public function shareShow(LandingPage $landingPage)
    {
        abort_if(!$landingPage->is_published, 404);

        $settings = Setting::whereNull('event_id')->pluck('value', 'key');

        return view('share-detail', compact('settings', 'landingPage'));
    }

    public function shareRegister(Request $request, LandingPage $landingPage)
    {
        abort_if(!$landingPage->is_published, 404);

        $validator = Validator::make($request->all(), [
            'name'  => ['required', 'min:2', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $crmTag = trim((string) ($landingPage->crm_tag ?: $landingPage->slug));
        $crmTag = (string) preg_replace('/[\x00-\x1F\x7F]/u', '', $crmTag);
        $crmTag = mb_substr($crmTag, 0, 100);

        $documentUrl = null;
        if ($landingPage->pdf_enabled) {
            if ($landingPage->pdf_source == 'url' && $landingPage->pdf_url) {
                $candidate = (string) $landingPage->pdf_url;
                if (
                    filter_var($candidate, FILTER_VALIDATE_URL)
                    && in_array(strtolower((string) parse_url($candidate, PHP_URL_SCHEME)), ['http', 'https'], true)
                ) {
                    $documentUrl = $candidate;
                }
            } elseif ($landingPage->pdf_file) {
                $documentUrl = $landingPage->pdf_file->getUrl();
            }
        }

        LandingLead::create([
            'landing_page_id' => $landingPage->id,
            'name'            => $request->input('name'),
            'email'           => $request->input('email'),
            'phone'           => $request->input('phone'),
            'crm_tag'         => $crmTag,
            'is_synced'       => false,
            'document_url'    => $documentUrl,
            'source_url'      => route('share.show', $landingPage->slug),
        ]);

        return redirect()->route('share.thank-you', $landingPage->slug);
    }

    public function shareThankYou(LandingPage $landingPage)
    {
        abort_if(!$landingPage->is_published, 404);

        $settings = Setting::whereNull('event_id')->pluck('value', 'key');

        return view('share-thank-you', compact('settings', 'landingPage'));
    }

    public function storeContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'min:2', 'max:255'],
            'email'    => ['required', 'email', 'max:255'],
            'company'  => ['nullable', 'string', 'max:255'],
            'phone'    => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'event_id' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->first(), 422);
        }

        ContactMessage::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'subject'  => 'Đăng ký tham gia sự kiện',
            'message'  => 'Company: ' . ($request->input('company') ?: '-') . "\n" . 'Phone: ' . ($request->input('phone') ?: '-'),
            'event_id' => (int) $request->input('event_id', 0),
        ]);

        return response('OK', 200);
    }
}
