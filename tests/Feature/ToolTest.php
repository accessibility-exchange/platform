<?php

use App\Enums\UserContext;
use App\Models\Document;
use App\Models\Revision;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Spatie\Translatable\Exceptions\AttributeIsNotTranslatable;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('tools can be translated', function () {
    $tool = Tool::factory()->create();

    $titleTranslations = ['en' => 'title in English', 'fr' => 'title in French'];
    $descriptionTranslations = ['en' => 'description in English', 'fr' => 'description in French'];

    $tool->setTranslation('title', 'en', $titleTranslations['en']);
    $tool->setTranslation('title', 'fr', $titleTranslations['fr']);

    $tool->setTranslation('description', 'en', $descriptionTranslations['en']);
    $tool->setTranslation('description', 'fr', $descriptionTranslations['fr']);

    expect($tool->title)->toEqual($titleTranslations['en']);
    expect($tool->description)->toEqual($descriptionTranslations['en']);
    App::setLocale('fr');
    expect($tool->title)->toEqual($titleTranslations['fr']);
    expect($tool->description)->toEqual($descriptionTranslations['fr']);

    expect($tool->getTranslation('title', 'en'))->toEqual($titleTranslations['en']);
    expect($tool->getTranslation('description', 'en'))->toEqual($descriptionTranslations['en']);
    expect($tool->getTranslation('title', 'fr'))->toEqual($titleTranslations['fr']);
    expect($tool->getTranslation('description', 'fr'))->toEqual($descriptionTranslations['fr']);

    expect($tool->getTranslations('title'))->toEqual($titleTranslations);
    expect($tool->getTranslations('description'))->toEqual($descriptionTranslations);

    $this->expectException(AttributeIsNotTranslatable::class);
    $tool->setTranslation('user_id', 'en', 'user_id in English');
});

test('many documents can belong to single tool', function () {
    $tool = Tool::factory()->create();

    $documents = Document::factory(3)->create();

    foreach ($documents as $document) {
        $tool->documents()->save($document);
        $tool->refresh();
        expect($tool->documents->pluck('id')->toArray())->toContain($document->id);
    }
});

test('deleting documents belonging to a tool removes them from the tool', function () {
    $tool = Tool::factory()->create();
    $document = Document::factory()->create();
    $tool->documents()->save($document);

    expect($tool->documents->first()->id)->toBe($document->id);

    $document->delete();

    $tool->refresh();

    expect($tool->documents->count())->toBe(0);
});

test('document revisions can be accessed from a tool', function () {
    $tool = Tool::factory()->create();

    $document = Document::factory()->create();
    $revision = Revision::factory()->for($document)->create();

    $tool->documents()->save($document);
    $tool->refresh();

    expect($tool->revisions->first()->id)->toBe($revision->id);
});

test('users can view tools', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => UserContext::Administrator->value]);
    $tool = Tool::factory()->create();

    get(localized_route('tools.index'))
        ->assertOk()
        ->assertSee($tool->title);

    actingAs($user)->get(localized_route('tools.index'))
        ->assertOk()
        ->assertSee($tool->title);

    actingAs($user)->get(localized_route('tools.show', $tool))
        ->assertOk()
        ->assertSee($tool->title)
        ->assertDontSee('Edit tool');

    actingAs($administrator)->get(localized_route('tools.show', $tool))
        ->assertOk()
        ->assertSee('Edit tool');
});
