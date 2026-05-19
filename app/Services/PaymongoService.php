<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymongoService
{
    private string $secretKey;
    private string $webhookSecret;
    private string $baseUrl = 'https://api.paymongo.com/v1';

    public function __construct()
    {
        $this->secretKey     = config('services.paymongo.secret_key', '');
        $this->webhookSecret = config('services.paymongo.webhook_secret', '');
    }

    public function createCheckoutSession(Order $order): array
    {
        $shipping    = json_decode($order->shipping_address, true) ?? [];
        $orderNumber = 'ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);

        $lineItems = $order->items->map(function ($item) {
            $name = $item->variant?->product?->prod_name ?? 'Product';
            if ($item->variant?->var_name) {
                $name .= ' — ' . $item->variant->var_name;
            }
            return [
                'currency'    => 'PHP',
                'amount'      => (int) round($item->unit_price * 100),
                'description' => $name,
                'name'        => $name,
                'quantity'    => $item->quantity,
            ];
        })->values()->all();

        $response = Http::withBasicAuth($this->secretKey, '')
            ->post("{$this->baseUrl}/checkout_sessions", [
                'data' => [
                    'attributes' => [
                        'billing' => [
                            'name'  => trim(($shipping['first_name'] ?? '') . ' ' . ($shipping['last_name'] ?? '')),
                            'email' => $shipping['email'] ?? '',
                            'phone' => $shipping['phone'] ?? '',
                        ],
                        'line_items'           => $lineItems,
                        'payment_method_types' => ['card', 'gcash', 'paymaya', 'grab_pay'],
                        'success_url'          => route('shop.payment.success', $order),
                        'cancel_url'           => route('shop.payment.cancel', $order),
                        'description'          => "BAHAYTEK {$orderNumber}",
                        'reference_number'     => $orderNumber,
                        'metadata'             => ['order_id' => (string) $order->id],
                    ],
                ],
            ]);

        if ($response->failed()) {
            Log::error('PayMongo checkout session error', [
                'order_id' => $order->id,
                'status'   => $response->status(),
                'body'     => $response->json(),
            ]);
            throw new \RuntimeException('Payment gateway error. Please try again or contact us.');
        }

        $data = $response->json('data');

        return [
            'session_id'   => $data['id'],
            'checkout_url' => $data['attributes']['checkout_url'],
        ];
    }

    public function verifyWebhookSignature(string $rawPayload, string $sigHeader): bool
    {
        if (empty($this->webhookSecret) || empty($sigHeader)) return false;

        // Header format: "t=<timestamp>,te=<signature>"
        $parts = [];
        foreach (explode(',', $sigHeader) as $segment) {
            [$k, $v]   = array_pad(explode('=', $segment, 2), 2, '');
            $parts[$k] = $v;
        }

        if (empty($parts['t']) || empty($parts['te'])) return false;

        $computed = hash_hmac('sha256', $parts['t'] . '.' . $rawPayload, $this->webhookSecret);

        return hash_equals($computed, $parts['te']);
    }
}
