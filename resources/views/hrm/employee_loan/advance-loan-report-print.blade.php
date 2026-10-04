<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Salary Advance Request Form</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4;
            margin: 8mm;
        }
        @media print {
    /* body { background: #fff; padding: 0; }
    .payslip { border: none; } */
    .action-bar { display: none !important; }
  }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #eeeeee;
            color: #102d55;
            padding: 20px;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: auto;
            background: #ffffff;
            padding: 10mm;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.12);
        }

        /* =========================
           HEADER
        ========================= */

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 9px;
            border-bottom: 2px solid #102d55;
        }

        .logo-area {
            width: 53%;
        }

        .logo-script {
            font-family: "Brush Script MT", "Segoe Script", cursive;
            font-size: 64px;
            line-height: 55px;
            font-weight: bold;
            color: #0d2d59;
            font-style: italic;
        }

        .logo-sub {
            font-size: 23px;
            font-weight: 800;
            letter-spacing: 2px;
            margin-left: 108px;
            margin-top: 2px;
            color: #0d2d59;
        }

        .logo-org {
            font-size: 17px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #0d2d59;
            margin-top: 3px;
        }

        .company-info {
            width: 43%;
            padding-top: 3px;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 5px;
            font-size: 15px;
            color: #162d4d;
        }

        .info-icon {
            width: 27px;
            min-width: 27px;
            font-size: 17px;
            color: #123f72;
            text-align: center;
            margin-right: 5px;
        }

        /* =========================
           FORM TITLE
        ========================= */

        .title-row {
            display: flex;
            gap: 10px;
            margin-top: 13px;
            margin-bottom: 12px;
        }

        .form-title {
            flex: 1;
            background: linear-gradient(to right, #dceeff, #d7edfc);
            border-radius: 9px;
            padding: 14px 15px;
            text-align: center;
            color: #102d55;
            font-size: 28px;
            font-weight: 900;
            letter-spacing: .5px;
        }

        .form-meta {
            width: 190px;
            background: #e0effc;
            border-radius: 9px;
            padding: 10px 14px;
            font-size: 15px;
            line-height: 24px;
            color: #17365c;
        }

        .form-meta strong {
            color: #e21d25;
            font-size: 22px;
        }

        /* =========================
           SECTION
        ========================= */

        .section {
            margin-bottom: 11px;
            border: 1px solid #8ba4bd;
            border-radius: 8px;
            overflow: hidden;
        }

        .section-title {
            background: linear-gradient(to right, #07518e, #084f89);
            color: #ffffff;
            font-size: 17px;
            font-weight: bold;
            padding: 8px 14px;
            letter-spacing: .3px;
        }

        /* =========================
           EMPLOYEE TABLE
        ========================= */

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .employee-table td {
            border-right: 1px solid #9baebe;
            border-bottom: 1px solid #9baebe;
            padding: 9px 11px;
            font-size: 15px;
            height: 39px;
        }

        .employee-table tr:last-child td {
            border-bottom: 0;
        }

        .employee-table td:last-child {
            border-right: 0;
        }

        .label {
            width: 23%;
            font-weight: bold;
            color: #102f58;
            background: #f2f6f9;
        }

        .colon {
            width: 3%;
            text-align: center;
            font-weight: bold;
            background: #f2f6f9;
        }

        .value {
            width: 27%;
            color: #26384e;
        }

        .employee-table .wide-label {
            width: 23%;
        }

        .employee-table .wide-value {
            width: 77%;
        }

        /* =========================
           PREVIOUS ADVANCE
        ========================= */

        .advance-table th {
            background: #e0f0fc;
            color: #15365e;
            font-size: 14px;
            padding: 9px 5px;
            border-right: 1px solid #a0b1c1;
        }

        .advance-table td {
            text-align: center;
            padding: 11px 5px;
            font-size: 15px;
            border-right: 1px solid #a0b1c1;
            color: #142d50;
        }

        .advance-table th:last-child,
        .advance-table td:last-child {
            border-right: 0;
        }

        .advance-table td strong {
            font-size: 18px;
        }

        /* =========================
           NEW ADVANCE
        ========================= */

        .new-advance td {
            padding: 10px 14px;
            border-bottom: 1px solid #9caebe;
            font-size: 16px;
        }

        .new-advance tr:last-child td {
            border-bottom: 0;
        }

        .amount-label {
            width: 56%;
            color: #26384e;
        }

        .amount-value {
            width: 44%;
            text-align: center;
            background: #cfe7fa;
            color: #0d3767;
            font-size: 27px !important;
            font-weight: 900;
        }

        /* =========================
           STATEMENT
        ========================= */

        .statement {
            padding: 14px 17px;
            font-size: 15.5px;
            line-height: 25px;
            color: #172c48;
            min-height: 91px;
        }

        /* =========================
           APPROVAL
        ========================= */

        .approval-table th {
            background: #e0f0fc;
            color: #15365e;
            font-size: 16px;
            padding: 9px;
            border-right: 1px solid #9caebe;
        }

        .approval-table th:last-child {
            border-right: 0;
        }

        .signature-box {
            height: 164px;
            padding: 15px 18px;
            vertical-align: top;
            border-right: 1px solid #9caebe;
        }

        .signature-box:last-child {
            border-right: 0;
        }

        .signature-line {
            margin-top: 52px;
            border-top: 1.5px solid #1b2e45;
            width: 82%;
        }

        .signature-info {
            margin-top: 10px;
            font-size: 15px;
            line-height: 26px;
            color: #26384e;
        }

        .signature-info strong {
            color: #102d55;
        }

        /* =========================
           NOTE
        ========================= */

        .note {
            background: #e0f0fc;
            border-radius: 9px;
            padding: 10px 17px 11px;
            color: #173457;
            margin-top: 12px;
        }

        .note-title {
            font-weight: bold;
            font-size: 17px;
            margin-bottom: 4px;
        }

        .note ol {
            padding-left: 20px;
            margin: 0;
        }

        .note li {
            font-size: 13.5px;
            line-height: 20px;
        }

        /* =========================
           PRINT
        ========================= */

        @media print {

            body {
                background: #ffffff;
                padding: 0;
            }

            .page {
                width: 100%;
                min-height: auto;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }

            .section,
            .title-row,
            .note {
                break-inside: avoid;
            }

            .header {
                break-inside: avoid;
            }
        }

        @media screen and (max-width: 900px) {

            body {
                padding: 10px;
            }

            .page {
                width: 100%;
                min-height: auto;
            }

            .header {
                flex-direction: column;
            }

            .logo-area,
            .company-info {
                width: 100%;
            }

            .company-info {
                margin-top: 15px;
            }

            .title-row {
                flex-direction: column;
            }

            .form-meta {
                width: 100%;
            }
        }

    .action-bar {
    max-width: 793px;
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
   .btn-back { background: #5da54f; color: #ffffff; }
  .btn-back:hover { background: #81be74; }
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

<div class="page">

    <!-- ================= HEADER ================= -->

    <div class="header">

        <div class="logo-area">
            <!-- <div class="logo-script">Jannat</div> -->
            <!-- <div class="logo-sub"><img src="{{asset($admin_setting->logo)}}"></div> -->
             <h1>{{$admin_setting->title}}</h1>
            <img src="{{asset($admin_setting->logo)}}">
            
        </div>

        <div class="company-info">

            <div class="info-row">
                 
                
                <div class="info-icon">📍</div>
                <div>
                 
                    {{$setting->address}}
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon">☎</div>
                <div>{{$setting->primary_mobile}}, {{$setting->secondary_mobile}}</div>
            </div>

            <div class="info-row">
                <div class="info-icon">✉</div>
                <div>{{$setting->primary_email}}</div>
            </div>

            <div class="info-row">
                <div class="info-icon">🌐</div>
                <div>www.jannatparties.ae</div>
            </div>

        </div>

    </div>


    <!-- ================= TITLE ================= -->

    <div class="title-row">

        <div class="form-title">
             ADVANCE LOAN SUMMARY OF {{$staff->name}}
        </div>

        <div class="form-meta">
            <div>
                IDNO :
                <strong>{{$staff->code}}</strong>
            </div>

            <div>
                Date :
                {{date('M d, Y')}}
            </div>
        </div>

    </div>


    <!-- ================= 1. EMPLOYEE INFORMATION ================= -->

    <div class="section">

        <div class="section-title">
            1. EMPLOYEE INFORMATION
        </div>

        <table class="employee-table">

            <tr>
                <td class="label">Employee Name</td>
                <td class="colon">:</td>
                <td class="value">{{$staff->name}}</td>

                <td class="label">Employee ID</td>
                <td class="colon">:</td>
                <td class="value">{{$staff->code}}</td>
            </tr>

            <tr>
                <td class="label">NATIONAL ID</td>
                <td class="colon">:</td>
                <td class="value">{{$staff->national_id ?? 'NA'}}</td>

                <td class="label">Department</td>
                <td class="colon">:</td>
                <td class="value">{{$department->name?? 'NA'}}</td>
            </tr>

            <tr>
                <td class="label">Mobile Number</td>
                <td class="colon">:</td>
                <td class="value">{{$staff->phone}}</td>

                <td class="label">Hotel / Outlet</td>
                <td class="colon">:</td>
                <td class="value">{{$staff->hotel?->name}}</td>
            </tr>

            <tr>
                <td class="label wide-label">Address</td>
                <td class="colon">:</td>
                <td class="value wide-value" colspan="4">
                    {{$staff->address}}
                </td>
            </tr>

        </table>

    </div>


    <!-- ================= 2. PREVIOUS ADVANCE ================= -->

    <div class="section">

        <div class="section-title">
            2. PREVIOUS ADVANCE INFORMATION
        </div>

        <table class="advance-table">

            <thead>
                <tr>
                    <th>Last Advance Date</th>
                    <th>Last Advance Amount ({{$staff->currency_code}})</th>
                    <th>Total Recovered ({{$staff->currency_code}})</th>
                    <th>Outstanding Balance ({{$staff->currency_code}})</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>{{$loans->first()?->loan_date}}</td>
                    <td><strong>{{$loans->first()?->loan_amount+$advance_payments->first()?->payment_amount}}</strong></td>
                    <td><strong>{{$loans->sum('installment_amount')}}</strong></td>
                    <td><strong>{{$loans->sum('loan_amount')-$loans->sum('total_installments')}}</strong></td>
                </tr>
            </tbody>

        </table>

    </div>


    <!-- ================= 3. NEW ADVANCE ================= -->

    <div class="section">

        <div class="section-title">
            3. NEW ADVANCE REQUEST
        </div>

        <table class="new-advance">

            <tr>
                <td class="amount-label">
                    Total Advance Amount ({{$staff->currency_code}})
                </td>

                <td class="amount-value">
                    {{$loans->sum('loan_amount')}}
                </td>
            </tr>

            <tr>
                <td class="amount-label">
                    Total Outstanding After This Advance ({{$staff->currency_code}})
                </td>

                <td class="amount-value">
                    {{$loans->sum('loan_amount')+$advance_payments->sum('payment_amount')-$loans->sum('total_installments')}}
                </td>
            </tr>

        </table>

    </div>


    <!-- ================= 4. STATEMENT ================= -->

    <div class="section">

        <div class="section-title">
            4. ADVANCE REQUEST STATEMENT
        </div>

        <div class="statement">

            I, {{$staff->name}} ({{$staff->code}}), kindly request a salary advance of
            {{$staff->currency_code}} {{$loans->first()?->loan_amount}} due to my personal/financial
            necessity. I understand that this amount will be adjusted from
            my upcoming salary as per company policy.

        </div>

    </div>


    <!-- ================= 5. APPROVAL ================= -->

    <div class="section">

        <div class="section-title">
            5. APPROVALS
        </div>

        <table class="approval-table">

            <thead>
                <tr>
                    <th>Employee Signature</th>
                    <th>Admin Signature</th>
                </tr>
            </thead>

            <tbody>

                <tr>

                    <td class="signature-box">

                        <div class="signature-line"></div>

                        <div class="signature-info">
                            <strong>Name :</strong>
                            {{$staff->name}}
                            <br>

                            <strong>Date :</strong>
                            {{date('M d, Y')}}
                        </div>

                    </td>


                    <td class="signature-box">

                        <div class="signature-line"></div>

                        <div class="signature-info">
                            <strong>Name :</strong>
                            &nbsp;..................................
                            <br>

                            <strong>Date :</strong>
                            &nbsp;&nbsp;&nbsp;........................
                        </div>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>


    <!-- ================= NOTE ================= -->

    <div class="note">

        <div class="note-title">
            Note:
        </div>

        <ol>
            <li>
                This advance will be adjusted from the employee’s salary.
            </li>

            <li>
                Employee must follow company policy for advance repayment.
            </li>

            <li>
                This form is valid only with authorized signatures.
            </li>
        </ol>

    </div>

</div>

</body>
</html>