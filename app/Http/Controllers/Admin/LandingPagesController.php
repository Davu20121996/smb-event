<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyLandingPageRequest;
use App\Http\Requests\StoreLandingPageRequest;
use App\Http\Requests\UpdateLandingPageRequest;
use App\LandingPage;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LandingPagesController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('landing_page_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $landingPages = LandingPage::all();

        return view('admin.landing-pages.index', compact('landingPages'));
    }

    public function create()
    {
        abort_if(Gate::denies('landing_page_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.landing-pages.create');
    }

    public function store(StoreLandingPageRequest $request)
    {
        $landingPage = LandingPage::create($this->fillData($request));

        if ($coverPath = $this->tmpUploadPath($request->input('cover'))) {
            $landingPage->addMedia($coverPath)->toMediaCollection('cover');
        }

        if ($thumbnailPath = $this->tmpUploadPath($request->input('thumbnail'))) {
            $landingPage->addMedia($thumbnailPath)->toMediaCollection('thumbnail');
        }

        if ($pdfPath = $this->tmpUploadPath($request->input('pdf_file'))) {
            $landingPage->addMedia($pdfPath)->toMediaCollection('pdf');
        }

        if ($avatarPath = $this->tmpUploadPath($request->input('speaker_avatar'))) {
            $landingPage->addMedia($avatarPath)->toMediaCollection('speaker_avatar');
        }

        return redirect()->route('admin.landing-pages.index');
    }

    public function edit(LandingPage $landingPage)
    {
        abort_if(Gate::denies('landing_page_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.landing-pages.edit', compact('landingPage'));
    }

    public function update(UpdateLandingPageRequest $request, LandingPage $landingPage)
    {
        $landingPage->update($this->fillData($request));

        if ($request->input('cover', false)) {
            if (!$landingPage->cover || $request->input('cover') !== $landingPage->cover->file_name) {
                if ($coverPath = $this->tmpUploadPath($request->input('cover'))) {
                    $landingPage->addMedia($coverPath)->toMediaCollection('cover');
                }
            }
        } elseif ($landingPage->cover) {
            $landingPage->cover->delete();
        }

        if ($request->input('thumbnail', false)) {
            if (!$landingPage->thumbnail || $request->input('thumbnail') !== $landingPage->thumbnail->file_name) {
                if ($thumbnailPath = $this->tmpUploadPath($request->input('thumbnail'))) {
                    $landingPage->addMedia($thumbnailPath)->toMediaCollection('thumbnail');
                }
            }
        } elseif ($landingPage->thumbnail) {
            $landingPage->thumbnail->delete();
        }

        if ($request->input('pdf_file', false)) {
            if (!$landingPage->pdf_file || $request->input('pdf_file') !== $landingPage->pdf_file->file_name) {
                if ($pdfPath = $this->tmpUploadPath($request->input('pdf_file'))) {
                    $landingPage->addMedia($pdfPath)->toMediaCollection('pdf');
                }
            }
        } elseif ($landingPage->pdf_file) {
            $landingPage->pdf_file->delete();
        }

        if ($request->input('speaker_avatar', false)) {
            if (!$landingPage->speaker_avatar || $request->input('speaker_avatar') !== $landingPage->speaker_avatar->file_name) {
                if ($avatarPath = $this->tmpUploadPath($request->input('speaker_avatar'))) {
                    $landingPage->addMedia($avatarPath)->toMediaCollection('speaker_avatar');
                }
            }
        } elseif ($landingPage->speaker_avatar) {
            $landingPage->speaker_avatar->delete();
        }

        return redirect()->route('admin.landing-pages.index');
    }

    public function show(LandingPage $landingPage)
    {
        abort_if(Gate::denies('landing_page_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.landing-pages.show', compact('landingPage'));
    }

    public function destroy(LandingPage $landingPage)
    {
        abort_if(Gate::denies('landing_page_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $landingPage->delete();

        return back();
    }

    public function massDestroy(MassDestroyLandingPageRequest $request)
    {
        LandingPage::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function fillData(Request $request)
    {
        $data = $request->only([
            'title',
            'slug',
            'content',
            'form_title',
            'button_title',
            'crm_tag',
            'pdf_enabled',
            'pdf_source',
            'pdf_url',
            'download_title',
            'download_button_title',
            'report_url',
            'is_published',
            'countdown_enabled',
            'registration_deadline',
            'speaker_name',
            'speaker_role',
            'speaker_company',
            'speaker_bio',
            'calendar_enabled',
            'zalo_url',
            'fanpage_url',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $data['pdf_enabled']  = $request->has('pdf_enabled') ? 1 : 0;
        $data['is_published'] = $request->has('is_published') ? 1 : 0;

        if (!$data['pdf_enabled']) {
            $data['pdf_source']          = null;
            $data['pdf_url']             = null;
            $data['download_title']      = null;
            $data['download_button_title'] = null;
            $data['report_url']          = null;
        }

        $data['countdown_enabled'] = $request->has('countdown_enabled') ? 1 : 0;
        $data['calendar_enabled']  = $request->has('calendar_enabled') ? 1 : 0;

        if (empty($data['registration_deadline'])) {
            $data['registration_deadline'] = null;
        }

        foreach (['pdf_url', 'report_url', 'zalo_url', 'fanpage_url'] as $urlField) {
            $data[$urlField] = safe_href($data[$urlField] ?? null);
        }

        $data['content'] = clean_html($data['content'] ?? null);

        $data['key_benefits'] = $this->normalizeList($request->input('key_benefits', []));
        $data['agenda']       = $this->normalizeList($request->input('agenda', []));

        return $data;
    }

    private function normalizeList($items)
    {
        if (!is_array($items)) {
            return null;
        }

        $items = array_values($items);
        $items = array_filter($items, function ($item) {
            return is_array($item) && array_filter($item, function ($value) {
                return !is_null($value) && trim($value) !== '';
            });
        });

        return empty($items) ? null : $items;
    }
}
