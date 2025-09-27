<?php

namespace App\Http\Controllers;

use App\Http\Requests\Team\CreateTeamRequest;
use App\Services\Team\CreateTeamService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class OnboardingController extends Controller
{
    public function __construct(
        private CreateTeamService $createTeamService
    ) {}

    /**
     * Show the onboarding page for creating a team.
     */
    public function create(): Response
    {
        $categories = [
            'AI',
            'Developer',
            'Marketing',
            'Game Development',
            'Journalist',
            'Writer',
            'Travel',
            'E-commerce',
            'Finance',
            'Healthcare',
            'Education',
            'Consulting',
            'Design',
            'Photography',
            'Music',
            'Sports',
            'Food & Beverage',
            'Real Estate',
            'Legal',
            'Non-profit',
            'Other',
        ];

        return Inertia::render('Onboarding/CreateTeam', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store the team and complete onboarding.
     */
    public function store(CreateTeamRequest $request): RedirectResponse
    {
        $team = $this->createTeamService->execute(
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('dashboard')
            ->with('success', 'Time criado com sucesso! Bem-vindo ao TribeSend.');
    }
}
