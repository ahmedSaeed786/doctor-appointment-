<?php

namespace App\Http\Controllers;

use App\Models\parentScan;
use Illuminate\Http\Request;
use Validator;

class ParentScanController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function find(Request $request)
    {
        if (isset($request->scan_category_id)) {

            $category = parentScan::where('id', $request->scan_category_id)->with('scan')->first();
            return response()->json([
                "status" => "success",
                "detail" => $category
            ]);
        }
        $category = parentScan::with('scan')->get();
        return response()->json([
            "status" => "success",
            "detail" => $category
        ]);
    }
    public function list()
    {


        $category = parentScan::with('scan')->get();
        return response()->json([
            "status" => "success",
            "detail" => $category
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
            "name" => 'required|unique:parent_scans,name'
        ]);
        if ($validation->fails()) {
            return response()->json([

                'status' => 'error',
                'detail' => $validation->errors(),
            ]);
        }

        $parent = parentScan::create($request->all());
        return response()->json([
            "status" => 'success',
            "detail" =>  $parent,
        ]);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(parentScan $parentScan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(parentScan $parentScan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        $parent = parentScan::where('id', $request->id)->first();
        if ($parent) {
            parentScan::where('id', $request->id)->update([
                'name' => $request->name
            ]);
            $detail = parentScan::where('id', $request->id)->first();
            return response()->json([
                "status" => 'success',
                "detail" =>  $detail,
            ]);
        } else {
            return response()->json([
                "status" => 'error',
                "detail" =>  "Invalid ID",
            ]);
        }
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $parent = parentScan::where('id', $request->id)->first();
        if ($parent) {
            parentScan::where('id', $request->id)->delete();
            // $detail = parentScan::where('id', $request->id)->first();
            return response()->json([
                "status" => 'success',
                "detail" =>  'Delete Successfully!.',
            ]);
        } else {
            return response()->json([
                "status" => 'error',
                "detail" =>  "Invalid ID",
            ]);
        }
    }
}
