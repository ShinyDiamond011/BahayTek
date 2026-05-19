<?php

namespace App\Services;

use App\Models\BookingSchedule;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SemaphoreService
{
    private string $apiKey;
    private string $senderName;

    public function __construct()
    {
        $this->apiKey     = config('services.semaphore.api_key', '');
        $this->senderName = config('services.semaphore.sender_name', 'BAHAYTEK');
    }

    public function send(string $phone, string $message): bool
    {
        if (empty($this->apiKey)) {
            Log::warning('Semaphore: API key not set, skipping SMS to ' . $phone);
            return false;
        }

        $normalized = $this->normalizePhone($phone);

        if (!$normalized) {
            Log::warning('Semaphore: invalid phone number', ['original' => $phone]);
            return false;
        }

        $response = Http::post('https://api.semaphore.co/api/v4/messages', [
            'apikey'     => $this->apiKey,
            'number'     => $normalized,
            'message'    => $message,
            'sendername' => $this->senderName,
        ]);

        if ($response->failed()) {
            Log::error('Semaphore SMS failed', [
                'phone'    => $normalized,
                'status'   => $response->status(),
                'response' => $response->json(),
            ]);
            return false;
        }

        return true;
    }

    public function sendOrderConfirmation(Order $order): bool
    {
        $shipping = json_decode($order->shipping_address, true) ?? [];
        $phone    = $shipping['phone'] ?? null;
        if (!$phone) return false;

        $orderNum = '#' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
        $total    = 'PHP ' . number_format($order->total_amount, 2);
        $message  = "BAHAYTEK: Order {$orderNum} received! Total: {$total}. We will process it shortly. Thank you!";

        return $this->send($phone, $message);
    }

    public function sendOrderStatusUpdate(Order $order): bool
    {
        $shipping = json_decode($order->shipping_address, true) ?? [];
        $phone    = $shipping['phone'] ?? null;
        if (!$phone) return false;

        $orderNum = '#' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
        $status   = ucfirst($order->status);
        $message  = "BAHAYTEK: Order {$orderNum} is now {$status}. Questions? Contact us at bahaytek.com.";

        return $this->send($phone, $message);
    }

    public function sendBookingConfirmation(BookingSchedule $booking): bool
    {
        if (!$booking->phone) return false;

        $booking->loadMissing('schedule');
        $date    = $booking->schedule?->date?->format('M j, Y') ?? '';
        $time    = $booking->schedule?->time_range ?? '';
        $message = "BAHAYTEK: Consultation booking received for {$date} {$time}. We will confirm within 24 hours.";

        return $this->send($booking->phone, $message);
    }

    private function normalizePhone(string $phone): ?string
    {
        $digits = preg_replace('/\D/', '', $phone);

        if (str_starts_with($digits, '63') && strlen($digits) === 12) {
            $digits = '0' . substr($digits, 2);
        } elseif (str_starts_with($digits, '9') && strlen($digits) === 10) {
            $digits = '0' . $digits;
        }

        if (strlen($digits) === 11 && str_starts_with($digits, '09')) {
            return $digits;
        }

        return null;
    }
}
