@extends('layouts.admin.create_app')

@section('content')
    <div class="row g-3">
        @if (Auth::user()->hasRole('Software Admin'))
            <div class="col-lg-4 col-sm-6">
                <label for="company_id" class="form-label"><b>Company Name <span class="text-danger">*</span></b></label>
                <select name="company_id" id="company_id" class="select form-select" data-placeholder="Select Company" required>
                    
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}"
                            {{ old('company_id') && old('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="col-lg-4 col-sm-6">
            <label for="branch_id" class="form-label"><b>Branch Name <span class="text-danger">*</span></b></label>
            <select name="branch_id" id="branch_id" class="select form-select" data-placeholder="Select Branch" required>
                
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}"
                        {{ old('branch_id') && old('branch_id') == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-4 col-sm-6">
            <label for="type" class="form-label"><b>Department <span class="text-danger">*</span></b></label>
            <div class="custom-select">
           <select name="department_id" id="department_id" class="select form-select" data-placeholder="Select Department" required>
                
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}"
                        {{ old('department_id') && old('department_id') == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}</option>
                @endforeach
            </select>            
        </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <label for="code" class="form-label"><b>Code <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control" id="code" name="code" required
                value="{{ old('code') }}" placeholder="code">
        </div>
        <div class="col-lg-4 col-sm-6">
            <label for="name" class="form-label"><b>Staff Name <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control" id="name" name="name" required
                value="{{ old('name') }}" placeholder="Staff Name">
        </div>
        <div class="col-lg-4 col-sm-6">
            <label for="short_name" class="form-label"><b><i class="fad fa-hotel"></i> Hotel Name <span class="text-danger">*</span></b></label>
            <select class="select form-select" id="hotel_id" name="hotel_id" required>
                @foreach($hotels as $hotel)
                  <option value="{{$hotel->id}}">{{$hotel->name}}, {{$hotel->address}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-4 col-sm-6">
            <label for="short_name" class="form-label"><b> Short Name <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control" id="short_name" name="short_name" required
                value="{{ old('short_name') }}" placeholder="Short Name">
        </div>
        <div class="col-lg-4 col-sm-6">
            <label for="designation" class="form-label"><b>Designation</b></label>
            <input type="text" class="form-control" id="designation" name="designation"
                value="{{ old('designation') }}" placeholder="Designation">
        </div>
        <div class="col-lg-4 col-sm-6">
            <label for="joining_date" class="form-label"><b>Joining Date <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control date_picker" id="joining_date" name="joining_date"
                required value="{{ date('d-m-Y', strtotime(old('joining_date'))) }}" placeholder="Joining Date">
        </div>
        <div class="col-lg-4 col-sm-6">
            <label for="email" class="form-label"><b>Email</b></label>
            <input type="email" class="form-control" id="email" name="email"
                value="{{ old('email') }}" placeholder="Email">
        </div>
        <div class="col-lg-4 col-sm-6">
            <label for="phone" class="form-label"><b>Phone No. <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control" id="phone" name="phone"
                value="{{ old('phone') }}" placeholder="Phone" required>
        </div>
        <div class="col-lg-4 col-sm-6">
            <label for="national_id" class="form-label"><b>National ID</b></label>
            <input type="number" class="form-control" id="national_id" name="national_id"
                value="{{ old('national_id') }}" placeholder="National ID">
        </div>
        <div class="col-lg-4 col-sm-6">
    <label for="currency_code" class="form-label">
        <b>Currency <span class="text-danger">*</span></b>
    </label>

    <select class="select form-select" id="currency_code" name="currency_code" required>
        <option value="">Select Currency</option>

        <option value="AED" {{ old('currency_code') == 'AED' ? 'selected' : '' }}>
            AED - United Arab Emirates Dirham
        </option>

        <option value="BDT" {{ old('currency_code') == 'BDT' ? 'selected' : '' }}>
            BDT - Bangladeshi Taka
        </option>

        <option value="USD" {{ old('currency_code') == 'USD' ? 'selected' : '' }}>
            USD - US Dollar
        </option>

        <option value="EUR" {{ old('currency_code') == 'EUR' ? 'selected' : '' }}>
            EUR - Euro
        </option>

        <option value="GBP" {{ old('currency_code') == 'GBP' ? 'selected' : '' }}>
            GBP - British Pound
        </option>

        <option value="SAR" {{ old('currency_code') == 'SAR' ? 'selected' : '' }}>
            SAR - Saudi Riyal
        </option>

        <option value="QAR" {{ old('currency_code') == 'QAR' ? 'selected' : '' }}>
            QAR - Qatari Riyal
        </option>

        <option value="KWD" {{ old('currency_code') == 'KWD' ? 'selected' : '' }}>
            KWD - Kuwaiti Dinar
        </option>

        <option value="OMR" {{ old('currency_code') == 'OMR' ? 'selected' : '' }}>
            OMR - Omani Rial
        </option>

        <option value="BHD" {{ old('currency_code') == 'BHD' ? 'selected' : '' }}>
            BHD - Bahraini Dinar
        </option>

        <option value="INR" {{ old('currency_code') == 'INR' ? 'selected' : '' }}>
            INR - Indian Rupee
        </option>

        <option value="PKR" {{ old('currency_code') == 'PKR' ? 'selected' : '' }}>
            PKR - Pakistani Rupee
        </option>

        <option value="NPR" {{ old('currency_code') == 'NPR' ? 'selected' : '' }}>
            NPR - Nepalese Rupee
        </option>

        <option value="LKR" {{ old('currency_code') == 'LKR' ? 'selected' : '' }}>
            LKR - Sri Lankan Rupee
        </option>

        <option value="MYR" {{ old('currency_code') == 'MYR' ? 'selected' : '' }}>
            MYR - Malaysian Ringgit
        </option>

        <option value="SGD" {{ old('currency_code') == 'SGD' ? 'selected' : '' }}>
            SGD - Singapore Dollar
        </option>

        <option value="THB" {{ old('currency_code') == 'THB' ? 'selected' : '' }}>
            THB - Thai Baht
        </option>

        <option value="CNY" {{ old('currency_code') == 'CNY' ? 'selected' : '' }}>
            CNY - Chinese Yuan
        </option>

        <option value="JPY" {{ old('currency_code') == 'JPY' ? 'selected' : '' }}>
            JPY - Japanese Yen
        </option>

        <option value="KRW" {{ old('currency_code') == 'KRW' ? 'selected' : '' }}>
            KRW - South Korean Won
        </option>

        <option value="AUD" {{ old('currency_code') == 'AUD' ? 'selected' : '' }}>
            AUD - Australian Dollar
        </option>

        <option value="CAD" {{ old('currency_code') == 'CAD' ? 'selected' : '' }}>
            CAD - Canadian Dollar
        </option>

        <option value="NZD" {{ old('currency_code') == 'NZD' ? 'selected' : '' }}>
            NZD - New Zealand Dollar
        </option>

        <option value="CHF" {{ old('currency_code') == 'CHF' ? 'selected' : '' }}>
            CHF - Swiss Franc
        </option>

        <option value="RUB" {{ old('currency_code') == 'RUB' ? 'selected' : '' }}>
            RUB - Russian Ruble
        </option>

        <option value="TRY" {{ old('currency_code') == 'TRY' ? 'selected' : '' }}>
            TRY - Turkish Lira
        </option>

        <option value="ZAR" {{ old('currency_code') == 'ZAR' ? 'selected' : '' }}>
            ZAR - South African Rand
        </option>

        <option value="BRL" {{ old('currency_code') == 'BRL' ? 'selected' : '' }}>
            BRL - Brazilian Real
        </option>

        <option value="MXN" {{ old('currency_code') == 'MXN' ? 'selected' : '' }}>
            MXN - Mexican Peso
        </option>

        <option value="IDR" {{ old('currency_code') == 'IDR' ? 'selected' : '' }}>
            IDR - Indonesian Rupiah
        </option>

        <option value="VND" {{ old('currency_code') == 'VND' ? 'selected' : '' }}>
            VND - Vietnamese Dong
        </option>

        <option value="PHP" {{ old('currency_code') == 'PHP' ? 'selected' : '' }}>
            PHP - Philippine Peso
        </option>

        <option value="HKD" {{ old('currency_code') == 'HKD' ? 'selected' : '' }}>
            HKD - Hong Kong Dollar
        </option>

        <option value="TWD" {{ old('currency_code') == 'TWD' ? 'selected' : '' }}>
            TWD - New Taiwan Dollar
        </option>

        <option value="SEK" {{ old('currency_code') == 'SEK' ? 'selected' : '' }}>
            SEK - Swedish Krona
        </option>

        <option value="NOK" {{ old('currency_code') == 'NOK' ? 'selected' : '' }}>
            NOK - Norwegian Krone
        </option>

        <option value="DKK" {{ old('currency_code') == 'DKK' ? 'selected' : '' }}>
            DKK - Danish Krone
        </option>

        <option value="PLN" {{ old('currency_code') == 'PLN' ? 'selected' : '' }}>
            PLN - Polish Zloty
        </option>

        <option value="CZK" {{ old('currency_code') == 'CZK' ? 'selected' : '' }}>
            CZK - Czech Koruna
        </option>

        <option value="ILS" {{ old('currency_code') == 'ILS' ? 'selected' : '' }}>
            ILS - Israeli New Shekel
        </option>

        <option value="EGP" {{ old('currency_code') == 'EGP' ? 'selected' : '' }}>
            EGP - Egyptian Pound
        </option>

        <option value="NGN" {{ old('currency_code') == 'NGN' ? 'selected' : '' }}>
            NGN - Nigerian Naira
        </option>

        <option value="KES" {{ old('currency_code') == 'KES' ? 'selected' : '' }}>
            KES - Kenyan Shilling
        </option>

        <option value="GHS" {{ old('currency_code') == 'GHS' ? 'selected' : '' }}>
            GHS - Ghanaian Cedi
        </option>
    </select>
</div>
        <div class="col-lg-4 col-sm-6">
            <label for="ac_no" class="form-label"><b>A/C No.</b></label>
            <input type="text" class="form-control" id="ac_no" name="ac_no"
                value="{{ old('ac_no') }}" placeholder="A/C No.">
        </div>
        <div class="col-lg-4 col-sm-6">
            <label for="ac_branch" class="form-label"><b>A/C Branch</b></label>
            <input type="text" class="form-control" id="ac_branch" name="ac_branch"
                value="{{ old('ac_branch') }}" placeholder="A/C Branch">
        </div>
        <div class="col-lg-4 col-sm-6">
            <label for="address" class="form-label"><b>Address</b></label>
            <input type="text" name="address" id="address" class="form-control" placeholder="Address"
                value="{{ old('address') }}">
        </div>

        <!-- Salary Structure -->
        <div class="col-lg-2 col-sm-3">
            <label for="basic_salary" class="form-label"><b>Basic Salary / Hour Rate</b></label>
            <input type="number" class="form-control" id="basic_salary" name="basic_salary"
                value="{{ old('basic_salary') }}" placeholder="ex. 100">
        </div>
        <div class="col-lg-2 col-sm-3">
            <label for="house_rent" class="form-label"><b>House Rent</b></label>
            <input type="number" class="form-control" id="house_rent" name="house_rent"
                value="{{ old('house_rent') }}" placeholder="ex. 10000">
        </div>
        
        <div class="col-lg-2 col-sm-3">
            <label for="medical_allowance" class="form-label"><b>Medical Allowance</b></label>
            <input type="number" class="form-control" id="medical_allowance" name="medical_allowance"
                value="{{ old('medical_allowance') }}" placeholder="ex. 5000">
        </div>
        <div class="col-lg-2 col-sm-3">
            <label for="others" class="form-label"><b>Others</b></label>
            <input type="number" class="form-control" id="others" name="others"
                value="{{ old('others') }}" placeholder="ex. 1000">
        </div>
        <div class="col-lg-2 col-sm-3">
            <label for="deducted" class="form-label"><b>Provident / Deducted</b></label>
            <input type="number" class="form-control" id="deducted" name="deducted"
                value="{{ old('deducted') }}" placeholder="ex. 1000">
        </div>
        
        <div class="col-lg-2 col-sm-3">
            <label for="increment_percent" class="form-label"><b>Increment %</b></label>
            <input type="number" class="form-control" id="increment_percent" name="increment_percent"
                value="{{ old('increment_percent') }}" placeholder="ex. 1000">
        </div>
        <div class="col-lg-2 col-sm-3">
            <label for="increment_amount" class="form-label"><b>Increment Amount</b></label>
            <input type="number" class="form-control" id="increment_amount" name="increment_amount"
                value="{{ old('increment_amount') }}" placeholder="ex. 1000">
        </div>
        <div class="col-lg-2 col-sm-3">
            <label for="total_salary" class="form-label"><b>Total Salary</b></label>
            <input type="number" class="form-control" id="total_salary" name="total_salary"
                value="" readonly>
        </div>
        
        
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $(".date_picker").datepicker({
                format: 'dd-mm-yyyy',
                changeMonth: true,
                changeYear: true,
            }).datepicker('setDate', 'today');

            $(document).on('change', '#company_id', function(e) {
                let company_id = $(this).val();
                let url = "{{ Route('admin.staff.create') }}";
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _method: 'GET',
                        company_id: company_id,
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#branch_id option').remove();
                            $('#branch_id').append('');
                            $.each(response.branches, function(key, value) {
                                var html = '<option value="' + value.id + '">' + value
                                    .name + '</option>';
                                $('#branch_id').append(html);
                            });
                        }
                    }
                });
            });
        });

        // salary structure

         $(document).ready(function () {

                function num(id) {
                    return parseFloat($(id).val()) || 0;
                }

                function calculateSalary() {

                    let basic      = num('#basic_salary');
                    let houseRent  = num('#house_rent');
                    let medical    = num('#medical_allowance');
                    let others     = num('#others');
                    let deducted   = num('#deducted');
                    let incrementP = num('#increment_percent');

                    // Increment Amount
                    let incrementAmount = (basic * incrementP) / 100;
                    $('#increment_amount').val(incrementAmount.toFixed(2));

                    // Total Salary
                    let totalSalary =
                        basic +
                        houseRent +
                        medical +
                        others +
                        incrementAmount -
                        deducted;

                    $('#total_salary').val(totalSalary.toFixed(2));
                }

                $(document).on('keyup change', '#basic_salary, #house_rent, #medical_allowance, #others, #deducted, #increment_percent', function () {
                    calculateSalary();
                });

                calculateSalary();
            });


    </script>
@endpush
