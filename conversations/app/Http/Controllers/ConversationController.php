<?php

namespace App\Http\Controllers;

use App\Conversation;
use Exception;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $conversation = Conversation::all();
        } catch (Exception $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage(),
                'Exception' => get_class($e),
            ], 409);
        }

        return response($conversation, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.

     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $conversation = new Conversation();
            $conversation->user_id = json_decode($request->header('User'), true)['id'];
            $conversation->save();
        } catch (Exception $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage(),
                'Exception' => get_class($e),
            ], 409);
        }

        $response = [
            'status' => 'created',
            'conversation' => $conversation
        ];

        return response($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($conversationId)
    {
        try {
            $conversation = Conversation::find($conversationId);

            if($conversation === null) {
                throw new Exception(sprintf("Conversation with id %s doesn't exist", $conversationId));
            }

            $conversation = $conversation->messages()->get();
        } catch (Exception $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage(),
                'Exception' => get_class($e),
            ], 400);
        }

        return response($conversation, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Conversations  $conversations
     * @return \Illuminate\Http\Response
     */
    public function edit(Conversation $conversations)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Conversations  $conversations
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Conversation $conversations)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $conversations = Conversation::findOrfail($id);
            $conversations->delete();
        } catch (Exception $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage(),
                'Exception' => get_class($e),
            ], 400);
        }

        return response([
            'status' => 'deleted',
        ], 200);
    }
}
