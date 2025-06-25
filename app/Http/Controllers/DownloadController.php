<?php

namespace App\Http\Controllers;

use App\Models\Revision;
use Illuminate\Http\Request;
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

            activity()
                ->causedBy($user)
                ->performedOn($revision)
                ->event('downloaded')
                ->withProperties([
                    'email' => $validated['email'],
                    'language' => locale(),
                ])
                ->log("User with email {$validated['email']} downloaded revision {$revision->id} of document {$revision->document->id}.");
        } else {
            activity()
                ->performedOn($revision)
                ->event('downloaded')
                ->withProperties([
                    'email' => $validated['email'],
                    'language' => locale(),
                ])
                ->log(
                    $validated['email'] ?
                        "Guest with email {$validated['email']} downloaded revision {$revision->id} of document {$revision->document->id}." :
                        "Anonymous guest downloaded revision {$revision->id} of document {$revision->document->id}."
                );
        }

        return response()->download(public_path('storage/'.$revision->getTranslation('file', locale())));
    }
}
