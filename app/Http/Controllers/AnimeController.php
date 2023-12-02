<?php

namespace App\Http\Controllers;

use App\Helpers\Wrapper;
use App\Http\Requests\GetAllAnimeRequest;
use App\Models\Anime;
use Illuminate\Http\Request;
use Response;

class AnimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetAllAnimeRequest $request)
    {

        $query = [];
        $projection = [];

        $validated = $request->validated();

        if ($validated['search'] ?? null) {
            $query['$text'] = [
                '$search' => '',
            ];
            $projection['score'] = [
                '$meta' => 'textScore',
            ];
            $sort['score'] = [
                '$meta' => 'textScore',
            ];
        }

        if ($validated['genres'] ?? null) {
            $genres = explode(',', $validated['genres'] ?? '');
            $query['genres'] = [
                '$elemMatch' => [
                    '$in' => $genres,
                ],
            ];
        }

        if ($validated['types'] ?? null) {
            $types = explode(',', $validated['types'] ?? '');
            $query['type'] = [
                '$in' => $types,
            ];
        }

        $orm = Anime::whereRaw($query)->project($projection);

        // sort
        if ($validated['search'] ?? null) {
            $orm = $orm->orderBy('score', ['$meta' => 'textScore']);
        } else {
            $sort_by = $validated['sortBy'] ?? 'ranking';
            $sort_order = $validated['sortOrder'] ?? 'asc';
            $orm = $orm->orderBy($sort_by, $sort_order);
        }

        $skip = ($validated['page'] - 1) * $validated['size'];
        $limit = $validated['size'];

        $result = $orm
            ->skip($skip)
            ->limit($limit)
            ->get();

        $totalData = $orm->count();

        return Response::json(
            Wrapper::pagination($result, $totalData, $validated)
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Anime $anime)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Anime $anime)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Anime $anime)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anime $anime)
    {
        //
    }
}
