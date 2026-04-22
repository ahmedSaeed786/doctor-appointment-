<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Mail;

class RegisterController extends BaseController
{
    //

    public function register(Request $request): JsonResponse
    {
        $validation = Validator::make($request->all(), [

            'name' => 'Required',
            'email' =>  'required|email|unique:users,email',
            'password' => 'Required',
        ]);
        if ($validation->fails()) {
            return $this->sendError('validator Error', $validation->errors());
        }
        $input = $request->all();
        $input['password'] = bcrypt($request->password);
        $input['otp'] = random_int(100000, 999999);

        $user = User::create($input);
        $success['token'] = $user->createToken('auth_token')->plainTextToken;

        $success['detail'] = $user;
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


        return $this->sendResponse($success, 'User Registered Successfully!.');
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $validation = Validator::make($request->all(), [

            'otp' => 'Required',
            'email' =>  'required',
            'password' => 'Required',
        ]);
        if ($validation->fails()) {
            return $this->sendError('validator Error', $validation->errors());
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
                return $this->sendResponse($users, 'Password Updated Successfully!.');
            } else {
                return $this->sendError('unauthorized', 'OTP not match.');
            }
        } else {
            return $this->sendError('unauthorized', 'Email not found.');
        }
    }
    public function forget(Request $request): JsonResponse
    {
        $validation = Validator::make($request->all(), [


            'email' =>  'required',

        ]);
        if ($validation->fails()) {
            return $this->sendError('validator Error', $validation->errors());
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {

            User::where('email', $request->email)->update([
                'otp' => random_int(100000, 999999),
            ]);
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
            return $this->sendResponse($user, 'Check your email.');
        } else {
            return $this->sendError('unauthorized', 'Email not found.');
        }
    }


    public function update(Request $request): JsonResponse
    {


        if (isset($request->password)) {
            $input = $request->all();
            $input['password'] = bcrypt($request->password);

            $user = User::where('id', $input['id'])->update($input);
            $user = User::where('id', $input['id'])->first();
            $success['detail'] = $user;
            return $this->sendResponse($success, 'User Updated Successfully!.');
        } else {
            $input = $request->all();

            $user = User::where('id', $input['id'])->update($input);
            $user = User::where('id', $input['id'])->first();
            $success['detail'] = $user;
            return $this->sendResponse($success, 'User Updated Successfully!.');
        }
    }

    public function  login(Request $request): JsonResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('auth_token')->plainTextToken;;
            $success['detail'] = $user;
            return $this->sendResponse($success, 'User login Successfully!.');
        } else {
            return $this->sendError('unauthorized', ['error' => 'unauthorized']);
            // return $this->sendResponse('unauthorized', ['error' => 'unauthorized']);
        }
    }
    public function  verify(Request $request): JsonResponse
    {
        $validation = Validator::make($request->all(), [

            'otp' => 'Required',
            'email' =>  'required',

        ]);
        if ($validation->fails()) {
            return $this->sendError('validator Error', $validation->errors());
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if ($user->otp == $request->otp) {


                $success['detail'] = $user;
                return $this->sendResponse($success, 'Verified OTP');
            } else {
                return $this->sendError('Worng', ['error' => 'Worng OTP']);
            }
        } else {
            return $this->sendError('Worng', ['error' => 'Email not found']);
            // return $this->sendResponse('Worng', ['error' => 'Email not found']);
        }
    }
    public function  logout(Request $request): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return $this->sendResponse('Logout', 'User logout Successfully!.');
    }


    public function destroy(Request $request)
    {
        $validation = Validator::make($request->all(), [

            'id' => 'Required',


        ]);
        if ($validation->fails()) {
            return $this->sendError('validator Error', $validation->errors());
        }
        $userFind = User::where('id', $request->id)->first();
        if ($userFind) {

            if ($request->id !=  auth()->user()->id) {
                $user = User::where('id', $request->id)->delete();
                return $this->sendResponse('Delete', 'User Deleted Successfully!.');
            } else {
                // $user = User::where('id', $request->id)->delete();
                return $this->sendError('Delete', 'You do not have permission to delete this ID');
            }
        }
        return $this->sendError('Delete Error', 'Invalid User ID');
    }
    public function  list(Request $request): JsonResponse
    {
        $user = user::orderBy('id', 'desc')->paginate(15);

        $success['detail'] = $user;
        return $this->sendResponse($success, 'Fetching Data Successfully!.');
    }
    public function  detail(Request $request): JsonResponse
    {
        $validation = Validator::make($request->all(), [

            'id' => 'Required',


        ]);
        if ($validation->fails()) {
            return $this->sendError('validator Error', $validation->errors());
        }
        $user = user::where('id', $request->id)->first();

        $success['detail'] = $user;
        return $this->sendResponse($success, 'Fetching Data Successfully!.');
    }
}
