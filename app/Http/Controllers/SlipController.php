<?php

namespace App\Http\Controllers;

use App\Item;
use App\Slip;
use Illuminate\Http\Request;
use App\Http\Resources\Slip as SlipResource;
use App\Http\Resources\SlipItem as SlipItemResource;

class SlipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SlipResource::collection(Slip::where('payed', false)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $slip = new Slip;

        $slip->save();

        return new SlipResource(Slip::find($slip->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Slip  $slip
     * @return \Illuminate\Http\Response
     */
    public function show(Slip $slip)
    {
        return new SlipResource($slip);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Slip  $slip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slip $slip)
    {
        $request->validate([
          'price' => 'required|numeric|nullable',
          'payed' => 'required|boolean',
          'userId' => 'required|integer|nullable',
        ]);

        $slip->price = $request->price;
        $slip->payed = $request->payed;
        $slip->user_id = $request->userId;

        $slip->save();

        return new SlipResource($slip);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Slip  $slip
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slip $slip)
    {
        $slip->delete();
        return new SlipResource($slip);
    }

    public function items(Slip $slip)
    {
        return SlipItemResource::collection($slip->items()->get());
    }

    public function itemsAdd(Request $request, Slip $slip, Item $item)
    {
        $request->validate([
          'description' => 'string|nullable',
        ]);

        $slip->items()->attach($item->id, ['description' => $request->description]);

        return new SlipItemResource($slip->items()->where('id', $item->id)->first());
    }

    public function itemsRemove(Slip $slip, Item $item)
    {
        $itemBak = $slip->items()->where('id', $item->id)->first();
        $slip->items()->detach($item->id);

        return new SlipItemResource($itemBak);
    }
}
