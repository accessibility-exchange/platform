<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Revision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DownloadController extends Controller
{
    public function __invoke(Request $request, Revision $revision): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'nullable|email',
        ]);

        $email = isset($validated['email']) ? $validated['email'] : null;

        if (request()->user()) {
            $user = request()->user();
            /** @var Document */
            $document = $revision->document;

            activity()
                ->causedBy($user)
                ->performedOn($revision)
                ->event('downloaded')
                ->withProperties([
                    'email' => $email,
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
                    'email' => $email,
                    'language' => locale(),
                ])
                ->log(
                    $email ?
                        "Guest with email {$email} downloaded revision {$revision->id} of document {$document->id}." :
                        "Anonymous guest downloaded revision {$revision->id} of document {$document->id}."
                );
        }

        return redirect(Storage::disk('documents-s3')->url($revision->getTranslation('file', locale())));
    }
}
