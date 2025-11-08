<?php

namespace App\Http\Controllers;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

use App\Models\Ticket;

class FileController extends Controller
{
    public function index()
    {
        // Flat map is used here for nested collections: tickets -> media files into a single collection
        // Otherwise i have to use nested foreach
        $files = Ticket::with('media', 'customer')->get()
            ->flatMap(function ($ticket) {
                return $ticket->getMedia('images')->map(function ($media) use ($ticket) {
                    $mimeType = '';
                    if ($media->mime_type) {
                        $mimeType = $media->mime_type;
                    }

                    return [
                        'file_name'      => $media->file_name,
                        'created_at'     => $media->created_at,
                        'url'            => route('media.show', $media->id),
                        'ticket_subject' => $ticket->subject,
                        'customer_name'  => $ticket->customer->name ?? 'N/A',
                        'is_image'       => str_starts_with($mimeType, 'image/'),
                    ];
                });
            })
            ->sortByDesc('created_at')
            ->values();

        return view('admin.files.index', compact('files'));
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
