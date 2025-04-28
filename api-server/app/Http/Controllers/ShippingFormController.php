<?php

namespace App\Http\Controllers;

use App\Models\ShippingForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShippingFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $shippingForms = $user->shippingForms()->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $shippingForms
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'province_name' => 'required|string|in:Koshi Province,Madhesh Province,Bagmati Province,Gandaki Province,Lumbini Province,Karnali Province,Sudurpashchim Province',
            'city' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'building_details' => 'required|string|max:255',
            'address' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        
        $shippingForm = new ShippingForm();
        $shippingForm->user_id = $user->id;
        $shippingForm->user_name = $request->input('user_name');
        $shippingForm->email = $request->input('email');
        $shippingForm->phone = $request->input('phone');
        $shippingForm->province_name = $request->input('province_name');
        $shippingForm->city = $request->input('city');
        $shippingForm->area = $request->input('area');
        $shippingForm->building_details = $request->input('building_details');
        $shippingForm->address = $request->input('address');
        $shippingForm->save();

        return response()->json([
            'success' => true,
            'message' => 'Shipping address created successfully',
            'data' => $shippingForm
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $shippingForm = $user->shippingForms()->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $shippingForm
        ]);
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
        $validator = Validator::make($request->all(), [
            'user_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'province_name' => 'sometimes|string|in:Koshi Province,Madhesh Province,Bagmati Province,Gandaki Province,Lumbini Province,Karnali Province,Sudurpashchim Province',
            'city' => 'sometimes|string|max:255',
            'area' => 'sometimes|string|max:255',
            'building_details' => 'sometimes|string|max:255',
            'address' => 'sometimes|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $shippingForm = $user->shippingForms()->findOrFail($id);
        
        $fieldsToUpdate = [
            'user_name', 'email', 'phone', 'province_name', 
            'city', 'area', 'building_details', 'address'
        ];
        
        foreach ($fieldsToUpdate as $field) {
            if ($request->has($field)) {
                $shippingForm->$field = $request->input($field);
            }
        }
        
        $shippingForm->save();

        return response()->json([
            'success' => true,
            'message' => 'Shipping address updated successfully',
            'data' => $shippingForm
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $shippingForm = $user->shippingForms()->findOrFail($id);
        $shippingForm->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Shipping address deleted successfully'
        ]);
    }
    
    /**
     * Get the most recent shipping address for the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDefault()
    {
        $user = Auth::user();
        $defaultShippingForm = $user->shippingForms()->latest()->first();
        
        if (!$defaultShippingForm) {
            return response()->json([
                'success' => false,
                'message' => 'No shipping address found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $defaultShippingForm
        ]);
    }
}
