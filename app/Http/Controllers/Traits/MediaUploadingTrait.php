<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

trait MediaUploadingTrait
{
    public function storeMedia(Request $request)
    {
        // Keep the shared temporary uploader aligned with the 5 MB limit
        // displayed on the Event management forms.
        $requestedSize = (float) request()->input('size', 5);
        if ($requestedSize <= 0) {
            $requestedSize = 2;
        }

        $sizeMb = min($requestedSize, 20);

        $this->validate(request(), [
            'file' => 'max:' . (int) round($sizeMb * 1024),
        ]);

        if (request()->has('width') || request()->has('height')) {
            $this->validate(request(), [
                'file' => sprintf(
                    'image|dimensions:max_width=%s,max_height=%s',
                    request()->input('width', 100000),
                    request()->input('height', 100000)
                ),
            ]);
        } else {
            $this->validate(request(), [
                'file' => 'mimes:jpg,jpeg,png,gif,webp,pdf',
            ]);
        }

        $path = storage_path('tmp/uploads');

        try {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        } catch (\Exception $e) {
        }

        $file = $request->file('file');

        $name = $file->hashName();

        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    protected function tmpUploadPath($filename)
    {
        $filename = (string) $filename;

        if ($filename === '' || $filename !== basename($filename) || str_contains($filename, '..')) {
            return null;
        }

        $path = storage_path('tmp/uploads/' . $filename);

        return is_file($path) ? $path : null;
    }
}
