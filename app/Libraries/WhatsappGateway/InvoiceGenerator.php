<?php

namespace App\Libraries\WhatsappGateway;

use Illuminate\Support\Facades\DB;

class InvoiceGenerator {
    public function generateInvoiceImage($orderId) {
    // Get order data
    $order = DB::table('orders')
        ->select('orders.*', 'services.name as service_name')
        ->join('services', 'orders.service_id', '=', 'services.id')
        ->where('orders.id', $orderId)
        ->first();

    if (!$order) {
        return false;
    }

    // Generate SVG
    $svg = $this->createInvoiceSVG($order);
    
    // Save SVG to file
    $svgFileName = 'invoice_' . $orderId . '.svg';
    $svgFilePath = public_path('media/whatsapp/invoices/' . $svgFileName);
    file_put_contents($svgFilePath, $svg);

    // Convert SVG to PNG
    $pngFileName = 'invoice_' . $orderId . '.png';
    $pngFilePath = public_path('media/whatsapp/invoices/' . $pngFileName);
    
    // Use Imagick to convert SVG to PNG
    $imagick = new \Imagick();
    $imagick->readImage($svgFilePath);
    $imagick->setImageFormat('png');
    $imagick->writeImage($pngFilePath);
    $imagick->clear();
    $imagick->destroy();

    return $pngFileName; // Kembalikan nama file PNG
}

    private function createInvoiceSVG($order) {
        // Format currency
        $total = number_format($order->price, 0, ',', '.');
        
        // Format date
        $date = date('d/m/Y H:i', strtotime($order->created_at));
        
        // Get status color
        $statusColor = match ($order->status) {
            'sukses' => '#34D399',    // Green
            'pending' => '#FCD34D',    // Yellow
            'proses' => '#60A5FA',     // Blue
            'gagal' => '#EF4444',      // Red
            default => '#6B7280'       // Gray
        };
        
        return <<<SVG
        <?xml version="1.0" encoding="UTF-8" standalone="no"?>
        <svg xmlns="http://www.w3.org/2000/svg" width="800" height="400" viewBox="0 0 800 400">
            <!-- Background -->
            <rect width="800" height="400" fill="#ffffff"/>
            <rect width="800" height="80" fill="#2563eb"/>
            
            <!-- Header -->
            <text x="40" y="50" font-family="Arial" font-size="24" fill="white">INVOICE</text>
            <text x="650" y="50" font-family="Arial" font-size="24" fill="white">#${order->invoice}</text>
            
            <!-- Order Info -->
            <text x="40" y="120" font-family="Arial" font-size="16" fill="#374151">Order Information:</text>
            <text x="40" y="150" font-family="Arial" font-size="14" fill="#6B7280">Service: ${order->service_name}</text>
            <text x="40" y="170" font-family="Arial" font-size="14" fill="#6B7280">Target: ${order->target}</text>
            
            <!-- Date and Status -->
            <text x="450" y="120" font-family="Arial" font-size="16" fill="#374151">Date: ${date}</text>
            
            <!-- Status -->
            <rect x="450" y="140" width="100" height="30" rx="15" fill="${statusColor}"/>
            <text x="500" y="160" font-family="Arial" font-size="14" fill="white" text-anchor="middle">${order->status}</text>
            
            <!-- Divider -->
            <line x1="40" y1="200" x2="760" y2="200" stroke="#E5E7EB" stroke-width="2"/>
            
            <!-- Details -->
            <text x="40" y="240" font-family="Arial" font-size="16" fill="#374151">Order Details:</text>
            <text x="40" y="270" font-family="Arial" font-size="14" fill="#6B7280">Quantity: ${order->quantity}</text>
            
            <!-- Total -->
            <rect x="40" y="320" width="720" height="50" fill="#F3F4F6"/>
            <text x="60" y="350" font-family="Arial" font-size="16" font-weight="bold" fill="#374151">Total Amount:</text>
            <text x="700" y="350" font-family="Arial" font-size="16" font-weight="bold" fill="#374151" text-anchor="end">Rp ${total}</text>
        </svg>
        SVG;
    }
}