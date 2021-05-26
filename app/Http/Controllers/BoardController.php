<?php

namespace App\Http\Controllers;

use App\Http\Resources\BoardCollection;
use App\Http\Resources\BoardResource;
use App\Models\Board;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            return response()->json(['message' => 'invalid field'], 422);
        }

        $board = auth()->user()->boards()->create($validator->validated());

        $board->members()->attach(auth()->id());

        return response()->json(['message' => 'create board success']);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Board $board
     * @return mixed
     */
    public function show(Board $board)
    {
        $board->load('members', 'boardLists', 'boardLists.cards');

        if (!$board->members->contains(auth()->user())) {
            return response()->json(['message' => 'unauthorized user'], 401);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'invalid field'], 422);
        }

        $board->load('members');

        if (!$board->members->contains(auth()->user())) {
            return response()->json(['message' => 'unauthorized user'], 401);
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
            return response()->json(['message' => 'unauthorized user'], 401);
        }

        $board->delete();

        return response()->json(['message' => 'delete board success']);
    }

    public function addMember(Request $request, Board $board)
    {
        $board->load('members');

        if (!$board->members->contains(auth()->user())) {
            return response()->json(['message' => 'unauthorized user'], 401);
        }

        $member = User::where('username', $request->username)->first();

        if (!$member) {
            return response()->json(['message' => 'user did not exist'], 422);
        }

        $board->members()->attach($member);

        return response()->json(['message' => 'add member success']);
    }

    public function removeMember(Board $board, User $user)
    {
        $board->load('members');

        if (!$board->members->contains(auth()->user())) {
            return response()->json(['message' => 'unauthorized user'], 401);
        }

        $board->members()->detach($user);

        return response()->json(['message' => 'remove member success']);
    }
}
