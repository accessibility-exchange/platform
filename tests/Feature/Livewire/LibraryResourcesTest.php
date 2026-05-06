<?php

use App\Livewire\LibraryResources;
use App\Models\Library;
use App\Models\ResourceCollection;
use Livewire\Livewire;

use function Pest\Laravel\get;

it('renders successfully', function () {
    Livewire::test(LibraryResources::class)
        ->assertStatus(200);
});

test('resource collections can be paginated in English', function () {
    $library = Library::factory()->create([
        'title' => ['en' => 'Inclusive Employment', 'fr' => 'Emploi Inclusif'],
    ]);

    $collections = ResourceCollection::factory(11)->create();
    $library->resourceCollections()->attach($collections);

    /** @var \Illuminate\Pagination\LengthAwarePaginator|null $firstPageResults */
    $firstPageResults = null;

    Livewire::test(LibraryResources::class, ['library' => $library])
        ->assertStatus(200)
        ->assertViewHas('resourceCollections', function ($resourceCollections) use (&$firstPageResults) {
            $firstPageResults = $resourceCollections;

            return $resourceCollections->count() === 10;
        })
        ->call('setPage', 2, pageName: __('collections-page'))
        ->assertStatus(200)
        ->assertViewHas('resourceCollections', function ($resourceCollections) use ($firstPageResults) {
            return $resourceCollections->count() === 1
                && ! $firstPageResults->contains('id', $resourceCollections->first()->id);
        });
});

test('resource collections can be paginated in French', function () {
    $library = Library::factory()->create([
        'title' => ['en' => 'Inclusive Employment', 'fr' => 'Emploi Inclusif'],
    ]);

    $collections = ResourceCollection::factory(11)->create();
    $library->resourceCollections()->attach($collections);

    app()->setLocale('fr');
    session(['locale' => 'fr']);

    /** @var \Illuminate\Pagination\LengthAwarePaginator|null $firstPageResults */
    $firstPageResults = null;

    Livewire::test(LibraryResources::class, ['library' => $library])
        ->assertStatus(200)
        ->assertViewHas('resourceCollections', function ($resourceCollections) use (&$firstPageResults) {
            $firstPageResults = $resourceCollections;

            return $resourceCollections->count() === 10;
        })
        ->call('setPage', 2, pageName: __('collections-page'))
        ->assertStatus(200)
        ->assertViewHas('resourceCollections', function ($resourceCollections) use ($firstPageResults) {
            return $resourceCollections->count() === 1
                && ! $firstPageResults->contains('id', $resourceCollections->first()->id);
        });
});

test('locale is stored in the session after visiting a French page', function () {
    $library = Library::factory()->create([
        'title' => ['en' => 'Inclusive Employment', 'fr' => 'Emploi Inclusif'],
    ]);

    app()->setLocale('fr');
    $url = localized_route('libraries.show', $library);
    app()->setLocale('en');

    get($url)->assertOk();

    expect(session('locale'))->toBe('fr');
});

test('session locale is updated when user switches from French to English', function () {
    $library = Library::factory()->create([
        'title' => ['en' => 'Inclusive Employment', 'fr' => 'Emploi Inclusif'],
    ]);

    app()->setLocale('fr');
    $frenchUrl = localized_route('libraries.show', $library);

    app()->setLocale('en');
    $englishUrl = localized_route('libraries.show', $library);

    get($frenchUrl)->assertOk();
    expect(session('locale'))->toBe('fr');

    get($englishUrl)->assertOk();
    expect(session('locale'))->toBe('en');
});
