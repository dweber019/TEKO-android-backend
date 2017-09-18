<?php

namespace App\Http\Controllers;

use App\Settle;
use App\Slip;
use App\User;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;
use App\Http\Resources\Settle as SettleResource;
use Illuminate\Support\Facades\DB;

class SettleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SettleResource::collection(Settle::where('payed', false)->with(['owningUser', 'leaningUser'])->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $settleList = [];

        // Add all Slips
        $payedAndUnsettledSlips = Slip::where('payed', 1)->where('settled', 0)->get()->toArray();
        $users = User::get()->toArray();

        foreach ($payedAndUnsettledSlips as &$slip) {
            foreach ($users as &$user) {
                if ($slip['user_id'] != $user['id']) {
                    $settleList[] = [
                      'userLent' => $slip['user_id'],
                      'userOwns' => $user['id'],
                      'amount' => $slip['price'] / count($users),
                    ];
                }
            }
        }

        if (sizeof($payedAndUnsettledSlips) == 0) {
            return SettleResource::collection(Settle::with(['owningUser', 'leaningUser'])->get());
        }

        // Add all open Settles
        $openSettles = Settle::where('payed', 0)->get()->toArray();

        foreach ($openSettles as &$settle) {
            $settleList[] = [
              'userLent' => $settle['user_lent'],
              'userOwns' => $settle['user_owns'],
              'amount' => $settle['amount'],
            ];
        }

        // Reduce duplicates
        $reducedSettleList = [];

        foreach ($settleList as &$settle) {
            // check if exists
            $found = collect($reducedSettleList)->contains(function ($item) use ($settle) {
                return $item['userLent'] == $settle['userLent'] && $item['userOwns'] == $settle['userOwns'];
            });
            // search others
            if (!$found) {
                $amount = 0;
                foreach ($settleList as &$item) {
                    if ($item['userLent'] == $settle['userLent'] && $item['userOwns'] == $settle['userOwns']) {
                        $amount += $item['amount'];
                    }
                }

                // add
                if ($amount > 0) {
                    $reducedSettleList[] = [
                      'userLent' => $settle['userLent'],
                      'userOwns' => $settle['userOwns'],
                      'amount' => $amount,
                    ];
                }
            }
        }

        // Blance amount between users
        $userPairs = [];
        $blancedSettleList = [];

        foreach ($reducedSettleList as &$settle) {
            // check if exists
            $found = collect($userPairs)->contains(function ($item) use ($settle) {
                return $item['userLent'] == $settle['userLent'] && $item['userOwns'] == $settle['userOwns']
                  || $item['userLent'] == $settle['userOwns'] && $item['userOwns'] == $settle['userLent'];
            });
            // search others
            if (!$found) {
                $twoSettles = collect($reducedSettleList)->filter(function ($item) use ($settle) {
                    return $item['userLent'] == $settle['userLent'] && $item['userOwns'] == $settle['userOwns']
                      || $item['userLent'] == $settle['userOwns'] && $item['userOwns'] == $settle['userLent'];
                })->toArray();

                if (count($twoSettles) == 1) {
                    $blancedSettleList[] = [
                      'userLent' => array_values($twoSettles)[0]['userLent'],
                      'userOwns' => array_values($twoSettles)[0]['userOwns'],
                      'amount' => array_values($twoSettles)[0]['amount'],
                    ];
                } else if (array_values($twoSettles)[0]['amount'] > array_values($twoSettles)[1]['amount']) {
                    $blancedSettleList[] = [
                      'userLent' => array_values($twoSettles)[0]['userLent'],
                      'userOwns' => array_values($twoSettles)[0]['userOwns'],
                      'amount' => array_values($twoSettles)[0]['amount'] - array_values($twoSettles)[1]['amount'],
                    ];
                } else if (array_values($twoSettles)[0]['amount'] < array_values($twoSettles)[1]['amount']) {
                    $blancedSettleList[] = [
                      'userLent' => array_values($twoSettles)[1]['userLent'],
                      'userOwns' => array_values($twoSettles)[1]['userOwns'],
                      'amount' => array_values($twoSettles)[1]['amount'] - array_values($twoSettles)[0]['amount'],
                    ];
                }
            }
            $userPairs[] = [
              'userLent' => $settle['userLent'],
              'userOwns' => $settle['userOwns'],
            ];
        }

        DB::transaction(function () use ($blancedSettleList) {
        // update all settles to be payed
            Settle::where('payed', 0)->update(['payed' => 1]);
            Slip::where('payed', 1)->where('settled', 0)->update(['settled' => 1]);

            // add new settles
            foreach ($blancedSettleList as &$settle) {
                $model = new Settle([
                  'amount' => $settle['amount'],
                  'user_owns' => $settle['userOwns'],
                  'user_lent' => $settle['userLent'],
                ]);
                $model->save();
            }
        });

        return SettleResource::collection(Settle::with(['owningUser', 'leaningUser'])->get());
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
