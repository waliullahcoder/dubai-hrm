<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Salary Certificate Template</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: 'Georgia', 'Times New Roman', serif;
    background: #e9edf1;
    padding: 30px 10px;
  }
  .certificate {
    max-width: 850px;
    margin: 0 auto;
    background: #ffffff;
    padding: 50px 55px;
    border: 1px solid #ddd;
    position: relative;
  }
  .header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    border-bottom: 3px solid #14335e;
    padding-bottom: 20px;
    margin-bottom: 30px;
  }
  .company-name {
    font-size: 34px;
    font-weight: bold;
    color: #14335e;
  }
  .company-tagline {
    font-size: 13px;
    letter-spacing: 2px;
    color: #555;
    margin-top: 4px;
  }
  .contact-info {
    text-align: right;
    font-size: 13px;
    color: #333;
    font-family: Arial, sans-serif;
    line-height: 1.6;
  }
  h1.title {
    text-align: center;
    font-size: 42px;
    color: #14335e;
    letter-spacing: 4px;
    margin-bottom: 8px;
  }
  .divider {
    text-align: center;
    color: #c9a227;
    margin-bottom: 25px;
    font-size: 20px;
  }
  .date {
    font-weight: bold;
    margin-bottom: 20px;
    font-size: 15px;
  }
  h2.subtitle {
    text-align: center;
    color: #14335e;
    font-size: 22px;
    letter-spacing: 1px;
    margin-bottom: 20px;
  }
  p.intro {
    font-size: 16px;
    line-height: 1.6;
    margin-bottom: 25px;
  }
  .details-box {
    display: flex;
    border: 1px solid #ddd;
    margin-bottom: 30px;
  }
  .photo-box {
    width: 150px;
    min-width: 150px;
    padding: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-right: 1px solid #ddd;
  }
  .photo-placeholder {
    width: 120px;
    height: 150px;
    background: #f2f2f2;
    border: 1px dashed #aaa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    color: #999;
    text-align: center;
    font-family: Arial, sans-serif;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
    font-size: 15px;
  }
  table td {
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
  }
  table tr:last-child td {
    border-bottom: none;
  }
  td.label {
    background: #f4f8fb;
    width: 40%;
    color: #333;
  }
  td.colon {
    width: 20px;
    text-align: center;
    color: #999;
  }
  td.value {
    font-weight: 600;
    color: #14335e;
  }
  p.closing {
    font-size: 16px;
    line-height: 1.7;
    margin-bottom: 20px;
  }
  .signature-area {
    margin-top: 60px;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
  }
  .signature-line {
    border-top: 1px solid #333;
    width: 260px;
    padding-top: 8px;
    font-size: 14px;
  }
  .signature-line strong {
    display: block;
    font-size: 15px;
    color: #14335e;
  }
  .stamp-placeholder {
    width: 130px;
    height: 130px;
    border: 2px dashed #aaa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    font-size: 11px;
    color: #999;
    font-family: Arial, sans-serif;
    padding: 10px;
  }
  .bottom-stripe {
    height: 14px;
    background: linear-gradient(to right, #14335e 60%, #c9a227 60%);
    margin-top: 50px;
    border-radius: 3px;
  }

  @media print {
    body { background: #fff; padding: 0; }
    .certificate { border: none; }
    .action-bar { display: none !important; }
  }

  .action-bar {
    max-width: 850px;
    margin: 0 auto 15px auto;
    display: flex;
    justify-content: space-between;
    gap: 10px;
  }
  .action-bar button {
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
  }
  .btn-back {
    background: #eee;
    color: #333;
  }
  .btn-back:hover { background: #ddd; }
  .btn-print {
    background: #14335e;
    color: #fff;
  }
  .btn-print:hover { background: #0d2745; }
</style>
</head>
<body>

<div class="action-bar">
  <button class="btn-back" onclick="history.back()">&larr; Back</button>
  <button class="btn-print" onclick="window.print()">🖨️ Print / Save as PDF</button>
</div>

<div class="certificate">

  <div class="header">
    <div>
      <div class="company-name">Your Company Name</div>
      <div class="company-tagline">TAGLINE / DEPARTMENT</div>
    </div>
    <div class="contact-info">
      Office Address Line 1<br>
      City, Country<br>
      +000 0 000 0000<br>
      info@yourcompany.com
    </div>
  </div>

  <h1 class="title">SALARY CERTIFICATE</h1>
  <div class="divider">❖ ─────── ❖</div>

  <div class="date">Date: [DD Month YYYY]</div>

  <h2 class="subtitle">TO WHOM IT MAY CONCERN</h2>

  <p class="intro">
    This is to certify that the following employee is working with
    <strong>Your Company Name</strong> and the details of their employment
    and salary are as follows:
  </p>

  <div class="details-box">
    <div class="photo-box">
      <div class="photo-placeholder">Employee<br>Photo</div>
    </div>
    <table>
      <tr>
        <td class="label">Employee Name</td>
        <td class="colon">:</td>
        <td class="value">[Full Name]</td>
      </tr>
      <tr>
        <td class="label">Employee ID</td>
        <td class="colon">:</td>
        <td class="value">[EMP0000]</td>
      </tr>
      <tr>
        <td class="label">Designation</td>
        <td class="colon">:</td>
        <td class="value">[Job Title]</td>
      </tr>
      <tr>
        <td class="label">Department</td>
        <td class="colon">:</td>
        <td class="value">[Department]</td>
      </tr>
      <tr>
        <td class="label">Branch / Location</td>
        <td class="colon">:</td>
        <td class="value">[Location]</td>
      </tr>
      <tr>
        <td class="label">Date of Joining</td>
        <td class="colon">:</td>
        <td class="value">[DD Month YYYY]</td>
      </tr>
      <tr>
        <td class="label">Employment Status</td>
        <td class="colon">:</td>
        <td class="value">[Permanent / Contract]</td>
      </tr>
      <tr>
        <td class="label">Monthly Gross Salary</td>
        <td class="colon">:</td>
        <td class="value">[Currency] [Amount]<br><span style="font-weight:400; font-size:13px; color:#555;">([Amount in words])</span></td>
      </tr>
    </table>
  </div>

  <p class="closing">
    This certificate is issued upon the request of the employee for whatever purpose it may serve.
  </p>
  <p class="closing">
    We confirm that the above information is true and correct as per our records.
  </p>

  <div class="signature-area">
    <div class="signature-line">
      <strong>Authorized Signature</strong>
      HR Department<br>
      Your Company Name
    </div>
    <div class="stamp-placeholder">
      Company<br>Stamp / Seal
    </div>
  </div>

  <div class="bottom-stripe"></div>

</div>

</body>
</html>
