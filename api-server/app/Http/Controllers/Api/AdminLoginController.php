<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminLogin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminLoginController extends Controller
{
    /**
     * Get all admin accounts (for administration)
     */
    public function index()
    {
        $admins = AdminLogin::all();
        return response()->json($admins);
    }

    /**
     * Add a new admin account
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:admin_logins',
            'password' => 'required|string|min:8',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $admin = new AdminLogin([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => $request->is_active ?? true
        ]);

        $admin->save();
        return response()->json($admin, 201);
    }

    /**
     * Get a specific admin account
     */
    public function show($id)
    {
        $admin = AdminLogin::findOrFail($id);
        return response()->json($admin);
    }

    /**
     * Update an admin account
     */
    public function update(Request $request, $id)
    {
        $admin = AdminLogin::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'email' => 'email|unique:admin_logins,email,' . $admin->id,
            'password' => 'string|min:8',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('email')) {
            $admin->email = $request->email;
        }

        if ($request->has('password')) {
            $admin->password = Hash::make($request->password);
        }

        if ($request->has('is_active')) {
            $admin->is_active = $request->is_active;
        }

        $admin->save();
        return response()->json($admin);
    }

    /**
     * Delete an admin account
     */
    public function destroy($id)
    {
        $admin = AdminLogin::findOrFail($id);
        $admin->delete();
        return response()->json(null, 204);
    }

    /**
     * Check if admin credentials are valid
     */
    public function checkCredentials(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $isValid = AdminLogin::checkCredentials($request->email, $request->password);

        if ($isValid) {
            // Find or create a corresponding user with admin rights
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                // Create a new admin user if one doesn't exist
                $user = User::create([
                    'name' => 'Admin User',
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'admin'
                ]);
            } else {
                // Update existing user to have admin role if not already
                if ($user->role !== 'admin') {
                    $user->role = 'admin';
                    $user->save();
                }
            }

            // Create token for authentication
            $token = $user->createToken('admin_token')->plainTextToken;

            return response()->json([
                'valid' => true,
                'user' => $user,
                'token' => $token
            ]);
        }

        return response()->json([
            'valid' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }
} 