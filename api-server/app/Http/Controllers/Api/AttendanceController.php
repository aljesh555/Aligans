<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Admin or manager can see all attendances, others can only see their own
        if ($user->role === 'admin' || $user->role === 'manager') {
            $attendances = Attendance::with('user')->get();
        } else {
            $attendances = Attendance::where('user_id', $user->id)->get();
        }
        
        return response()->json(['data' => $attendances]);
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
        
        // Only admin or manager can create attendance for other users
        $canManageOthers = $user->role === 'admin' || $user->role === 'manager';
        
        $validatedData = $request->validate([
            'user_id' => [
                $canManageOthers ? 'required' : 'prohibited',
                'exists:users,id'
            ],
            'date' => 'required|date',
            'status' => [
                'required',
                Rule::in([
                    Attendance::STATUS_PRESENT,
                    Attendance::STATUS_ABSENT,
                    Attendance::STATUS_LATE,
                    Attendance::STATUS_HALF_DAY
                ])
            ],
        ]);
        
        // If not an admin/manager, use the current user's ID
        if (!$canManageOthers) {
            $validatedData['user_id'] = $user->id;
        }
        
        // Check for duplicate attendance on the same date
        $existingAttendance = Attendance::where('user_id', $validatedData['user_id'])
            ->where('date', $validatedData['date'])
            ->first();
            
        if ($existingAttendance) {
            return response()->json([
                'message' => 'Attendance record already exists for this date'
            ], 422);
        }
        
        $attendance = Attendance::create($validatedData);

        return response()->json([
            'message' => 'Attendance created successfully',
            'data' => $attendance
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
        
        // Retrieve the attendance record
        $attendance = Attendance::with('user')->findOrFail($id);
        
        // Check if user is authorized to view this attendance
        if ($user->role !== 'admin' && $user->role !== 'manager' && $attendance->user_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        
        return response()->json(['data' => $attendance]);
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
        
        // Only admin or manager can update attendance
        if ($user->role !== 'admin' && $user->role !== 'manager') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $attendance = Attendance::findOrFail($id);

        $validatedData = $request->validate([
            'date' => 'sometimes|required|date',
            'status' => [
                'sometimes',
                'required',
                Rule::in([
                    Attendance::STATUS_PRESENT,
                    Attendance::STATUS_ABSENT,
                    Attendance::STATUS_LATE,
                    Attendance::STATUS_HALF_DAY
                ])
            ],
        ]);

        $attendance->update($validatedData);

        return response()->json([
            'message' => 'Attendance updated successfully',
            'data' => $attendance
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
        
        // Only admin or manager can delete attendance
        if ($user->role !== 'admin' && $user->role !== 'manager') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return response()->json([
            'message' => 'Attendance deleted successfully'
        ]);
    }
    
    /**
     * Get attendance records by user ID.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function getByUser($userId, Request $request)
    {
        $currentUser = $request->user();
        
        // Users can only view their own attendance unless they're admin/manager
        if ($currentUser->role !== 'admin' && $currentUser->role !== 'manager' && $currentUser->id != $userId) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $user = User::findOrFail($userId);
        $attendances = Attendance::where('user_id', $userId)->get();
        
        return response()->json([
            'data' => [
                'user' => $user,
                'attendances' => $attendances
            ]
        ]);
    }
}
