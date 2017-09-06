<?php

namespace App\Http\Controllers;

use App\Settle;
use Illuminate\Http\Request;
use App\Http\Resources\Settle as SettleResource;

class SettleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SettleResource::collection(Settle::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // special
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Settle  $settle
     * @return \Illuminate\Http\Response
     */
    public function show(Settle $settle)
    {
        return new SettleResource($settle);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Settle  $settle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Settle $settle)
    {
        $request->validate([
          'payed' => 'required|boolean',
        ]);

        $settle->payed = $request->payed;

        $settle->save();

        return new SettleResource($settle);
    }
}
