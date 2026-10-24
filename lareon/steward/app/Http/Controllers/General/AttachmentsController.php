<?php

namespace Lareon\Steward\App\Http\Controllers\General;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Lareon\Steward\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Steward\App\Http\Requests\General\AttachmentRequest;

class AttachmentsController extends Controller
{
    public function show(AttachmentRequest $request)
    {
        $data = $request->validated();

        $disk = Storage::disk($data['disk']);
        $path = $data['path'];

        abort_unless($disk->exists($path), 404);

        $originalName = basename($path);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        $filename = $originalName;

        if (! empty($data['filename'])) {
            $name = basename(str_replace('\\', '/', $data['filename']));
            $name = pathinfo($name, PATHINFO_FILENAME);
            $filename = $name . ($extension !== '' ? ".{$extension}" : '');
        }

        return $disk->download($path, $filename, [
            'X-Robots-Tag'           => 'noindex, nofollow, noarchive',
            'Cache-Control'          => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
