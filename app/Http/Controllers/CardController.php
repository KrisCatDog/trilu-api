<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\BoardList;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Board $board
     * @param BoardList $boardList
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Board $board, BoardList $boardList)
    {
        $validator = Validator::make($request->all(), [
            'task' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'invalid field'], 422);
        }

        $board->load('members');

        if (!$board->members->contains(auth()->user())) {
            return response()->json(['message' => 'unauthorized user'], 401);
        }

        if ($boardList->cards()->count() > 0) {
            $order = $boardList->cards()->max('order') + 1;
        } else {
            $order = 1;
        }

        $boardList->cards()->create(array_merge($validator->validated(), ['order' => $order]));

        return response()->json(['message' => 'create card success']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Board $board
     * @param BoardList $boardList
     * @param \App\Models\Card $card
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Board $board, BoardList $boardList, Card $card)
    {
        $validator = Validator::make($request->all(), [
            'task' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'invalid field'], 422);
        }

        $board->load('members');

        if (!$board->members->contains(auth()->user())) {
            return response()->json(['message' => 'unauthorized user'], 401);
        }

        $card->update($validator->validated());

        return response()->json(['message' => 'update card success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Board $board
     * @param BoardList $boardList
     * @param \App\Models\Card $card
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Board $board, BoardList $boardList, Card $card)
    {
        $board->load('members');

        if (!$board->members->contains(auth()->user())) {
            return response()->json(['message' => 'unauthorized user'], 401);
        }

        $nextCards = $boardList->cards()->where('order', '>', $card->order)->get();

        foreach ($nextCards as $nextCard) {
            $nextCard->update(['order' => $nextCard->order - 1]);
        }

        $card->delete();

        return response()->json(['message' => 'delete card success']);
    }

    public function moveDown(Card $card)
    {
        $board = $card->boardList->board->load('members');
        $boardList = $card->boardList;

        if (!$board->members->contains(auth()->user())) {
            return response()->json(['message' => 'unauthorized user'], 401);
        }

        $nextCard = $boardList->cards()->where('order', $card->order + 1)->first();

        if ($nextCard) {
            $card->update(['order' => $card->order + 1]);

            $nextCard->update(['order' => $nextCard->order - 1]);
        }

        return response()->json(['message' => 'move success']);
    }

    public function moveUp(Card $card)
    {
        $board = $card->boardList->board->load('members');
        $boardList = $card->boardList;

        if (!$board->members->contains(auth()->user())) {
            return response()->json(['message' => 'unauthorized user'], 401);
        }

        $prevCard = $boardList->cards()->where('order', $card->order - 1)->first();

        if ($prevCard) {
            $card->update(['order' => $card->order - 1]);

            $prevCard->update(['order' => $prevCard->order + 1]);
        }

        return response()->json(['message' => 'move success']);
    }

    public function moveList(Card $card, BoardList $boardList)
    {
        $board = $card->boardList->board->load('members');

        if (!$board->members->contains(auth()->user())) {
            return response()->json(['message' => 'unauthorized user'], 401);
        }

        if ($boardList->board_id !== $board->id) {
            return response()->json(['message' => 'move list invalid'], 422);
        }

        $card->update(['list_id' => $boardList->id, 'order' => $boardList->cards()->max('order') + 1]);

        return response()->json(['message' => 'move success']);
    }
}
