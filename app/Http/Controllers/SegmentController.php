<?php

namespace App\Http\Controllers;

use App\Http\Requests\Segment\StoreSegmentRequest;
use App\Http\Requests\Segment\UpdateSegmentRequest;
use App\Models\Segment;
use App\Services\Segment\CreateSegmentService;
use App\Services\Segment\DeleteSegmentService;
use App\Services\Segment\GetAllSegmentsService;
use App\Services\Segment\GetSegmentDetailsService;
use App\Services\Segment\UpdateSegmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SegmentController extends Controller
{
    public function __construct(
        private GetAllSegmentsService $getAllSegmentsService,
        private GetSegmentDetailsService $getSegmentDetailsService,
        private CreateSegmentService $createSegmentService,
        private UpdateSegmentService $updateSegmentService,
        private DeleteSegmentService $deleteSegmentService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $team = $request->user()->currentTeam;
        $segments = $this->getAllSegmentsService->execute($team, $request->all());

        return Inertia::render('Segments/Index', [
            'segments' => $segments,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Segments/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSegmentRequest $request): RedirectResponse
    {
        $team = $request->user()->currentTeam;
        $segment = $this->createSegmentService->execute($request->validated(), $team);

        return redirect()
            ->route('segments.index')
            ->with('success', 'Segmento criado com sucesso');
    }

    /**
     * Display the specified resource.
     */
    public function show(Segment $segment, Request $request): Response
    {
        $team = $request->user()->currentTeam;

        if ($segment->team_id !== $team->id) {
            abort(403);
        }

        $segmentData = $this->getSegmentDetailsService->execute($segment);

        return Inertia::render('Segments/Show', [
            'segment' => $segmentData,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Segment $segment, Request $request): Response
    {
        $team = $request->user()->currentTeam;

        if ($segment->team_id !== $team->id) {
            abort(403);
        }

        return Inertia::render('Segments/Edit', [
            'segment' => $segment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSegmentRequest $request, Segment $segment): RedirectResponse
    {
        $team = $request->user()->currentTeam;

        if ($segment->team_id !== $team->id) {
            abort(403);
        }

        $this->updateSegmentService->execute($segment, $request->validated());

        return redirect()
            ->route('segments.index')
            ->with('success', 'Segmento atualizado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Segment $segment, Request $request): RedirectResponse
    {
        $team = $request->user()->currentTeam;

        if ($segment->team_id !== $team->id) {
            abort(403);
        }

        try {
            $this->deleteSegmentService->execute($segment);

            return redirect()
                ->route('segments.index')
                ->with('success', 'Segmento excluído com sucesso');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
