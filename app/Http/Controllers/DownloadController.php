<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Revision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadController extends Controller
{
    public function __invoke(Request $request, Revision $revision): BinaryFileResponse
    {
        $validated = $request->validate([
            'email' => 'nullable|email',
        ]);

        if (request()->user()) {
            $user = request()->user();
            /** @var Document */
            $document = $revision->document;

            activity()
                ->causedBy($user)
                ->performedOn($revision)
                ->event('downloaded')
                ->withProperties([
                    'email' => $validated['email'],
                    'language' => locale(),
                ])
                ->log("User with email {$validated['email']} downloaded revision {$revision->id} of document {$document->id}.");
        } else {
            /** @var Document */
            $document = $revision->document;

            activity()
                ->performedOn($revision)
                ->event('downloaded')
                ->withProperties([
                    'email' => $validated['email'],
                    'language' => locale(),
                ])
                ->log(
                    $validated['email'] ?
                        "Guest with email {$validated['email']} downloaded revision {$revision->id} of document {$document->id}." :
                        "Anonymous guest downloaded revision {$revision->id} of document {$document->id}."
                );
        }

        return response()->download(Storage::disk('public')->path($revision->getTranslation('file', locale())));
    }
}
