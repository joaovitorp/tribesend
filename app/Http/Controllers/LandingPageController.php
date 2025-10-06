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
            $waitlist = $this->storeWaitlistService->execute($request->validated());

            return redirect()
                ->route('waitlist.success')
                ->with('email', $waitlist->email);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao processar sua inscrição. Tente novamente.')
                ->withInput();
        }
    }

    /**
     * Exibe a página de confirmação de inscrição.
     */
    public function success(): Response|RedirectResponse
    {
        if (! session()->has('email')) {
            return redirect()->route('home');
        }

        return Inertia::render('Landing/Success', [
            'email' => session('email'),
        ]);
    }
}
