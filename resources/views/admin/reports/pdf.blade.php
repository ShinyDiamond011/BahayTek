<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<title>{{ $title }} — BahayTek</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',Arial,sans-serif;font-size:11px;color:#1a2e12;background:#fff;padding:28px 32px}
.report-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px;padding-bottom:16px;border-bottom:2px solid #336a29}
.report-brand{font-size:20px;font-weight:800;color:#336a29;letter-spacing:-0.5px}
.report-brand span{color:#80b155}
.report-meta{text-align:right;font-size:10px;color:#5a7248}
.report-title{font-size:15px;font-weight:700;color:#1a2e12;margin-bottom:4px}
.report-period{font-size:10.5px;color:#5a7248;margin-bottom:20px}
table{width:100%;border-collapse:collapse;font-size:10.5px}
thead tr{background:#336a29;color:#fff}
thead th{padding:8px 10px;text-align:left;font-weight:700;letter-spacing:.3px;font-size:9.5px;text-transform:uppercase}
tbody tr{border-bottom:1px solid #c8ddb0}
tbody tr:nth-child(even){background:#f5f9f0}
tbody td{padding:7px 10px;color:#2e4a1e}
.report-footer{margin-top:20px;padding-top:12px;border-top:1px solid #c8ddb0;font-size:9.5px;color:#5a7248;display:flex;justify-content:space-between}
.total-count{font-size:11px;font-weight:700;color:#336a29;margin-bottom:12px}
@media print{
  .no-print{display:none!important}
  body{padding:16px}
}
</style>
</head>
<body>

<div class="no-print" style="background:#336a29;color:#fff;padding:12px 16px;border-radius:8px;margin-bottom:20px;display:flex;gap:12px;align-items:center;flex-wrap:wrap">
  <div style="flex:1;min-width:200px">
    <strong style="font-size:12px;display:block;margin-bottom:2px">Download as PDF</strong>
    <span style="font-size:10.5px;opacity:.8">When the print dialog opens, set <em>Destination</em> to <strong>Save as PDF</strong>, then click Save.</span>
  </div>
  <button onclick="window.print()" style="background:#c1d95c;color:#1a2e12;border:none;padding:7px 20px;border-radius:6px;font-weight:700;font-size:12px;cursor:pointer;white-space:nowrap">
    ⬇ Download / Print
  </button>
  <button onclick="window.close()" style="background:rgba(255,255,255,.15);color:#fff;border:none;padding:7px 18px;border-radius:6px;font-weight:600;font-size:12px;cursor:pointer">Close</button>
</div>

<div class="report-header">
  <div>
    <div class="report-brand">BAHAY<span>TEK</span></div>
    <div style="font-size:9px;color:#80b155;letter-spacing:2px;text-transform:uppercase;margin-top:2px">Bahay Teknik — Official Report</div>
  </div>
  <div class="report-meta">
    <div style="font-weight:700;font-size:11px;color:#1a2e12">{{ $title }}</div>
    <div>Generated: {{ $generatedAt }}</div>
    <div>Period: {{ $periodLabel }}</div>
  </div>
</div>

<div class="total-count">{{ $rows->count() }} record{{ $rows->count() !== 1 ? 's' : '' }}</div>

<table>
  <thead>
    <tr>
      @foreach($columns as $col)
      <th>{{ $col }}</th>
      @endforeach
    </tr>
  </thead>
  <tbody>
    @forelse($rows as $row)
    <tr>
      @foreach($row as $cell)
      <td>{{ $cell }}</td>
      @endforeach
    </tr>
    @empty
    <tr><td colspan="{{ count($columns) }}" style="text-align:center;padding:20px;color:#5a7248">No data for this period.</td></tr>
    @endforelse
  </tbody>
</table>

<div class="report-footer">
  <span>BahayTek Administration System</span>
  <span>Confidential — Internal Use Only</span>
</div>

<script class="no-print">
window.onload = function(){ window.print(); };
</script>
</body>
</html>
