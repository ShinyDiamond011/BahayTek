<!DOCTYPE html>
<html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f5f9f0;font-family:'Helvetica Neue',Arial,sans-serif;color:#1a2e12">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f9f0;padding:32px 0">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%">

      <tr><td style="background:#336a29;border-radius:12px 12px 0 0;padding:28px 36px;text-align:center">
        <div style="font-family:Georgia,serif;font-size:22px;font-weight:700;color:#fff;letter-spacing:1px">BAHAYTEK</div>
        <div style="font-size:11px;color:rgba(255,255,255,.6);letter-spacing:2px;text-transform:uppercase;margin-top:3px">Order Update</div>
      </td></tr>

      <tr><td style="background:#fff;padding:36px 36px 28px">
        <h2 style="font-size:20px;margin:0 0 6px;color:#1a2e12">Your Order Has Been Updated</h2>
        <p style="font-size:14px;color:#5a7248;margin:0 0 24px">Hi {{ $shipping['first_name'] ?? 'there' }}, here's the latest status on your order.</p>

        <div style="background:#f5f9f0;border:1px solid #c8ddb0;border-radius:8px;padding:20px;margin-bottom:24px;text-align:center">
          <div style="font-size:11px;color:#5a7248;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</div>
          @php
            $statusColor = match($order->status) {
              'confirmed'  => '#166534',
              'processing' => '#1d4ed8',
              'shipped'    => '#7c3aed',
              'delivered'  => '#059669',
              'cancelled'  => '#dc2626',
              default      => '#854d0e',
            };
          @endphp
          <div style="font-size:22px;font-weight:700;color:{{ $statusColor }}">{{ ucfirst($order->status) }}</div>
          @if($order->payment_status === 'paid')
          <div style="font-size:12px;color:#059669;margin-top:4px">Payment confirmed</div>
          @endif
        </div>

        <!-- Items summary -->
        <div style="margin-bottom:24px">
          @foreach($order->items as $item)
          <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #edf5e3;font-size:13px">
            <span style="color:#1a2e12">{{ $item->variant?->product?->prod_name ?? 'Product' }} × {{ $item->quantity }}</span>
            <span style="color:#336a29;font-weight:600">₱{{ number_format($item->unit_price * $item->quantity, 2) }}</span>
          </div>
          @endforeach
          <div style="text-align:right;padding-top:10px;font-size:14px;font-weight:700;color:#1a2e12">
            Total: ₱{{ number_format($order->total_amount, 2) }}
          </div>
        </div>

        <p style="font-size:13px;color:#5a7248;line-height:1.7;margin:0">Questions about your order? Reply to this email or contact us at <a href="https://bahaytek.com" style="color:#336a29">bahaytek.com</a>.</p>
      </td></tr>

      <tr><td style="background:#f5f9f0;border-radius:0 0 12px 12px;padding:20px 36px;text-align:center;border-top:1px solid #c8ddb0">
        <p style="font-size:11px;color:#5a7248;margin:0">© {{ date('Y') }} Bahay Teknik · Bicol, Philippines</p>
      </td></tr>

    </table>
  </td></tr>
</table>
</body></html>
