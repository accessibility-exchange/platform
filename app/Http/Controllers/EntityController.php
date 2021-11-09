<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEntityRequest;
use App\Http\Requests\DestroyEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use App\Models\Entity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('entities.index', ['entities' => Entity::orderBy('name')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $this->authorize('create', Entity::class);

        return view('entities.create', [
            'regions' => get_regions(['CA'], \locale()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateEntityRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateEntityRequest $request): RedirectResponse
    {
        $entity = Entity::create($request->validated());

        $entity->users()->attach(
            $request->user(),
            ['role' => 'admin']
        );

        flash(__('Your regulated entity has been created.'), 'success');


        return redirect(\localized_route('entities.show', $entity));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Entity  $entity
     * @return \Illuminate\View\View
     */
    public function show(Entity $entity): View
    {
        if (Route::currentRouteName() === \locale() . '.entities.show-projects') {
            $entity->load('currentProjects');
        } elseif (Route::currentRouteName() === \locale() . '.entities.show-projects') {
            $entity->load('pastProjects', 'currentProjects');
        }

        return view('entities.show', compact('entity'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Entity  $entity
     * @return \Illuminate\View\View
     */
    public function edit(Entity $entity): View
    {
        $roles = [];

        foreach (config('hearth.organizations.roles') as $role) {
            $roles[$role] = __('roles.' . $role);
        }

        return view('entities.edit', [
            'entity' => $entity,
            'regions' => get_regions(['CA'], \locale()),
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEntityRequest  $request
     * @param  \App\Models\Entity  $entity
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateEntityRequest $request, Entity $entity): RedirectResponse
    {
        $entity->fill($request->validated());
        $entity->save();

        flash(__('Your regulated entity has been updated.'), 'success');

        return redirect(\localized_route('entities.show', $entity));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\DestroyEntityRequest  $request
     * @param  \App\Models\Entity  $entity
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DestroyEntityRequest $request, Entity $entity): RedirectResponse
    {
        $entity->delete();

        flash(__('Your regulated entity has been deleted.'), 'success');

        return redirect(\localized_route('dashboard'));
    }
}
