<?php

namespace App\Http\Controllers;

use App\Http\Requests\Form\StoreFormRequest;
use App\Http\Requests\Form\UpdateFormRequest;
use App\Models\Form;
use App\Models\Segment;
use App\Services\Form\CreateFormService;
use App\Services\Form\DeleteFormService;
use App\Services\Form\GetAllFormsService;
use App\Services\Form\GetFormDetailsService;
use App\Services\Form\UpdateFormService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FormController extends Controller
{
    public function __construct(
        private GetAllFormsService $getAllFormsService,
        private GetFormDetailsService $getFormDetailsService,
        private CreateFormService $createFormService,
        private UpdateFormService $updateFormService,
        private DeleteFormService $deleteFormService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $team = $request->user()->currentTeam;
        $forms = $this->getAllFormsService->execute($team, $request->all());

        return Inertia::render('Forms/Index', [
            'forms' => $forms,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response
    {
        $team = $request->user()->currentTeam;
        $segments = Segment::where('team_id', $team->id)
            ->where('is_active', true)
            ->get(['id', 'name', 'description']);

        return Inertia::render('Forms/Create', [
            'segments' => $segments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFormRequest $request): RedirectResponse
    {
        $team = $request->user()->currentTeam;
        $form = $this->createFormService->execute($request->validated(), $team);

        return redirect()
            ->route('forms.index')
            ->with('success', 'Formulário criado com sucesso');
    }

    /**
     * Display the specified resource.
     */
    public function show(Form $form): Response
    {
        $formData = $this->getFormDetailsService->execute($form);

        return Inertia::render('Forms/Show', [
            'form' => $formData,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Form $form, Request $request): Response
    {
        $team = $request->user()->currentTeam;
        $segments = Segment::where('team_id', $team->id)
            ->where('is_active', true)
            ->get(['id', 'name', 'description']);

        return Inertia::render('Forms/Edit', [
            'form' => $form,
            'segments' => $segments,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFormRequest $request, Form $form): RedirectResponse
    {
        $this->updateFormService->execute($form, $request->validated());

        return redirect()
            ->route('forms.index')
            ->with('success', 'Formulário atualizado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form): RedirectResponse
    {
        $this->deleteFormService->execute($form);

        return redirect()
            ->route('forms.index')
            ->with('success', 'Formulário excluído com sucesso');
    }
}
