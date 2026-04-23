<?php

namespace App\Http\Controllers;

use App\Models\scan;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Validator;

class ScanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list()
    {

        $slots = scan::get();

        return response()->json([
            "status" => 'success',
            "detail" =>  $slots,
        ]);
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

            'price' => 'Required',
            'scan_category_id' => 'Required',
            'name' =>  'required|unique:scans,name',

        ]);
        if ($validation->fails()) {
            return response()->json([
                "status" => 'error',
                "detail" =>  $validation->errors(),
            ]);
        } else {

            $input = $request->all();
            $scan = scan::create($input);

            return response()->json([
                "status" => 'success',
                "detail" =>  $scan,
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(scan $scan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(scan $scan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): HttpJsonResponse
    {
        $validation = Validator::make($request->all(), [

            'id' => 'Required',
            'price' => 'Required',
            'scan_category_id' => 'Required',
            'name' =>  'required',

        ]);
        if ($validation->fails()) {
            return response()->json([
                "status" => 'error',
                "detail" =>  $validation->errors(),
            ]);
        } else {

            $input = $request->all();
            scan::where('id', $request->id)->update($input);
            $scan = scan::where('id', $request->id)->first();

            return response()->json([
                "status" => 'success',
                "detail" =>  $scan,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validation = Validator::make($request->all(), [

            'id' => 'Required',


        ]);
        if ($validation->fails()) {
            return response()->json([
                "status" => 'error',
                "detail" =>  $validation->errors(),
            ]);
        } else {

            $id = scan::where('id', $request->id)->first();
            if ($id) {
                scan::where('id', $request->id)->delete();
                return response()->json([
                    "status" => 'success',
                    "detail" =>  'Scan Deleted Successfully!.',
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
