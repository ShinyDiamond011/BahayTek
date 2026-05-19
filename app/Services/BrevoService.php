<?php

namespace App\Services;

use App\Models\BookingSchedule;
use App\Models\Order;
use App\Models\TrainingRegistration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoService
{
    private string $apiKey;
    private string $fromEmail;
    private string $fromName;
    private string $baseUrl = 'https://api.brevo.com/v3';

    public function __construct()
    {
        $this->apiKey    = config('services.brevo.api_key', '');
        $this->fromEmail = config('services.brevo.from_email', 'noreply@bahaytek.com');
        $this->fromName  = config('services.brevo.from_name', 'BAHAYTEK');
    }

    public function send(string $toEmail, string $toName, string $subject, string $htmlContent): bool
    {
        if (empty($this->apiKey)) {
            Log::warning('Brevo: API key not set, skipping email to ' . $toEmail);
            return false;
        }

        $response = Http::withHeaders(['api-key' => $this->apiKey])
            ->post("{$this->baseUrl}/smtp/email", [
                'sender'      => ['name' => $this->fromName, 'email' => $this->fromEmail],
                'to'          => [['email' => $toEmail, 'name' => $toName]],
                'subject'     => $subject,
                'htmlContent' => $htmlContent,
            ]);

        if ($response->failed()) {
            Log::error('Brevo email failed', [
                'to'       => $toEmail,
                'subject'  => $subject,
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
        $email    = $shipping['email'] ?? $order->user?->email;
        $name     = trim(($shipping['first_name'] ?? '') . ' ' . ($shipping['last_name'] ?? ''));

        if (!$email) return false;

        $order->loadMissing('items.variant.product');
        $html = view('emails.order-confirmation', compact('order', 'shipping'))->render();

        return $this->send(
            $email, $name,
            'Order Confirmed — BAHAYTEK #' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
            $html
        );
    }

    public function sendOrderStatusUpdate(Order $order): bool
    {
        $shipping = json_decode($order->shipping_address, true) ?? [];
        $email    = $shipping['email'] ?? $order->user?->email;
        $name     = trim(($shipping['first_name'] ?? '') . ' ' . ($shipping['last_name'] ?? ''));

        if (!$email) return false;

        $order->loadMissing('items.variant.product');
        $html = view('emails.order-status-update', compact('order', 'shipping'))->render();

        return $this->send(
            $email, $name,
            'Order Update — BAHAYTEK #' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
            $html
        );
    }

    public function sendBookingConfirmation(BookingSchedule $booking): bool
    {
        if (!$booking->email) return false;

        $booking->loadMissing('schedule');
        $html = view('emails.booking-confirmation', compact('booking'))->render();

        return $this->send(
            $booking->email,
            $booking->full_name,
            'Consultation Booking Received — BAHAYTEK',
            $html
        );
    }

    public function sendWorkshopEnrollment(TrainingRegistration $registration): bool
    {
        $email = $registration->user?->email;
        $name  = $registration->user
            ? trim($registration->user->first_name . ' ' . $registration->user->last_name)
            : '';

        if (!$email) return false;

        $registration->loadMissing('trainingSession');
        $html = view('emails.workshop-enrollment', compact('registration'))->render();

        return $this->send(
            $email, $name,
            'Workshop Enrollment Submitted — BAHAYTEK',
            $html
        );
    }
}
