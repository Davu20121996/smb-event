<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Post;
use App\Setting;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyProfileController extends Controller
{
    protected $editableKeys = [
        'company_title',
        'company_subtitle',
        'company_slogan',
        'company_about',
        'company_letter',
        'company_vision',
        'company_mission',
        'company_thanks',
        'company_youtube_link',
        'contact_address',
        'contact_phone',
        'contact_email',
        'contact_website',
        'footer_description',
        'footer_address',
        'footer_twitter',
        'footer_facebook',
        'footer_instagram',
        'footer_googleplus',
        'footer_linkedin',
    ];

    protected $sectionKeys = [
        'sec_letter',
        'sec_about',
        'sec_values',
        'sec_why_us',
        'sec_services',
        'sec_solutions',
        'sec_process',
        'sec_roles',
        'sec_models',
        'sec_projects',
        'sec_partners',
        'sec_clients',
        'sec_commitments',
        'sec_warranty',
        'sec_thanks',
    ];

    public function index()
    {
        abort_if(Gate::denies('company_profile_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $settings = Setting::whereNull('event_id')->pluck('value', 'key')->toArray();
        $posts = Post::orderBy('created_at', 'desc')->get();
        $sections = $this->sectionKeys;

        return view('admin.company-profile.index', compact('settings', 'posts', 'sections'));
    }

    public function update(Request $request)
    {
        abort_if(Gate::denies('company_profile_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->only($this->editableKeys);

        foreach ($this->sectionKeys as $section) {
            $data[$section . '_title'] = $request->input($section . '_title', '');
            $data[$section . '_subtitle'] = $request->input($section . '_subtitle', '');
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key, 'event_id' => null],
                ['value' => $value]
            );
        }

        return back()->with('message', 'Company profile updated successfully.');
    }
}
