<!DOCTYPE html>
<html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f5f9f0;font-family:'Helvetica Neue',Arial,sans-serif;color:#1a2e12">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f9f0;padding:32px 0">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%">

      <tr><td style="background:#336a29;border-radius:12px 12px 0 0;padding:28px 36px;text-align:center">
        <div style="font-family:Georgia,serif;font-size:22px;font-weight:700;color:#fff;letter-spacing:1px">BAHAYTEK</div>
        <div style="font-size:11px;color:rgba(255,255,255,.6);letter-spacing:2px;text-transform:uppercase;margin-top:3px">Consultation Booking</div>
      </td></tr>

      <tr><td style="background:#fff;padding:36px 36px 28px">
        <h2 style="font-size:20px;margin:0 0 6px;color:#1a2e12">Booking Received!</h2>
        <p style="font-size:14px;color:#5a7248;margin:0 0 24px">Hi {{ $booking->first_name }}, we've received your consultation request. Our team will confirm your booking within 24 hours.</p>

        <!-- Details -->
        <div style="background:#f5f9f0;border:1px solid #c8ddb0;border-radius:8px;padding:20px;margin-bottom:24px">
          <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#5a7248;margin-bottom:14px">Booking Details</div>
          @php
            $details = [
              'Date'       => $booking->schedule?->date?->format('F j, Y') ?? '—',
              'Time'       => $booking->schedule?->time_range ?? '—',
              'Service'    => $booking->schedule?->type_label ?? 'Consultation',
              'Topic'      => $booking->topic,
              'Attendees'  => $booking->no_attendees,
            ];
            if($booking->organization) $details['Organization'] = $booking->organization;
          @endphp
          @foreach($details as $label => $value)
          <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #edf5e3;font-size:13px">
            <span style="color:#5a7248;font-weight:600">{{ $label }}</span>
            <span style="color:#1a2e12;text-align:right;max-width:60%">{{ $value }}</span>
          </div>
          @endforeach
        </div>

        <div style="background:#dcfce7;border:1px solid #86efac;border-radius:8px;padding:14px 18px;margin-bottom:24px">
          <div style="font-size:13px;color:#166534;font-weight:600">What's Next?</div>
          <div style="font-size:12px;color:#166534;margin-top:4px;line-height:1.6">Our team will review your booking and send a confirmation email within 24 hours. If you need to make changes, please reply to this email.</div>
        </div>

        <p style="font-size:13px;color:#5a7248;line-height:1.7;margin:0">Thank you for reaching out to BAHAYTEK. We look forward to speaking with you!</p>
      </td></tr>

      <tr><td style="background:#f5f9f0;border-radius:0 0 12px 12px;padding:20px 36px;text-align:center;border-top:1px solid #c8ddb0">
        <p style="font-size:11px;color:#5a7248;margin:0">© {{ date('Y') }} Bahay Teknik · Bicol, Philippines</p>
      </td></tr>

    </table>
  </td></tr>
</table>
</body></html>
