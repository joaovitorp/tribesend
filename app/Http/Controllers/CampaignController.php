<?php

namespace App\Http\Controllers;

use App\Http\Requests\Campaign\StoreCampaignRequest;
use App\Http\Requests\Campaign\UpdateCampaignRequest;
use App\Models\Campaign;
use App\Models\Segment;
use App\Services\Campaign\CreateCampaignService;
use App\Services\Campaign\DeleteCampaignService;
use App\Services\Campaign\GetAllCampaignsService;
use App\Services\Campaign\GetCampaignDetailsService;
use App\Services\Campaign\SendCampaignService;
use App\Services\Campaign\UpdateCampaignService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CampaignController extends Controller
{
    public function __construct(
        private GetAllCampaignsService $getAllCampaignsService,
        private GetCampaignDetailsService $getCampaignDetailsService,
        private CreateCampaignService $createCampaignService,
        private UpdateCampaignService $updateCampaignService,
        private DeleteCampaignService $deleteCampaignService,
        private SendCampaignService $sendCampaignService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $campaigns = $this->getAllCampaignsService->execute($request->all());

        return Inertia::render('Campaigns/Index', [
            'campaigns' => $campaigns,
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

        return Inertia::render('Campaigns/Create', [
            'segments' => $segments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCampaignRequest $request): RedirectResponse
    {
        try {
            $campaign = $this->createCampaignService->execute($request->validated());

            return redirect()
                ->route('campaigns.edit', $campaign)
                ->with('success', 'Campanha criada com sucesso');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao criar campanha: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign): Response
    {
        $campaignData = $this->getCampaignDetailsService->execute($campaign);

        return Inertia::render('Campaigns/Show', [
            'campaign' => $campaignData,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campaign $campaign, Request $request): Response
    {
        $team = $request->user()->currentTeam;
        $segments = Segment::where('team_id', $team->id)
            ->where('is_active', true)
            ->get(['id', 'name', 'description']);

        $campaign->load(['includedSegments', 'excludedSegments']);

        return Inertia::render('Campaigns/Edit', [
            'campaign' => $campaign,
            'segments' => $segments,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCampaignRequest $request, Campaign $campaign): RedirectResponse
    {
        try {
            $this->updateCampaignService->execute($campaign, $request->validated());

            return redirect()
                ->route('campaigns.edit', $campaign)
                ->with('success', 'Campanha atualizada com sucesso');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao atualizar campanha: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign): RedirectResponse
    {
        try {
            $this->deleteCampaignService->execute($campaign);

            return redirect()
                ->route('campaigns.index')
                ->with('success', 'Campanha excluída com sucesso');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Preview the campaign.
     */
    public function preview(Campaign $campaign): Response
    {
        $campaignData = $this->getCampaignDetailsService->execute($campaign);

        return Inertia::render('Campaigns/Preview', [
            'campaign' => $campaignData,
        ]);
    }

    /**
     * Send the campaign.
     */
    public function send(Request $request, Campaign $campaign): RedirectResponse
    {
        try {
            $this->sendCampaignService->execute($campaign);

            return redirect()
                ->route('campaigns.index')
                ->with('success', 'Campanha enviada com sucesso');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display campaign sends listing.
     */
    public function sends(Campaign $campaign): Response
    {
        $sends = $campaign->campaignSends()
            ->with('subscriber')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return Inertia::render('Campaigns/Sends', [
            'campaign' => $campaign,
            'sends' => $sends,
        ]);
    }
}
