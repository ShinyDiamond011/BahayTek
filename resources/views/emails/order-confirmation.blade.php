<!DOCTYPE html>
<html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f5f9f0;font-family:'Helvetica Neue',Arial,sans-serif;color:#1a2e12">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f9f0;padding:32px 0">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%">

      <!-- Header -->
      <tr><td style="background:#336a29;border-radius:12px 12px 0 0;padding:28px 36px;text-align:center">
        <div style="font-family:Georgia,serif;font-size:22px;font-weight:700;color:#fff;letter-spacing:1px">BAHAYTEK</div>
        <div style="font-size:11px;color:rgba(255,255,255,.6);letter-spacing:2px;text-transform:uppercase;margin-top:3px">Bahay Teknik</div>
      </td></tr>

      <!-- Body -->
      <tr><td style="background:#fff;padding:36px 36px 28px">
        <h2 style="font-size:20px;margin:0 0 6px;color:#1a2e12">Order Confirmed!</h2>
        <p style="font-size:14px;color:#5a7248;margin:0 0 24px">Hi {{ $shipping['first_name'] ?? 'there' }}, your order has been received and is being processed.</p>

        <!-- Order number badge -->
        <div style="background:#f5f9f0;border:1px solid #c8ddb0;border-radius:8px;padding:14px 20px;margin-bottom:24px;display:flex;justify-content:space-between">
          <span style="font-size:13px;color:#5a7248">Order Number</span>
          <strong style="font-size:13px;color:#336a29">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong>
        </div>

        <!-- Items -->
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px">
          <tr>
            <th align="left" style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#5a7248;padding-bottom:10px;border-bottom:2px solid #c8ddb0">Item</th>
            <th align="center" style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#5a7248;padding-bottom:10px;border-bottom:2px solid #c8ddb0">Qty</th>
            <th align="right" style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#5a7248;padding-bottom:10px;border-bottom:2px solid #c8ddb0">Price</th>
          </tr>
          @foreach($order->items as $item)
          <tr>
            <td style="padding:12px 0;border-bottom:1px solid #edf5e3;font-size:13px;color:#1a2e12">
              {{ $item->variant?->product?->prod_name ?? 'Product' }}
              @if($item->variant?->var_name)
              <div style="font-size:11px;color:#5a7248">{{ $item->variant->var_name }}</div>
              @endif
            </td>
            <td align="center" style="padding:12px 0;border-bottom:1px solid #edf5e3;font-size:13px;color:#1a2e12">{{ $item->quantity }}</td>
            <td align="right" style="padding:12px 0;border-bottom:1px solid #edf5e3;font-size:13px;color:#1a2e12">₱{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
          </tr>
          @endforeach
          <tr>
            <td colspan="2" align="right" style="padding:14px 0 0;font-size:15px;font-weight:700;color:#1a2e12">Total</td>
            <td align="right" style="padding:14px 0 0;font-size:15px;font-weight:700;color:#336a29">₱{{ number_format($order->total_amount, 2) }}</td>
          </tr>
        </table>

        <!-- Shipping address -->
        <div style="background:#f5f9f0;border-radius:8px;padding:16px 20px;margin-bottom:24px">
          <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#5a7248;margin-bottom:8px">Shipping To</div>
          <div style="font-size:13px;color:#1a2e12;line-height:1.7">
            {{ ($shipping['first_name'] ?? '') . ' ' . ($shipping['last_name'] ?? '') }}<br>
            {{ $shipping['street'] ?? '' }}<br>
            {{ $shipping['city'] ?? '' }}, {{ $shipping['province'] ?? '' }}
          </div>
        </div>

        <!-- Payment method -->
        <div style="font-size:13px;color:#5a7248;margin-bottom:24px">
          <strong style="color:#1a2e12">Payment:</strong> {{ ucfirst($order->payment_method) }}
          &nbsp;·&nbsp;
          <strong style="color:#1a2e12">Status:</strong> {{ ucfirst($order->payment_status) }}
        </div>

        <p style="font-size:13px;color:#5a7248;line-height:1.7;margin:0">You will receive another email when your order ships. For questions, reply to this email or visit our website.</p>
      </td></tr>

      <!-- Footer -->
      <tr><td style="background:#f5f9f0;border-radius:0 0 12px 12px;padding:20px 36px;text-align:center;border-top:1px solid #c8ddb0">
        <p style="font-size:11px;color:#5a7248;margin:0">© {{ date('Y') }} Bahay Teknik · Bicol, Philippines</p>
      </td></tr>

    </table>
  </td></tr>
</table>
</body></html>
