<?php

namespace App\Http\Controllers;

use App\Http\Requests\Waitlist\StoreWaitlistRequest;
use App\Services\Waitlist\StoreWaitlistService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class LandingPageController extends Controller
{
    public function __construct(
        private StoreWaitlistService $storeWaitlistService
    ) {}

    /**
     * Exibe a landing page.
     */
    public function index(): Response
    {
        return Inertia::render('Landing/Index');
    }

    /**
     * Armazena uma nova inscrição na waitlist.
     */
    public function subscribe(StoreWaitlistRequest $request): RedirectResponse
    {
        try {
            $this->storeWaitlistService->execute($request->validated());

            return redirect()
                ->back()
                ->with('success', 'Obrigado! Você entrou na lista de espera para o TribeSend.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao processar sua inscrição. Tente novamente.')
                ->withInput();
        }
    }
}
