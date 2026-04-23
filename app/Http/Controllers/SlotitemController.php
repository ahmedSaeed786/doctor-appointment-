<?php

namespace App\Http\Controllers;

use App\Models\slotitem;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Nette\Utils\Json;
use Validator;

class SlotitemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list()
    {

        $slotitem = slotitem::get();
        return response()->json([
            "status" => 'success',
            "detail" =>  $slotitem,
        ]);



        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'price' => 'required',
            'time' => 'required|unique:slotitems,time',

        ]);
        if ($validation->fails()) {
            return response()->json([
                "status" => 'error',
                "detail" =>  $validation->errors(),
            ]);
        } else {
            $slotitem = slotitem::create($request->all());

            return response()->json([
                "status" => 'success',
                "detail" =>  $slotitem,
            ]);
        }
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(slotitem $slotitem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(slotitem $slotitem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, slotitem $slotitem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'Required'
        ]);
        if ($validation->fails()) {
            return response()->json([
                "status" => 'error',
                "detail" =>  $validation->errors(),
            ]);
        } else {
            $id = slotitem::where('id', $request->id)->first();
            if ($id) {
                slotitem::where('id', $request->id)->delete();
                return response()->json([
                    "status" => 'success',
                    "detail" => 'Slot deleted Successfully!.',
                ]);
            } else {
                return response()->json([
                    "status" => 'error',
                    "detail" =>  "Invalid ID",
                ]);
            }
        }

        //
    }
}
