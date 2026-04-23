<?php

namespace App\Http\Controllers;

use App\Models\bookSlot;
use App\Models\bookScan;
use App\Models\appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Nette\Utils\Json;
use App\Models\slotitem;
use App\Models\parentScan;
use Ramsey\Collection\Set;

use function PHPUnit\Framework\isNull;

class AppointmentController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $appointments  = appointment::with('appointmentSlot', 'appointmentScan')->get();
        return $this->sendResponse($appointments, 'Fetching Data SuccessFully!.');


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
        $input =  $request->all();

        $appointment = appointment::create($input);
        bookScan::create([
            "appointment_id" => $appointment->id,
            "scan_id" => $request->scan_id,

        ]);
        bookSlot::create([
            "appointment_id" => $appointment->id,
            "slot_id" => $request->slot_id,

        ]);
        $appointment->load('appointmentSlot', 'appointmentScan');
        return $this->sendResponse($appointment, 'Appointment Booked SuccessFully!.');


        //
    }

    /**
     * Display the specified resource.
     */
    public function find(Request $request)
    {


        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */


    public function slot(Request $request)
    {






        $query = appointment::where('appointment_date',  $request->date)->with('appointmentSlot', 'appointmentScan')->get();


        $SlotDetail = collect($query)->keyBy('appointmentSlot.slot_id');


        $slots = slotitem::get();
        $set = [];
        foreach ($slots as $slot) {





            if ($SlotDetail->has($slot->id)) {

                $set[] = [

                    'slot_id' => $slot->id,
                    'time' => $slot->time,
                    'price' => $slot->price,
                    'status' => 'booked',
                ];
            } else {

                $set[] = [

                    'slot_id' => $slot->id,
                    'time' => $slot->time,
                    'price' => $slot->price,
                    'status' => 'un booked',
                ];
            }
        }

        return response()->json([
            "status" => true,
            "detail" => $set,
        ]);




        //
    }


    public function update(Request $request)
    {
        $check = appointment::where('id', $request->id)->first();
        // $update = appointment::where('id', $request->id)->update($request->except(['slot_id', 'scan_id']));
        $update = appointment::where('id', $request->id)
            ->update($request->except(['slot_id', 'scan_id']));
        if ($request->scan_id) {

            $bookScan = bookScan::where('appointment_id', $request->id)->update(
                [
                    "scan_id" => $request->scan_id
                ]
            );
        }
        if ($request->slot_id) {

            $bookScan = bookSlot::where('appointment_id', $request->id)->update(
                [
                    "slot_id" => $request->slot_id
                ]
            );
        }
        $appointment = appointment::with('appointmentSlot', 'appointmentScan')->where('id', $request->id)->first();
        return $appointment;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        $appointment = appointment::where('id', $request->id)->first();
        if ($appointment) {
            appointment::where('id', $request->id)->delete();
            bookSlot::where('appointment_id', $request->id)->delete();
            bookScan::where('appointment_id', $request->id)->delete();
            return $this->sendResponse("Delete", "Appointment Deleted SuccessFully!.");
        } else {
            return $this->sendError("Invalid", "This ID not found.");
        }
        //
    }
}
