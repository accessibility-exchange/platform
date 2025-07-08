<?php

use App\Enums\UserContext;
use App\Models\Document;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;

use function Pest\Faker\fake;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

beforeEach(function () {
    Storage::fake('public');

    $this->date = fake()->date('Y-m-d');

    $this->document = Document::factory()
        ->hasRevisions(1, [
            'date' => $this->date,
            'file' => [
                'en' => "documents/example-document-{$this->date}-en.txt",
            ],
        ])
        ->create();
    $this->document->refresh();

    $this->revision = $this->document->revisions->first();

    Storage::disk('public')->put("documents/example-document-{$this->date}-en.txt", 'English content');

    $this->admin = User::factory()->create(['context' => UserContext::Administrator->value]);
    $this->user = User::factory()->create();
    $this->guestEmail = fake()->email;
});

test('document revisions can be downloaded by anonymous guests', function () {
    post(localized_route('download', [
        'revision' => $this->revision->id,
        'email' => null,
    ], 'en'))
        ->assertDownload("example-document-{$this->date}-en.txt");

    $loggedActivity = Activity::first();

    expect($loggedActivity->subject->id)->toBe($this->revision->id);
    expect($loggedActivity->event)->toBe('downloaded');
    expect($loggedActivity->properties['email'])->toBeNull();
    expect($loggedActivity->causer)->toBeNull();
    expect($loggedActivity->description)->toBe("Anonymous guest downloaded revision {$this->revision->id} of document {$this->document->id}.");

    actingAs($this->admin)
        ->get(route('filament.admin.pages.downloads'))
        ->assertSeeInOrder([$this->revision->document->name, $this->date, 'English']);
});

test('document revisions can be downloaded by guests identified by email', function () {
    post(localized_route('download', [
        'revision' => $this->revision->id,
        'email' => $this->guestEmail,
    ], 'en'))
        ->assertDownload("example-document-{$this->date}-en.txt");

    $loggedActivity = Activity::first();

    expect($loggedActivity->subject->id)->toBe($this->revision->id);
    expect($loggedActivity->event)->toBe('downloaded');
    expect($loggedActivity->properties['email'])->toBe($this->guestEmail);
    expect($loggedActivity->causer)->toBeNull();
    expect($loggedActivity->description)->toBe("Guest with email {$this->guestEmail} downloaded revision {$this->revision->id} of document {$this->document->id}.");

    actingAs($this->admin)
        ->get(route('filament.admin.pages.downloads'))
        ->assertSeeInOrder([$this->revision->document->name, $this->date, 'English']);
});

test('document revisions can be downloaded by users', function () {

    actingAs($this->user)->post(localized_route('download', [
        'revision' => $this->revision->id,
        'email' => $this->user->email,
    ], 'en'))
        ->assertDownload("example-document-{$this->date}-en.txt");

    $loggedActivity = Activity::first();

    expect($loggedActivity->subject->id)->toBe($this->revision->id);
    expect($loggedActivity->event)->toBe('downloaded');
    expect($loggedActivity->properties['email'])->toBe($this->user->email);
    expect($loggedActivity->causer->id)->toBe($this->user->id);
    expect($loggedActivity->description)->toBe("User with email {$this->user->email} downloaded revision {$this->revision->id} of document {$this->document->id}.");

    actingAs($this->admin)
        ->get(route('filament.admin.pages.downloads'))
        ->assertSeeInOrder([$this->revision->document->name, $this->date, 'English']);
});
