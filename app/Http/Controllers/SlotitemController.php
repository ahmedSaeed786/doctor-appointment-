<?php

namespace App\Http\Controllers;

use App\Models\slotitem;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Nette\Utils\Json;
use Validator;

class SlotitemController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function list(): JsonResponse
    {

        $slotitem = slotitem::get();
        $success['detail'] = $slotitem;
        return $this->sendResponse($success, 'Fetching Data Successfully!.');


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
    public function store(Request $request): JsonResponse
    {
        $validation = Validator::make($request->all(), [
            'price' => 'required',
            'time' => 'required|unique:slotitems,time',

        ]);
        if ($validation->fails()) {
            return $this->sendError('validator Error', $validation->errors());
        } else {
            $slotitem = slotitem::create($request->all());

            return $this->sendResponse($slotitem, 'Slot Addes Successfully!.');
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
    public function destroy(Request $request): JsonResponse
    {
        $validation = Validator::make($request->all(), [
            'id' => 'Required'
        ]);
        if ($validation->fails()) {
            return $this->sendError('validator Error', $validation->errors());
        } else {
            $id = slotitem::where('id', $request->id)->first();
            if ($id) {
                slotitem::where('id', $request->id)->delete();
                return $this->sendResponse("Delete", 'Slot deleted Successfully!.');
            } else {
                return $this->sendError('Invalid', "Invalid ID");
            }
        }

        //
    }
}
