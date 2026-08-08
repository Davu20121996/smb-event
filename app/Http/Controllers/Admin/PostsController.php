<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPostRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Post;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PostsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('post_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $posts = Post::all();

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        abort_if(Gate::denies('post_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.posts.create');
    }

    public function store(StorePostRequest $request)
    {
        $post = Post::create($this->fillData($request));

        if ($coverPath = $this->tmpUploadPath($request->input('cover'))) {
            $post->addMedia($coverPath)->toMediaCollection('cover');
        }

        if ($thumbnailPath = $this->tmpUploadPath($request->input('thumbnail'))) {
            $post->addMedia($thumbnailPath)->toMediaCollection('thumbnail');
        }

        return redirect()->route('admin.posts.index');
    }

    public function edit(Post $post)
    {
        abort_if(Gate::denies('post_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.posts.edit', compact('post'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($this->fillData($request));

        if ($request->input('cover', false)) {
            if (!$post->cover || $request->input('cover') !== $post->cover->file_name) {
                if ($coverPath = $this->tmpUploadPath($request->input('cover'))) {
                    $post->addMedia($coverPath)->toMediaCollection('cover');
                }
            }
        } elseif ($post->cover) {
            $post->cover->delete();
        }

        if ($request->input('thumbnail', false)) {
            if (!$post->thumbnail || $request->input('thumbnail') !== $post->thumbnail->file_name) {
                if ($thumbnailPath = $this->tmpUploadPath($request->input('thumbnail'))) {
                    $post->addMedia($thumbnailPath)->toMediaCollection('thumbnail');
                }
            }
        } elseif ($post->thumbnail) {
            $post->thumbnail->delete();
        }

        return redirect()->route('admin.posts.index');
    }

    public function show(Post $post)
    {
        abort_if(Gate::denies('post_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.posts.show', compact('post'));
    }

    public function destroy(Post $post)
    {
        abort_if(Gate::denies('post_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $post->delete();

        return back();
    }

    public function massDestroy(MassDestroyPostRequest $request)
    {
        Post::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function fillData(Request $request)
    {
        $data = $request->only([
            'title',
            'slug',
            'tag',
            'excerpt',
            'content',
            'is_published',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $data['content'] = clean_html($data['content'] ?? null);

        return $data;
    }
}
