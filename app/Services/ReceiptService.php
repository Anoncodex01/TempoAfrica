<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReceiptService
{
    public function generateReceipt(Booking $booking)
    {
        // Load relationships
        $booking->load(['accommodation', 'accommodationRoom', 'customer']);
        
        // Generate HTML content
        $html = $this->generateReceiptHtml($booking);
        
        // Configure PDF options
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        // Create PDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Generate filename
        $filename = 'receipt_' . $booking->reference . '_' . date('Y-m-d_H-i-s') . '.pdf';
        
        // Save to public/images/receipts folder (using existing images directory)
        $publicPath = public_path('images/receipts/' . $filename);
        
        // Create directory if it doesn't exist
        $directory = dirname($publicPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        file_put_contents($publicPath, $dompdf->output());
        
        // Generate URL for public access
        $url = url('images/receipts/' . $filename);
        
        // Log the generated URL for debugging
        \Log::info('Receipt generated', [
            'booking_id' => $booking->id,
            'filename' => $filename,
            'url' => $url,
            'storage_path' => $path
        ]);
        
        return [
            'filename' => $filename,
            'path' => 'receipts/' . $filename,
            'url' => $url,
        ];
    }
    
    private function generateReceiptHtml(Booking $booking)
    {
        $accommodation = $booking->accommodation;
        $room = $booking->accommodationRoom;
        $customer = $booking->customer;
        
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Booking Receipt</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    color: #333;
                }
                .header {
                    text-align: center;
                    border-bottom: 2px solid #FFB300;
                    padding-bottom: 20px;
                    margin-bottom: 30px;
                }
                .logo {
                    font-size: 24px;
                    font-weight: bold;
                    color: #FFB300;
                    margin-bottom: 10px;
                }
                .receipt-title {
                    font-size: 18px;
                    color: #494140;
                    margin-bottom: 5px;
                }
                .receipt-number {
                    font-size: 14px;
                    color: #6E7A8A;
                }
                .section {
                    margin-bottom: 25px;
                }
                .section-title {
                    font-size: 16px;
                    font-weight: bold;
                    color: #494140;
                    border-bottom: 1px solid #E0E4EA;
                    padding-bottom: 5px;
                    margin-bottom: 15px;
                }
                .row {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 8px;
                }
                .label {
                    font-weight: bold;
                    color: #6E7A8A;
                }
                .value {
                    color: #494140;
                }
                .total {
                    font-size: 18px;
                    font-weight: bold;
                    color: #D71518;
                    border-top: 2px solid #E0E4EA;
                    padding-top: 10px;
                    margin-top: 15px;
                }
                .status {
                    display: inline-block;
                    padding: 5px 15px;
                    background-color: #4CAF50;
                    color: white;
                    border-radius: 20px;
                    font-size: 12px;
                    font-weight: bold;
                }
                .footer {
                    margin-top: 40px;
                    text-align: center;
                    font-size: 12px;
                    color: #6E7A8A;
                    border-top: 1px solid #E0E4EA;
                    padding-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="logo">TEMPO AFRICA</div>
                <div class="receipt-title">Booking Receipt</div>
                <div class="receipt-number">Receipt #' . $booking->reference . '</div>
            </div>
            
            <div class="section">
                <div class="section-title">Accommodation Details</div>
                <div class="row">
                    <span class="label">Property:</span>
                    <span class="value">' . ($accommodation ? $accommodation->name : 'N/A') . '</span>
                </div>
                <div class="row">
                    <span class="label">Room Type:</span>
                    <span class="value">' . ($room ? $room->name : 'N/A') . '</span>
                </div>
                <div class="row">
                    <span class="label">Location:</span>
                    <span class="value">' . ($accommodation ? $accommodation->location : 'N/A') . '</span>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">Booking Details</div>
                <div class="row">
                    <span class="label">Check-in Date:</span>
                    <span class="value">' . ($booking->from_date ? $booking->from_date->format('D, d M Y') : 'N/A') . '</span>
                </div>
                <div class="row">
                    <span class="label">Check-out Date:</span>
                    <span class="value">' . ($booking->to_date ? $booking->to_date->format('D, d M Y') : 'N/A') . '</span>
                </div>
                <div class="row">
                    <span class="label">Duration:</span>
                    <span class="value">' . $booking->duration . ' night(s)</span>
                </div>
                <div class="row">
                    <span class="label">Guests:</span>
                    <span class="value">' . $booking->pacs . ' person(s)</span>
                </div>
                <div class="row">
                    <span class="label">Status:</span>
                    <span class="value"><span class="status">' . ucfirst($booking->status) . '</span></span>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">Customer Details</div>
                <div class="row">
                    <span class="label">Name:</span>
                    <span class="value">' . ($customer ? $customer->name : 'N/A') . '</span>
                </div>
                <div class="row">
                    <span class="label">Phone:</span>
                    <span class="value">' . ($customer ? $customer->phone : 'N/A') . '</span>
                </div>
                <div class="row">
                    <span class="label">Email:</span>
                    <span class="value">' . ($customer ? $customer->email : 'N/A') . '</span>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">Payment Details</div>
                <div class="row">
                    <span class="label">Payment Status:</span>
                    <span class="value">' . ($booking->is_paid ? 'Paid' : 'Pending') . '</span>
                </div>
                <div class="row">
                    <span class="label">Payment Date:</span>
                    <span class="value">' . ($booking->paid_at ? $booking->paid_at->format('D, d M Y H:i') : 'N/A') . '</span>
                </div>
                <div class="row">
                    <span class="label">Price per Night:</span>
                    <span class="value">' . $booking->currency . ' ' . number_format($booking->price) . '</span>
                </div>
                <div class="row">
                    <span class="label">Total Amount:</span>
                    <span class="value">' . $booking->currency . ' ' . number_format($booking->amount) . '</span>
                </div>
                <div class="row total">
                    <span class="label">Amount Paid:</span>
                    <span class="value">' . $booking->currency . ' ' . number_format($booking->amount_paid ?? $booking->amount) . '</span>
                </div>
            </div>
            
            <div class="footer">
                <p>Thank you for choosing Tempo Africa!</p>
                <p>For any questions, please contact our support team.</p>
                <p>Generated on: ' . now()->format('D, d M Y H:i:s') . '</p>
            </div>
        </body>
        </html>';
    }
} 