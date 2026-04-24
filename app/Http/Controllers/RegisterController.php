<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Mail;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    //

    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [

            'name' => 'Required',
            'role' => ['required', 'in:Admin,User'],
            'email' =>  'required|email|unique:users,email',
            'password' => 'Required',
        ]);
        if ($validation->fails()) {

            return response()->json([
                "status" => 'error',
                "detail" => $validation->errors(),
            ]);
        }


        $otp = random_int(100000, 999999);
        Session::put('otp', $otp);
        $user = User::create([
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'email' => $request->email,
            'role' => $request->role,
            'otp' => $otp,
        ]);
        $toke = $user->createToken('auth_token')->plainTextToken;


        return  $user;
        $userMail = User::find($user->id);
        $booking = [
            'title' => 'Mail from MarksMan.com',
            'body' => 'This is for testing email using smtp.',
            'userMail'     =>   $userMail

        ];
        try {
            Mail::send('mail', $booking, function ($mail) use ($userMail) {
                $mail->from('ahmedsaeedprojects@gmail.com');
                $mail->to($userMail->email);
                $mail->subject('Email Confirmation');
            });
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        session()->flush();
        return response()->json([
            "status" => 'success',
            "token" =>  $toke,
            "detail" =>  $user,
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validation = Validator::make($request->all(), [


            'otp' => 'Required',
            'email' =>  'required',
            'password' => 'Required',
        ]);
        if ($validation->fails()) {
            return response()->json([
                "status" => 'error',
                "detail" => $validation->errors(),
            ]);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if ($user->otp == $request->otp) {
                User::where('email', $request->email)->update([
                    "password" => bcrypt($request->password),
                ]);
                // $success['token'] = $user->createToken('auth_token')->plainTextToken;

                $success['detail'] = $user;
                User::where('email', $request->email)->update([
                    "otp" => null,
                ]);
                $users =  User::where('email', $request->email)->first();
                return response()->json([
                    "status" => 'success',
                    "detail" =>   'Password Updated Successfully!.',
                ]);
            } else {
                return response()->json([
                    "status" => 'success',
                    "detail" =>  'OTP not match.',
                ]);
            }
        } else {
            return response()->json([
                "status" => 'success',
                "detail" =>  'Email not found.',
            ]);
        }
    }
    public function forget(Request $request)
    {
        $validation = Validator::make($request->all(), [


            'email' =>  'required',

        ]);
        if ($validation->fails()) {
            return response()->json([
                "status" => 'error',
                "detail" => $validation->errors(),
            ]);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $otp = random_int(100000, 999999);
            User::where('email', $request->email)->update([
                'otp' => $otp,
            ]);
            Session::put('otp', $otp);
            $userMail = User::find($user->id);

            $booking = [
                'title' => 'Mail from MarksMan.com',
                'body' => 'This is for testing email using smtp.',
                'userMail'     =>   $userMail

            ];
            try {
                Mail::send('mail', $booking, function ($mail) use ($userMail) {
                    $mail->from('ahmedsaeedprojects@gmail.com');
                    $mail->to($userMail->email);
                    $mail->subject('Email Confirmation');
                });
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
            Session()->flush();
            return response()->json([
                "status" => 'success',
                "detail" => "Please check your email",
            ]);
        } else {
            return response()->json([
                "status" => 'error',
                "detail" => 'Email Not Found',
            ]);
        }
    }


    public function update(Request $request)
    {


        if (isset($request->password)) {
            $input = $request->all();
            $input['password'] = bcrypt($request->password);

            $user = User::where('id', $input['id'])->update($input);
            $user = User::where('id', $input['id'])->first();
            $success['detail'] = $user;
            return response()->json([
                "status" => 'success',
                "detail" => 'User Updated Successfully!.',
            ]);
        } else {
            $input = $request->all();

            $user = User::where('id', $input['id'])->update($input);
            $user = User::where('id', $input['id'])->first();
            $success['detail'] = $user;
            return response()->json([
                "status" => 'success',
                "detail" => 'User Updated Successfully!.'
            ]);
        }
    }

    public function  login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('auth_token')->plainTextToken;;
            $success['detail'] = $user;
            return response()->json([
                "status" => 'success',
                "detail" =>  $success,
            ]);
        } else {
            return response()->json([
                "status" => 'success',
                "detail" => 'unauthorized',
            ]);
        }
    }
    public function  verify(Request $request)
    {
        $validation = Validator::make($request->all(), [

            'otp' => 'Required',
            'email' =>  'required',

        ]);
        if ($validation->fails()) {
            return response()->json([
                "status" => 'error',
                "detail" => $validation->errors(),
            ]);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if ($user->otp == $request->otp) {



                return response()->json([
                    "status" => 'success',
                    "detail" => $user,
                ]);
            } else {
                return response()->json([
                    "status" => 'success',
                    "detail" => 'Worng OTP',
                ]);
            }
        } else {
            return response()->json([
                "status" => 'success',
                "detail" => 'Email not found',
            ]);
        }
    }
    public function  logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "status" => 'success',
            "detail" =>  'User logout Successfully!.',
        ]);
    }


    public function destroy(Request $request)
    {
        $validation = Validator::make($request->all(), [

            'id' => 'Required',


        ]);
        if ($validation->fails()) {
            return response()->json([
                "status" => 'error',
                "detail" => $validation->errors(),
            ]);
        }
        $userFind = User::where('id', $request->id)->first();
        if ($userFind) {

            if ($request->id !=  auth()->user()->id) {
                $user = User::where('id', $request->id)->delete();
                return response()->json([
                    "status" => 'success',
                    "detail" =>  'User Deleted Successfully!.',
                ]);
            } else {
                // $user = User::where('id', $request->id)->delete();
                return response()->json([
                    "status" => 'success',
                    "detail" => 'You do not have permission to delete this ID',
                ]);
            }
        }
        return response()->json([
            "status" => 'success',
            "detail" => 'Invalid User ID',
        ]);
    }
    public function  list(Request $request)
    {
        $user = user::orderBy('id', 'desc')->paginate(15);
        return response()->json([
            "status" => 'success',
            "detail" =>  $user,
        ]);
    }
    public function  detail(Request $request)
    {
        $validation = Validator::make($request->all(), [

            'id' => 'Required',


        ]);
        if ($validation->fails()) {
            return response()->json([
                "status" => 'error',
                "detail" => $validation->errors(),
            ]);
        }
        $user = user::where('id', $request->id)->first();
        return response()->json([
            "status" => 'success',
            "detail" =>   $user,
        ]);
    }
}
