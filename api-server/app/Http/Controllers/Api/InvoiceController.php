<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Generate and download an invoice for an order.
     *
     * @param  int  $orderId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function download($orderId, Request $request)
    {
        try {
            // Authorize the request
            $user = $request->user();
            $order = Order::with(['orderItems.product', 'paymentDetails'])
                ->findOrFail($orderId);
                
            // Check if the user is authorized to download this invoice
            if ($order->user_id !== $user->id && $user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            
            // Create the PDF using DomPDF
            $pdf = App::make('dompdf.wrapper');
            
            // Ensure order_number is set
            if (empty($order->order_number)) {
                $order->order_number = "ORD-" . $order->id . "-" . time();
                $order->save();
            }
            
            // Generate PDF view
            $pdf->loadView('invoices.order', [
                'order' => $order,
                'user' => $user,
                'company' => [
                    'name' => 'Aligans Sports Store',
                    'address' => '123 Main Street, Kathmandu',
                    'phone' => '+977 1-4123456',
                    'email' => 'info@aligans.com',
                    'website' => 'www.aligans.com',
                    'logo' => null,
                ]
            ]);
            
            // Download the PDF
            return $pdf->download("invoice-{$order->order_number}.pdf");
        } catch (\Exception $e) {
            \Log::error('Error generating invoice PDF: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'user_id' => $request->user()->id ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate invoice. ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Generate and view an invoice for an order.
     *
     * @param  int  $orderId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function view($orderId, Request $request)
    {
        try {
            // Authorize the request
            $user = $request->user();
            $order = Order::with(['orderItems.product', 'paymentDetails'])
                ->findOrFail($orderId);
                
            // Check if the user is authorized to view this invoice
            if ($order->user_id !== $user->id && $user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            
            // Create the PDF using DomPDF
            $pdf = App::make('dompdf.wrapper');
            
            // Ensure order_number is set
            if (empty($order->order_number)) {
                $order->order_number = "ORD-" . $order->id . "-" . time();
                $order->save();
            }
            
            // Generate PDF view
            $pdf->loadView('invoices.order', [
                'order' => $order,
                'user' => $user,
                'company' => [
                    'name' => 'Aligans Sports Store',
                    'address' => '123 Main Street, Kathmandu',
                    'phone' => '+977 1-4123456',
                    'email' => 'info@aligans.com',
                    'website' => 'www.aligans.com',
                    'logo' => null,
                ]
            ]);
            
            // View the PDF in the browser
            return $pdf->stream("invoice-{$order->order_number}.pdf");
        } catch (\Exception $e) {
            \Log::error('Error generating invoice PDF for view: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'user_id' => $request->user()->id ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate invoice. ' . $e->getMessage()
            ], 500);
        }
    }
}
