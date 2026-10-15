<!DOCTYPE html>
<html>
<head>
    <title>Salary Slip</title>
    <style>
        body{font-family:Arial,sans-serif;padding:30px;max-width:720px;margin:auto}
        table{width:100%;border-collapse:collapse}
        td{padding:8px;border-bottom:1px solid #ddd}
        td:last-child{text-align:right}
        .total td{font-weight:bold;font-size:18px;border-top:2px solid #000}
    </style>
</head>
<body onload="window.print()">
    <h2>Salary Slip - {{ date('F', mktime(0,0,0,$payment->payment_month,1)) }} {{ $payment->payment_year }}</h2>
    <p>{{ $payment->name }}<br>{{ $payment->address }}<br>Date: {{ $payment->payment_date }}</p>
    <table>
        <tr><td>Working Hours</td><td>{{ number_format($payment->hours, 2) }} hrs</td></tr>
        <tr><td>Hourly Rate (AED)</td><td>{{ number_format($payment->hour_rate, 2) }}</td></tr>
        <tr><td>Basic Salary (AED)</td><td>{{ number_format($payment->hours * $payment->hour_rate, 2) }}</td></tr>
        <tr><td>Transport (AED)</td><td>{{ number_format($payment->transport_amount, 2) }}</td></tr>
        <tr><td>Gross Salary (AED)</td><td>{{ number_format($payment->gross_amount, 2) }}</td></tr>
        <tr><td>Advance Deduction (AED)</td><td>{{ number_format($payment->advance_deduction, 2) }}</td></tr>
        <tr class="total"><td>Net Payable (AED)</td><td>{{ number_format($payment->payment_amount, 2) }}</td></tr>
    </table>
    <p>{{ $payment->remarks }}</p>
</body>
</html>