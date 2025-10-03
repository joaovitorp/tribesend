<?php

namespace App\Http\Controllers;

use App\Http\Requests\Subscriber\StoreSubscriberRequest;
use App\Http\Requests\Subscriber\UpdateSubscriberRequest;
use App\Models\Subscriber;
use App\Services\Subscriber\CreateSubscriberService;
use App\Services\Subscriber\DeleteSubscriberService;
use App\Services\Subscriber\GetAllSubscribersService;
use App\Services\Subscriber\GetSubscriberDetailsService;
use App\Services\Subscriber\UpdateSubscriberService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubscriberController extends Controller
{
    public function __construct(
        private GetAllSubscribersService $getAllSubscribersService,
        private GetSubscriberDetailsService $getSubscriberDetailsService,
        private CreateSubscriberService $createSubscriberService,
        private UpdateSubscriberService $updateSubscriberService,
        private DeleteSubscriberService $deleteSubscriberService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $team = $request->user()->currentTeam;
        $subscribers = $this->getAllSubscribersService->execute($team, $request->all());

        return Inertia::render('Subscribers/Index', [
            'subscribers' => $subscribers,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Subscribers/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubscriberRequest $request): RedirectResponse
    {
        $team = $request->user()->currentTeam;

        try {
            $this->createSubscriberService->execute($request->validated(), $team);

            return redirect()
                ->route('subscribers.index')
                ->with('success', 'Assinante criado com sucesso');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao criar assinante: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscriber $subscriber): Response
    {
        $subscriberData = $this->getSubscriberDetailsService->execute($subscriber);

        return Inertia::render('Subscribers/Show', [
            'subscriber' => $subscriberData,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Subscriber $subscriber): Response
    {
        return Inertia::render('Subscribers/Edit', [
            'subscriber' => $subscriber,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubscriberRequest $request, Subscriber $subscriber): RedirectResponse
    {
        try {
            $this->updateSubscriberService->execute($subscriber, $request->validated());

            return redirect()
                ->route('subscribers.index')
                ->with('success', 'Assinante atualizado com sucesso');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao atualizar assinante: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscriber $subscriber): RedirectResponse
    {
        try {
            $this->deleteSubscriberService->execute($subscriber);

            return redirect()
                ->route('subscribers.index')
                ->with('success', 'Assinante excluído com sucesso');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir assinante: '.$e->getMessage());
        }
    }
}
