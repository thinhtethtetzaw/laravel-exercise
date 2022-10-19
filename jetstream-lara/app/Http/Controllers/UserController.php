<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function totalUsers()
    {
        $users = User::all();
        return response()->json(["totalUsers" => count($users), "users" => $users]);
    }

    public function searchUsers($email)
    {
        $user = User::where("email", "like", "%" . $email . "%")->get();

        if (count($user)) {
            return response()->json(["search count" => count($user), "data" => $user]);
        } else {
            return response()->json(["search count" => count($user), 'result' => 'Your searching data does not match!!']);
        }
    }

    public function deleteUser($email)
    {
        $user = User::where("email", $email)->first();
        if ($user) {
            $user->delete();
            return ["deleted" => $user];
        } else {
            return [$email => "This email doesn't exist"];
        }
    }

    // User Register
    public function register(Request $request)
    {
        $validator  =   Validator::make($request->all(), [
            "name"  =>  "required",
            "email"  =>  "required|email|unique:users",
            "phone"  =>  "required",
            "password"  =>  "required",
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
        }

        $inputs = $request->all();
        $inputs["password"] = Hash::make($request->password);
        $inputs["g10_eng_unwh"] = 1; //free to all
        $inputs["g7_eng_unwh"] = 0;
        $user   =   User::create($inputs);

        if (!is_null($user)) {
            $user       =     User::where('email', $request->email)->first();
            $token      =       $user->createToken($request->email)->plainTextToken;
            return response()->json(["status" => "success", "login" => true, "token" => $token, "message" => "Success! registration completed", "data" => $user,]);
        } else {
            return response()->json(["status" => "failed", "message" => "Registration failed!"]);
        }
    }

    // User login
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "email" =>  "required|email",
            "password" =>  "required",
        ]);

        if ($validator->fails()) {
            return response()->json(["validation_errors" => $validator->errors()]);
        }

        $user           =       User::where("email", $request->email)->first();

        if (is_null($user)) {
            return response()->json(["status" => "failed", "message" => "Failed! email not found"]);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            Auth::logoutOtherDevices($request->password);
            $user       =       $user = User::where('email', $request->email)->first();
            $user->tokens()->delete();
            $token      =       $user->createToken($request->email)->plainTextToken;

            return response()->json(["status" => "success", "login" => true, "token" => $token, "data" => $user]);
        } else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! invalid password"]);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => "success",
            'message' => 'Logout successful',
        ]);
    }

    // User Detail
    public function currentUser()
    {
        $user       =       Auth::user();
        if (!is_null($user)) {
            return response()->json(["status" => "success", "currentUser" => $user]);
        } else {
            return response()->json(["status" => "failed", "message" => "Whoops! no user found"]);
        }
    }


    public function getMetta()
    {
        $user = User::where("metta", 1)->get();

        if (count($user)) {
            return response()->json(["Metta count" => count($user), "data" => $user]);
        } else {
            return ['result' => 'No metta yet'];
        }
    }


    public function updateMetta(Request $request)
    {
        $user = User::where("email", $request->email)->first();
        if ($user) {
            $user->metta = $request->metta;
            $result = $user->save();
            if ($result) {
                return $user;
            } else {
                return ["error"];
            }
        } else {
            return ["User doesn't exist"];
        }
    }

    //courses

    public function get_g10_eng_unwh()
    {
        $users = User::where("g10_eng_unwh", 1)->get();

        if (count($users)) {
            return response()->json(["count" => count($users), "g10_eng_unwhUsers" => $users]);
        } else {
            return ["count" => 0, 'result' => 'No user bought g10_eng_unwh'];
        }
    }

    public function update_g10_eng_unwh(Request $request)
    {
        $user = User::where("email", $request->email)->first();
        if ($user) {
            $user->g10_eng_unwh = $request->g10_eng_unwh;
            $result = $user->save();
            if ($result) {
                return $user;
            } else {
                return ["error"];
            }
        } else {
            return ["User doesn't exist"];
        }
    }

    public function get_g10_eng_unwhFromMetta()
    {
        $users = User::where("metta", 1)->where("g10_eng_unwh", 1)->get();
        if (count($users)) {
            return response()->json(["count of metta && g10_eng_unwh" => count($users), "data" => $users]);
        } else {
            return ["No Metta && g10_eng_unwh"];
        }
    }

    public function update_g10_eng_unwhFromMetta(Request $request)
    {
        $users = User::where("metta", 1)->get();
        foreach ($users as $user) {
            $user->g10_eng_unwh = $request->g10_eng_unwh;
            $result = $user->save();
        }
        if ($result) {
            return $users;
        } else {
            return ["error"];
        }
    }

    //g10_eng_unwh


    public function get_g7_eng_unwh()
    {
        $users = User::where("g7_eng_unwh", 1)->get();

        if (count($users)) {
            return response()->json(["count" => count($users), "g7_eng_unwh_users" => $users]);
        } else {
            return ["count" => 0, 'result' => 'No user bought g7_eng_unwh'];
        }
    }

    public function update_g7_eng_unwh(Request $request)
    {
        $user = User::where("email", $request->email)->first();
        if ($user) {
            $user->g7_eng_unwh = $request->g7_eng_unwh;
            $result = $user->save();
            if ($result) {
                return $user;
            } else {
                return ["error"];
            }
        } else {
            return ["User doesn't exist"];
        }
    }


    public function update_g7_eng_unwh_to_all(Request $request)
    {
        $result = User::where('g7_eng_unwh', $request->g7_eng_unwh_from)
            ->update(['g7_eng_unwh' => $request->g7_eng_unwh_to]);
        if ($result) {
            return $result;
        } else {
            return ["error"];
        }
    }

    public function get_g7_eng_unwhFromMetta()
    {
        $users = User::where("metta", 1)->where("g7_eng_unwh", 1)->get();
        if (count($users)) {
            return response()->json(["count of metta && g7_eng_unwh" => count($users), "data" => $users]);
        } else {
            return ["No Metta && g7_eng_unwh"];
        }
    }

    public function update_g7_eng_unwhFromMetta(Request $request)
    {
        $result = User::where("metta", 1)->update(['g7_eng_unwh' => $request->g7_eng_unwh]);

        if ($result) {
            return $result;
        } else {
            return ["error"];
        }
    }
}
