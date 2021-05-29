<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BoardMemberController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Board $board
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Board $board)
    {
        $board->load('members');

        if (!$board->members->contains(auth()->id())) {
            return response()->json(['message' => 'unauthorized user'], Response::HTTP_UNAUTHORIZED);
        }

        $member = User::where('username', $request->username)->first();

        if (!$member) {
            return response()->json(['message' => 'user did not exist'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $board->members()->attach($member);

        return response()->json(['message' => 'add member success']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Board $board
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Board $board, User $user)
    {
        $board->load('members');

        if (!$board->members->contains(auth()->id())) {
            return response()->json(['message' => 'unauthorized user'], Response::HTTP_UNAUTHORIZED);
        }

        $board->members()->detach($user);

        return response()->json(['message' => 'remove member success']);
    }
}
