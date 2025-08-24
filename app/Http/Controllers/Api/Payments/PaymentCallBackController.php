<?php

namespace App\Http\Controllers\Api\Payments;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\ReceiptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentCallBackController extends Controller
{
    
    
    
     public function success()
    {

        return view('payments.success');
        
    }

    public function cancel()
    {

        return view('payments.cancel');
    }
    
    
    // public function success(Request $request)
    // {
        // Log::info('DPO Payment Success Callback Received:', $request->all());
        // Log::info('DPO Payment Success Callback Headers:', $request->headers->all());
        
        // $transToken = $request->input('TransToken');
        // $transRef = $request->input('TransRef');
        // $companyRef = $request->input('CompanyRef');
        
        // Log::info('DPO Success Callback Parameters:', [
        //     'transToken' => $transToken,
        //     'transRef' => $transRef,
        //     'companyRef' => $companyRef,
        // ]);
        
        // // Find booking by company reference
        // $booking = Booking::where('reference', $companyRef)->first();
        
        // Log::info('Booking lookup result (Success):', [
        //     'companyRef' => $companyRef,
        //     'bookingFound' => $booking ? 'Yes' : 'No',
        //     'bookingId' => $booking ? $booking->id : null,
        //     'currentIsPaid' => $booking ? $booking->is_paid : null,
        // ]);
        
        // if ($booking) {
        //     Log::info('Processing success callback for booking:', [
        //         'booking_id' => $booking->id,
        //         'amount' => $booking->amount,
        //     ]);
            
        //     $booking->update([
        //         'is_paid' => true,
        //         'paid_at' => now(),
        //         'amount_paid' => $booking->amount,
        //     ]);
            
        //     // Refresh the booking to get updated data
        //     $booking->refresh();
            
        //     Log::info('Payment status updated (Success):', [
        //         'booking_id' => $booking->id,
        //         'is_paid' => $booking->is_paid,
        //         'paid_at' => $booking->paid_at,
        //         'amount_paid' => $booking->amount_paid,
        //     ]);
            
        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Payment processed successfully',
        //         'booking' => $booking,
        //     ]);
        // }
        
        // Log::warning('Booking not found for success callback:', [
        //     'companyRef' => $companyRef,
        // ]);
        
        // return response()->json([
        //     'success' => false,
        //     'message' => 'Booking not found',
        // ], 404);
    // }
    
    // public function cancel(Request $request)
    // {
    //     Log::info('DPO Payment Cancel Callback:', $request->all());
        
    //     $transToken = $request->input('TransToken');
    //     $transRef = $request->input('TransRef');
    //     $companyRef = $request->input('CompanyRef');
        
    //     // Find booking by company reference
    //     $booking = Booking::where('reference', $companyRef)->first();
        
    //     if ($booking) {
    //         Log::info('Payment cancelled for booking:', [
    //             'booking_id' => $booking->id,
    //             'reference' => $booking->reference,
    //         ]);
            
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Payment cancelled',
    //             'booking' => $booking,
    //         ]);
    //     }
        
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Booking not found',
    //     ], 404);
    // }
    
    public function callBack(Request $request)
    {
        Log::info('DPO Payment Callback Received:', $request->all());
        Log::info('DPO Callback Headers:', $request->headers->all());

        $transToken = $request->input('TransToken');
        $transRef = $request->input('TransRef');
        $companyRef = $request->input('CompanyRef');
        $result = $request->input('Result');
        $resultExplanation = $request->input('ResultExplanation');
        
        Log::info('DPO Callback Parameters:', [
            'transToken' => $transToken,
            'transRef' => $transRef,
            'companyRef' => $companyRef,
            'result' => $result,
            'resultExplanation' => $resultExplanation,
        ]);
        
        // Find booking by company reference
        $booking = Booking::where('reference', $companyRef)->first();
        
        Log::info('Booking lookup result:', [
            'companyRef' => $companyRef,
            'bookingFound' => $booking ? 'Yes' : 'No',
            'bookingId' => $booking ? $booking->id : null,
            'currentIsPaid' => $booking ? $booking->is_paid : null,
        ]);
        
        if (!$booking) {
            Log::warning('Booking not found for callback:', ['companyRef' => $companyRef]);
            return response()->json([
                'success' => false,
                'message' => 'Booking not found',
            ], 404);
        }

        // CRITICAL SECURITY: Verify payment with DPO servers
        $dpoService = new \App\Services\DPOPaymentService();
        
        // Verify payment with DPO using HTTP request
        $verificationResult = $dpoService->verifyPayment($transToken, $transRef, $companyRef);

        Log::info('Payment verification result:', $verificationResult);

        // Only process payment if verified with DPO
        if ($verificationResult['success'] && $verificationResult['verified']) {
            Log::info('Payment verified with DPO - processing payment');
            
            if ($result === '000') {
                // Payment successful and verified
                Log::info('Processing verified successful payment for booking:', [
                    'booking_id' => $booking->id,
                    'amount' => $booking->amount,
                ]);
                
                $booking->update([
                    'is_paid' => true,
                    'paid_at' => now(),
                    'amount_paid' => $booking->amount,
                ]);
                
                // Refresh the booking to get updated data
                $booking->refresh();
                
                Log::info('Payment status updated:', [
                    'booking_id' => $booking->id,
                    'is_paid' => $booking->is_paid,
                    'paid_at' => $booking->paid_at,
                    'amount_paid' => $booking->amount_paid,
                ]);
                
                // Generate receipt
                try {
                    $receiptService = new ReceiptService();
                    $receipt = $receiptService->generateReceipt($booking);
                    
                    // Store receipt info in booking
                    $booking->update([
                        'receipt_url' => $receipt['url'],
                        'receipt_filename' => $receipt['filename'],
                    ]);
                    
                    Log::info('Receipt generated for booking:', [
                        'booking_id' => $booking->id,
                        'receipt_url' => $receipt['url'],
                        'filename' => $receipt['filename'],
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to generate receipt:', [
                        'booking_id' => $booking->id,
                        'error' => $e->getMessage(),
                    ]);
                }
                
                Log::info('Payment callback - Success (Verified):', [
                    'booking_id' => $booking->id,
                    'reference' => $booking->reference,
                    'result' => $result,
                    'explanation' => $resultExplanation,
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Payment verified and processed successfully',
                    'booking' => $booking,
                ]);
            } else {
                // Payment failed but verified
                Log::info('Payment callback - Failed (Verified):', [
                    'booking_id' => $booking->id,
                    'reference' => $booking->reference,
                    'result' => $result,
                    'explanation' => $resultExplanation,
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Payment failed (verified with DPO)',
                    'booking' => $booking,
                ]);
            }
        } else {
            // Payment NOT verified with DPO - SECURITY ALERT
            Log::error('SECURITY ALERT: Payment verification failed with DPO!', [
                'booking_id' => $booking->id,
                'reference' => $booking->reference,
                'transToken' => $transToken,
                'transRef' => $transRef,
                'verificationResult' => $verificationResult,
            ]);
            
            // DO NOT update booking status - this could be a malicious request
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed - security check failed',
                'error' => 'Payment not verified with DPO servers',
            ], 403);
        }
    }
}
