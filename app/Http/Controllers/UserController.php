<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function register()
    {
        if(session()->has('loggedInUser')){
            return redirect('/profile');
        } else {
        return view('auth.register');
        }
    }

    public function forgot()
    {
        return view('auth.forgot');
    }

    public function reset()
    {
        return view('auth.reset');
    }

    // Handle register user ajax request
    public function saveUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'required|min:8',
            'cpassword' => 'required|min:8|same:password',
        ], [
            'cpassword.same' => 'Password did not match!',
            'cpassword.required' => 'Confirm password is required!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()
            ]);
        } else {
            DB::beginTransaction();
            try {
                // Handle image upload
                $profileImagePath = null;
                if ($request->hasFile('image')) {
                    $profileImagePath = $request->file('image')->store('profile_images', 'public');
                }

                // Create the user with isAdmin set to 0
                $user = $this->createUser($request, $profileImagePath);
                $buyer = $this->createBuyer($request, $user->id);

                DB::commit();

                return response()->json([
                    'status' => 200,
                    'message' => 'User successfully registered!'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error creating user: '.$e->getMessage());
                return response()->json([
                    'status' => 500,
                    'message' => 'Internal Server Error'
                ]);
            }
        }
    }

    // Example method to create user record
    private function createUser($request, $profileImagePath)
    {
        return User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_image_path' => $profileImagePath, // Adjust as per your form
            'is_admin' => 0, // Ensure isAdmin is set to 0 for regular users
            'is_activated' => 1, // You can set activated status as per your logic
        ]);
    }

    // Example method to create related buyer record
    private function createBuyer($request, $userId)
    {
        return Buyer::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'contact' => $request->contact,
            'address' => $request->address,
            'barangay' => $request->barangay,
            'city' => $request->city,
            'landmark' => $request->landmark,
            'id_user' => $userId, // Ensure the column matches 'user_id'
        ]);
    }

    // Handle login user ajax request
    public function loginUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    $request->session()->put('loggedInUser', $user->id);
                    return response()->json([
                        'status' => 200,
                        'message' => 'Login Successful'
                    ]);
                } else {
                    return response()->json([
                        'status' => 401,
                        'message' => 'Invalid Password'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid Email'
                ]);
            }
        }
    }

    //profile page
    public function profile(){
        return view ('profile');
    }

    //logout method
    public function logout(){
        if (session()->has('loggedInUser')){
            session()->pull('loggedInUser');
            return redirect('/');
        }
}
}