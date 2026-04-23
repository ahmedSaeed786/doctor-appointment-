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
use Validator;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isNull;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user = appointment::where('id', Auth()->user()->id)->first();

        if (Auth()->user()->role == 'Admin') {

            $appointments  = appointment::with('appointmentSlot', 'appointmentScan')->get();
            return response()->json([
                "status" => 'success',
                "detail" => $appointments,
            ]);

            return $this->sendResponse($appointments, 'Fetching Data SuccessFully!.');
        } else {

            $appointments  = appointment::where('user_id',  Auth()->user()->id)->with('appointmentSlot', 'appointmentScan')->get();
            return response()->json([

                "status" => 'success',
                "detail" => $appointments,
            ]);

            return $this->sendResponse($appointments, 'Fetching Data SuccessFully!.');
        }


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
            "first_name" => 'Required',
            "last_name" => 'Required',
            "email" => 'Required',
            "phone" => 'Required',
            "address" => 'Required',
            "second_address" => 'Required',
            "emergency_contact" => 'Required',
            "dob" => 'Required',
            "gender" => 'Required',
            "disability" => 'Required',

            "clinical_indication" => 'Required',
            "capability" => 'Required',
            "representative" => 'Required',
            "appointment_date" => 'Required',
            "Representative_first_name" => 'Required',
            "Representative_last_name" => 'Required',
            "total" => 'Required',
            "user_id" => 'Required',

        ]);

        if ($validation->fails()) {
            return response()->json([
                "status" => 'error',
                "error" => $validation->errors(),
            ]);
        }
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
        return response()->json([
            "status" => 'success',
            "detail" => $appointment,
        ]);


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
            "status" => 'success',
            "detail" => $set,
        ]);




        //
    }


    public function update(Request $request)
    {
        $check = appointment::where('id', $request->id)->first();

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
        return response()->json([
            "status" => 'success',
            "detail" => $appointment,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $appointment = appointment::where('id', $request->id)->first();
        if ($appointment) {
            appointment::where('id', $request->id)->delete();
            bookSlot::where('appointment_id', $request->id)->delete();
            bookScan::where('appointment_id', $request->id)->delete();
            return response()->json([
                "status" => 'success',
                "detail" => 'Appointment Deleted SuccessFully!.',
            ]);

            return $this->sendResponse("Delete", "Appointment Deleted SuccessFully!.");
        } else {
            return response()->json([
                "status" => 'error',
                "detail" => "This ID not found.",
            ]);

            return $this->sendError("Invalid", "This ID not found.");
        }
        //
    }
}
