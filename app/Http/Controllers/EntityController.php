<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Http\Requests\CreateEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use App\Http\Requests\DestroyEntityRequest;
use Illuminate\Http\Request;

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('entities.index', ['entities' => Entity::orderBy('name')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', Entity::class);

        return view('entities.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateEntityRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateEntityRequest $request)
    {
        $entity = Entity::create($request->validated());

        $entity->users()->attach(
            $request->user(),
            ['role' => 'admin']
        );

        flash(__('entity.create_succeeded'), 'success');

        return redirect(localized_route('entities.show', $entity));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Entity  $entity
     * @return \Illuminate\View\View
     */
    public function show(Entity $entity)
    {
        return view('entities.show', ['entity' => $entity]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Entity  $entity
     * @return \Illuminate\View\View
     */
    public function edit(Entity $entity)
    {
        $roles = [];

        foreach (config('roles') as $role) {
            $roles[$role] = __('roles.' . $role);
        }

        return view('entities.edit', [
            'entity' => $entity,
            'roles' => $roles
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEntityRequest  $request
     * @param  \App\Models\Entity  $entity
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateEntityRequest $request, Entity $entity)
    {
        $entity->fill($request->validated());
        $entity->save();

        flash(__('entity.update_succeeded'), 'success');

        return redirect(localized_route('entities.show', $entity));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\DestroyEntityRequest  $request
     * @param  \App\Models\Entity  $entity
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DestroyEntityRequest $request, Entity $entity)
    {
        $entity->delete();

        flash(__('entity.destroy_succeeded'), 'success');

        return redirect(localized_route('dashboard'));
    }
}
