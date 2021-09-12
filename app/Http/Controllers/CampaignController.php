<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Input;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $allowedSortOrder = ["asc", "desc"];

        $sortOrder = $request->query("sort") ?? "asc";
        $sortOrder = in_array($sortOrder, $allowedSortOrder) ? $sortOrder : "asc";

        $sortBy = $request->query("sortBy") ?? "created_at";

        $offset = $request->query("offset") ?? 0;

        $limit = $request->query("limit") ?? 10;
        $limit = max(min(intval($limit), 10), 1);

        $campaigns = Campaign::offset($offset)->limit($limit)->orderBy($sortBy, $sortOrder)->get();
        foreach ($campaigns as $campaign) {
            Campaign::addAuthorAndInputs($campaign);
        }

        return response()->json([
            "campaigns" => $campaigns
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(Request $request): JsonResponse
    {
        $inputsData = $request->input("inputs");

        if (!Input::validate($inputsData)) {
            throw new \Exception("Missing input type or value", 400);
        }

        $campaign = Campaign::create($request->all());

        foreach ($inputsData as $inputData) {
            $inputData["campaign_id"] = $campaign["id"];

            $existingInput = Input::where("campaign_id", "=", $campaign["id"])->where("type", "=", $inputData["type"])->first();

            if (!$existingInput) {
                Input::create($inputData);
            }
        }

        Campaign::addAuthorAndInputs($campaign);

        return response()->json($campaign, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param string $campaign_id
     * @return JsonResponse
     * @throws \Exception
     */
    public function show($campaign_id): JsonResponse
    {
        $campaign = Campaign::where("campaign_id", "=", $campaign_id)->first();
        if (!isset($campaign)) {
            throw new \Exception("Campaign '$campaign_id' not found", 404);
        }
        Campaign::addAuthorAndInputs($campaign);
        return response()->json($campaign);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $campaign_id
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(string $campaign_id): JsonResponse
    {
        $campaign = Campaign::where("campaign_id", "=", $campaign_id)->first();

        if (!isset($campaign)) {
            throw new \Exception("Campaign with this id doesn't exist", 404);
        }

        $suc = Campaign::destroy([$campaign["id"]]);

        return response()->json($suc, 204);
    }
}
