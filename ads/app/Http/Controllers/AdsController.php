<?php

namespace App\Http\Controllers;

use App\Ads;
use Illuminate\Http\Request;
use Exception;

class AdsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
    }

    /**
     * Create ad
     *
     * @param \Illuminate\Http\Request $request
     * @param Ads $ads
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Ads $ads)
    {
        try {
            $ads->fill($request->input());
            $ads->user_id = json_decode($request->header('User'), true)['id'];
            $ads->save();
        } catch (Exception $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage(),
                'Exception' => get_class($e),
            ], 409);
        }

        $response = [
            'status' => 'created',
            'ad' => $ads
        ];

        return response($response, 201);
    }

    /**
     * Retrieve ad
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            $ad = Ads::findOrFail($id);
        } catch (Exception $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage(),
                'Exception' => get_class($e),
            ], 400);
        }

        return response($ad, 200);
    }

    /**
     * Update ad
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $ad = Ads::findOrfail($id);
            $ad->fill($request->input());
            $ad->save();
        } catch (Exception $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage(),
                'Exception' => get_class($e),
            ], 400);
        }

        return response([
            'status' => 'updated',
        ], 200);
    }

    /**
     * Delete ad
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $ad = Ads::findOrfail($id);
            $ad->delete();
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
