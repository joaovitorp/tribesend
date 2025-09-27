<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriberGroup\StoreSubscriberGroupRequest;
use App\Http\Requests\SubscriberGroup\UpdateSubscriberGroupRequest;
use App\Models\SubscriberGroup;
use App\Services\SubscriberGroup\CreateSubscriberGroupService;
use App\Services\SubscriberGroup\DeleteSubscriberGroupService;
use App\Services\SubscriberGroup\GetAllSubscriberGroupsService;
use App\Services\SubscriberGroup\GetSubscriberGroupDetailsService;
use App\Services\SubscriberGroup\UpdateSubscriberGroupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubscriberGroupController extends Controller
{
    public function __construct(
        private GetAllSubscriberGroupsService $getAllSubscriberGroupsService,
        private GetSubscriberGroupDetailsService $getSubscriberGroupDetailsService,
        private CreateSubscriberGroupService $createSubscriberGroupService,
        private UpdateSubscriberGroupService $updateSubscriberGroupService,
        private DeleteSubscriberGroupService $deleteSubscriberGroupService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $team = $request->user()->currentTeam;
        $subscriberGroups = $this->getAllSubscriberGroupsService->execute($team, $request->all());

        return Inertia::render('SubscriberGroups/Index', [
            'subscriberGroups' => $subscriberGroups,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('SubscriberGroups/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubscriberGroupRequest $request): RedirectResponse
    {
        $team = $request->user()->currentTeam;
        $subscriberGroup = $this->createSubscriberGroupService->execute($request->validated(), $team);

        return redirect()
            ->route('subscriber-groups.index')
            ->with('success', 'Grupo de assinantes criado com sucesso');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubscriberGroup $subscriberGroup, Request $request): Response
    {
        $team = $request->user()->currentTeam;

        if ($subscriberGroup->team_id !== $team->id) {
            abort(403);
        }

        $subscriberGroupData = $this->getSubscriberGroupDetailsService->execute($subscriberGroup);

        return Inertia::render('SubscriberGroups/Show', [
            'subscriberGroup' => $subscriberGroupData,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubscriberGroup $subscriberGroup, Request $request): Response
    {
        $team = $request->user()->currentTeam;

        if ($subscriberGroup->team_id !== $team->id) {
            abort(403);
        }

        return Inertia::render('SubscriberGroups/Edit', [
            'subscriberGroup' => $subscriberGroup,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubscriberGroupRequest $request, SubscriberGroup $subscriberGroup): RedirectResponse
    {
        $team = $request->user()->currentTeam;

        if ($subscriberGroup->team_id !== $team->id) {
            abort(403);
        }

        $this->updateSubscriberGroupService->execute($subscriberGroup, $request->validated());

        return redirect()
            ->route('subscriber-groups.index')
            ->with('success', 'Grupo de assinantes atualizado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubscriberGroup $subscriberGroup, Request $request): RedirectResponse
    {
        $team = $request->user()->currentTeam;

        if ($subscriberGroup->team_id !== $team->id) {
            abort(403);
        }

        try {
            $this->deleteSubscriberGroupService->execute($subscriberGroup);

            return redirect()
                ->route('subscriber-groups.index')
                ->with('success', 'Grupo de assinantes excluído com sucesso');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
