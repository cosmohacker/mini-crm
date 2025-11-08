<?php

namespace App\Http\Controllers;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

use App\Models\Ticket;

class FileController extends Controller
{
    public function index()
    {
        return view('admin.files.index');
    }

    public function details($id)
    {
        $media = Media::findOrFail($id);

        // Checking the file existence
        if (!$media->getPath()) {
            abort(404);
        }

        return response()->file($media->getPath());
    }
}
