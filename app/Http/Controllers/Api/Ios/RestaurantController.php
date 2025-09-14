<?php

namespace App\Http\Controllers\Api\Ios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Get restaurants list for iOS platform
     */
    public function index(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'platform' => 'ios',
            'locale' => app()->getLocale(),
            'message' => 'iOS restaurants endpoint working'
        ]);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
