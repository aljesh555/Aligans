<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Salary;
use App\Models\User;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Admin or manager can see all salaries, others can only see their own
        if ($user->role === 'admin' || $user->role === 'manager') {
            $salaries = Salary::with('user')->get();
        } else {
            $salaries = Salary::where('user_id', $user->id)->get();
        }
        
        return response()->json(['data' => $salaries]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        
        // Only admin or manager can create salary records
        if ($user->role !== 'admin' && $user->role !== 'manager') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|string|max:20',
            'amount' => 'required|numeric|min:0',
            'paid' => 'required|boolean',
        ]);
        
        // Check for duplicate salary record for the same user and month
        $existingSalary = Salary::where('user_id', $validatedData['user_id'])
            ->where('month', $validatedData['month'])
            ->first();
            
        if ($existingSalary) {
            return response()->json([
                'message' => 'Salary record already exists for this user and month'
            ], 422);
        }
        
        $salary = Salary::create($validatedData);

        return response()->json([
            'message' => 'Salary record created successfully',
            'data' => $salary
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $user = $request->user();
        
        // Retrieve the salary record
        $salary = Salary::with('user')->findOrFail($id);
        
        // Check if user is authorized to view this salary
        if ($user->role !== 'admin' && $user->role !== 'manager' && $salary->user_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        
        return response()->json(['data' => $salary]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        
        // Only admin or manager can update salary records
        if ($user->role !== 'admin' && $user->role !== 'manager') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $salary = Salary::findOrFail($id);

        $validatedData = $request->validate([
            'month' => 'sometimes|required|string|max:20',
            'amount' => 'sometimes|required|numeric|min:0',
            'paid' => 'sometimes|required|boolean',
        ]);

        $salary->update($validatedData);

        return response()->json([
            'message' => 'Salary record updated successfully',
            'data' => $salary
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $user = $request->user();
        
        // Only admin or manager can delete salary records
        if ($user->role !== 'admin' && $user->role !== 'manager') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $salary = Salary::findOrFail($id);
        $salary->delete();

        return response()->json([
            'message' => 'Salary record deleted successfully'
        ]);
    }
    
    /**
     * Get salary records by user ID.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function getByUser($userId, Request $request)
    {
        $currentUser = $request->user();
        
        // Users can only view their own salary records unless they're admin/manager
        if ($currentUser->role !== 'admin' && $currentUser->role !== 'manager' && $currentUser->id != $userId) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $user = User::findOrFail($userId);
        $salaries = Salary::where('user_id', $userId)->get();
        
        return response()->json([
            'data' => [
                'user' => $user,
                'salaries' => $salaries
            ]
        ]);
    }
}
