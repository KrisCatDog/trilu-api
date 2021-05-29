<?php

namespace App\Http\Controllers;

use App\Http\Resources\BoardCollection;
use App\Http\Resources\BoardResource;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return BoardCollection
     */
    public function index()
    {
        return new BoardCollection(auth()->user()->joinedBoards);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'invalid field'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $board = auth()->user()->boards()->create($validator->validated());

        $board->members()->attach(auth()->id());

        return response()->json(['message' => 'create board success']);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Board $board
     * @return BoardResource
     */
    public function show(Board $board)
    {
        $board->load('members', 'boardLists', 'boardLists.cards');

        if (!$board->members->contains(auth()->id())) {
            return response()->json(['message' => 'unauthorized user'], Response::HTTP_UNAUTHORIZED);
        }

        return new BoardResource($board);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Board $board
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Board $board)
    {
        $board->load('members');

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'invalid field'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!$board->members->contains(auth()->id())) {
            return response()->json(['message' => 'unauthorized user'], Response::HTTP_UNAUTHORIZED);
        }

        $board->update($validator->validated());

        return response()->json(['message' => 'update board success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Board $board
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Board $board)
    {
        if ($board->creator_id !== auth()->id()) {
            return response()->json(['message' => 'unauthorized user'], Response::HTTP_UNAUTHORIZED);
        }

        $board->delete();

        return response()->json(['message' => 'delete board success']);
    }
}
