<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Salary Payslip Print</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: Arial, Helvetica, sans-serif;
    background: #e9edf1;
    padding: 30px 10px;
  }

  .action-bar {
    max-width: 850px;
    margin: 0 auto 15px auto;
    display: flex;
    justify-content: space-between;
    gap: 10px;
  }
  .action-bar button {
    font-size: 14px;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
  }
  .btn-back { background: #5da54f; color: #ffffff; }
  .btn-back:hover { background: #81be74; }
  .btn-print { background: #14335e; color: #fff; }
  .btn-print:hover { background: #0d2745; }

  .payslip {
    max-width: 850px;
    margin: 0 auto;
    background: #fff;
    padding: 40px 45px;
    border: 1px solid #ddd;
  }

  .header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    border-bottom: 3px solid #14335e;
    padding-bottom: 18px;
    margin-bottom: 20px;
  }
  .company-name { font-size: 30px; font-weight: bold; color: #14335e; font-family: Georgia, serif; }
  .company-tagline { font-size: 12px; letter-spacing: 2px; color: #555; margin-top: 4px; }
  .contact-info { text-align: right; font-size: 13px; color: #333; line-height: 1.6; }

  .title-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #14335e;
    color: #fff;
    padding: 16px 20px;
    margin-bottom: 20px;
  }
  .title-bar h1 { font-size: 24px; font-weight: 600; }
  .title-bar .month-info {
    background: #dbe8f7;
    color: #14335e;
    padding: 10px 16px;
    font-size: 14px;
    text-align: right;
  }
  .title-bar .month-info strong { font-size: 15px; }

  .employee-section {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
  }
  .photo-placeholder {
    width: 110px;
    height: 130px;
    background: #f2f2f2;
    border: 1px dashed #aaa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    color: #999;
    text-align: center;
    flex-shrink: 0;
  }
  .employee-details { flex: 1; }
  .employee-details table { width: 100%; font-size: 14px; }
  .employee-details td { padding: 5px 0; }
  .employee-details td.label { color: #555; width: 35%; }
  .employee-details td.colon { width: 15px; color: #999; }
  .employee-details td.value { font-weight: bold; color: #14335e; }

  .section-header {
    padding: 10px 15px;
    font-weight: bold;
    font-size: 15px;
    margin-top: 10px;
  }
  .section-header.work { background: #dbe8f7; color: #14335e; }
  .section-header.earnings { background: #d5eedd; color: #1e7a3d; }
  .section-header.deductions { background: #f8d7da; color: #a12a34; }
  .section-header.final { background: #d5eedd; color: #1e7a3d; }

  table.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    margin-bottom: 15px;
  }
  table.data-table th, table.data-table td {
    border: 1px solid #e2e2e2;
    padding: 10px 12px;
    text-align: left;
  }
  table.data-table th { background: #f4f6f8; font-size: 13px; }
  table.data-table td.num, table.data-table th.num { text-align: right; }
  table.data-table tr.total td {
    background: #eaf2fb;
    font-weight: bold;
    color: #14335e;
  }
  table.data-table tr.total-deduction td {
    background: #fbeaea;
    font-weight: bold;
    color: #a12a34;
  }

  .work-grid {
    display: flex;
    border: 1px solid #e2e2e2;
    margin-bottom: 15px;
    text-align: center;
  }
  .work-grid div { flex: 1; padding: 14px 10px; border-right: 1px solid #e2e2e2; }
  .work-grid div:last-child { border-right: none; }
  .work-grid .wlabel { font-size: 13px; color: #555; margin-bottom: 6px; }
  .work-grid .wvalue { font-size: 18px; font-weight: bold; color: #14335e; }

  .final-box {
    display: flex;
    border: 1px solid #cdeadb;
    margin-bottom: 25px;
  }
  .final-box .flabel {
    flex: 1;
    padding: 18px 20px;
    font-size: 18px;
    font-weight: bold;
    color: #222;
  }
  .final-box .fvalue {
    background: #d5eedd;
    padding: 18px 20px;
    font-size: 26px;
    font-weight: bold;
    color: #1e7a3d;
  }

  .remarks { font-size: 14px; margin-bottom: 40px; }
  .remarks strong { color: #14335e; }

  .footer {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    font-size: 13px;
  }
  .sign-line {
    border-top: 1px solid #333;
    width: 200px;
    padding-top: 6px;
    text-align: center;
  }
  .footer-right { text-align: right; color: #555; }
  .footer-right .note { font-size: 11px; color: #999; margin-top: 4px; }

  @media print {
    body { background: #fff; padding: 0; }
    .payslip { border: none; }
    .action-bar { display: none !important; }
  }
</style>
</head>
<body>

<div class="action-bar">
  <button class="btn-back" onclick="history.back()">&larr; Back</button>
  <button class="btn-print" onclick="window.print()">🖨️ Print / Save as PDF</button>
</div>

<div class="payslip">

  <div class="header">
    <div>
        <div class="company-name"><img src="{{asset($admin_setting->logo)}}"></div>
      
      <!-- <div class="company-tagline">TAGLINE / DIVISION</div> -->
    </div>
    <div class="contact-info">
        <div class="company-name">{{$admin_setting->title}}</div>
      {{$setting->address}}<br>
      {{$setting->primary_mobile}}<br>
      {{$setting->secondary_mobile}}<br>
      {{$setting->primary_email}}
    </div>
  </div>

  <div class="title-bar">
    <h1>SALARY <span style="font-weight:400;">PAYSLIP</span></h1>
    <div class="month-info">
      Month: <strong>{{$selectedmonth}}</strong><br>
      Payment Type: <strong>{{$year}}</strong>
    </div>
  </div>

  <div class="employee-section">
    @if($data['user']->image)
     <div class="photo-placeholder"><img src="{{asset($data['user']->image)}}" width="110px"></div>
    @else
    <div class="photo-placeholder">Employee<br>Photo</div>
    @endif
   
    <div class="employee-details">
      <table>
        <tr><td class="label">Employee Name</td><td class="colon">:</td><td class="value">[Full Name]</td></tr>
        <tr><td class="label">Employee ID</td><td class="colon">:</td><td class="value">[EMP0000]</td></tr>
        <tr><td class="label">Mobile Number</td><td class="colon">:</td><td class="value">[000 000 0000]</td></tr>
        <tr><td class="label">Branch / Location</td><td class="colon">:</td><td class="value">[Location]</td></tr>
        <tr><td class="label">Department</td><td class="colon">:</td><td class="value">[Department]</td></tr>
      </table>
    </div>
  </div>

  <div class="section-header work">WORK DETAILS</div>
  <div class="work-grid">
    <div>
      <div class="wlabel">Total Working Days</div>
      <div class="wvalue">[00]</div>
    </div>
    <div>
      <div class="wlabel">Total Hours</div>
      <div class="wvalue">[000.00]</div>
    </div>
    <div>
      <div class="wlabel">Hourly Rate</div>
      <div class="wvalue">[0.00]</div>
    </div>
  </div>

  <div class="section-header earnings">EARNINGS</div>
  <table class="data-table">
    <tr>
      <th>Description</th>
      <th class="num">Hours / Qty</th>
      <th class="num">Rate</th>
      <th class="num">Amount</th>
    </tr>
    <tr>
      <td>Total Working Hours</td>
      <td class="num">[000.00]</td>
      <td class="num">[0.00]</td>
      <td class="num">[0.00]</td>
    </tr>
    <tr>
      <td>Allowance / Other</td>
      <td class="num">-</td>
      <td class="num">-</td>
      <td class="num">[0.00]</td>
    </tr>
    <tr class="total">
      <td colspan="3">Total Earnings</td>
      <td class="num">[0.00]</td>
    </tr>
  </table>

  <div class="section-header deductions">DEDUCTIONS & ADVANCE</div>
  <table class="data-table">
    <tr>
      <th>Description</th>
      <th class="num">Amount</th>
    </tr>
    <tr>
      <td>Advance Recovery</td>
      <td class="num">[0.00]</td>
    </tr>
    <tr>
      <td>Other Deduction</td>
      <td class="num">[0.00]</td>
    </tr>
    <tr class="total-deduction">
      <td>Total Deductions</td>
      <td class="num">[0.00]</td>
    </tr>
  </table>

  <div class="section-header final">FINAL PAYABLE AMOUNT</div>
  <div class="final-box">
    <div class="flabel">Final Payable</div>
    <div class="fvalue">[0.00]</div>
  </div>

  <div class="remarks">
    <strong>Remarks:</strong> [Salary for Month Year]
  </div>

  <div class="footer">
    <div class="sign-line">Prepared By</div>
    <div class="sign-line">Received By (Employee)</div>
    <div class="footer-right">
      <strong>Date:</strong> [DD Month YYYY]
      <div class="note">This is a computer generated payslip.</div>
    </div>
  </div>

</div>

</body>
</html>