@extends('layouts.admin.app')


@if(auth()->user()->role_status==4)

@extends('layouts.admin.app')

@section('content')

@include('hrm.dashboard.app_style')

@php
    $user       = auth()->user();
    $recorded   = session('recorded');            // set after Confirm
    $hasToday   = $todayTrips->count() > 0;
    $todayTotal = $todayTrips->sum('expense_amount');
    // which step opens first: 4 = success, 5 = today's list, 1 = select
    $startStep  = $recorded ? 4 : ($hasToday ? 5 : 1);
@endphp

<style>
    .tr-title{font-weight:700;color:#0d1b3e;text-align:center;margin:8px 0 2px}
    .tr-sub{text-align:center;color:#6b7488;margin-bottom:16px}
    .tr-step{display:none}
    .tr-step.active{display:block}
    .tr-user{display:flex;align-items:center;gap:12px;background:#fff;border-radius:14px;padding:12px 14px;box-shadow:0 2px 12px rgba(20,60,160,.07);margin-bottom:14px}
    .tr-user img{width:52px;height:52px;border-radius:50%;object-fit:cover}
    .tr-user b{display:block;color:#0d1b3e}
    .tr-user small{color:#6b7488}
    .tr-opt{display:flex;align-items:center;gap:14px;background:#fff;border:2px solid #e3e8f2;border-radius:14px;padding:16px;margin-bottom:12px;cursor:pointer;width:100%;text-align:left}
    .tr-opt i.main{font-size:22px;color:#1a5cff;width:30px;text-align:center}
    .tr-opt .name{flex:1;font-weight:600;color:#0d1b3e}
    .tr-opt .radio{width:22px;height:22px;border-radius:50%;border:2px solid #b8c1d4;display:flex;align-items:center;justify-content:center}
    .tr-opt.selected{border-color:#1a5cff;background:#f3f7ff}
    .tr-opt.selected .radio{border-color:#1a5cff}
    .tr-opt.selected .radio::after{content:"";width:12px;height:12px;border-radius:50%;background:#1a5cff}
    .tr-btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;border:0;border-radius:12px;padding:14px;font-weight:700;background:#1a5cff;color:#fff;font-size:16px}
    .tr-btn:disabled{opacity:.5}
    .tr-btn.outline{background:#fff;color:#1a5cff;border:2px solid #1a5cff}
    .tr-panel{background:#fff;border-radius:16px;padding:18px;box-shadow:0 2px 14px rgba(20,60,160,.07);margin-bottom:14px}
    .tr-field{margin-bottom:14px}
    .tr-field label{display:flex;align-items:center;gap:8px;color:#5b6578;font-size:14px;margin-bottom:6px}
    .tr-field label i{color:#1a5cff}
    .tr-trip{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid #edf0f6}
    .tr-trip:last-child{border-bottom:0}
    .tr-trip .ic{color:#1a5cff;font-size:22px;width:30px;text-align:center}
    .tr-trip .route{flex:1;color:#0d1b3e}
    .tr-trip .route small{display:block;color:#0d1b3e;font-weight:700}
    .tr-trip .tag{font-size:12px;font-style:italic;color:#6b7488}
    .tr-del{background:none;border:0;color:#e5202a;font-size:16px}
    .tr-total{display:flex;justify-content:space-between;align-items:center;background:#e6f7ec;border-radius:12px;padding:14px 16px;margin:14px 0}
    .tr-total span{color:#178a3a;font-weight:600}
    .tr-total b{font-size:32px;color:#178a3a}
    .tr-success{text-align:center}
    .tr-success .ok{width:70px;height:70px;border-radius:50%;background:#22a744;color:#fff;font-size:34px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px}
    .tr-success h5{color:#178a3a;font-weight:700}
    .tr-sum{display:flex;gap:12px;padding:10px 0;text-align:left;align-items:flex-start}
    .tr-sum i{color:#1a5cff;width:24px;text-align:center;margin-top:4px}
    .tr-sum small{display:block;color:#6b7488}
    .tr-sum b{color:#0d1b3e}
    .tr-back{background:none;border:0;color:#1a5cff;font-weight:600;margin-bottom:8px}
</style>

<div class="container py-4">
    <div class="app-container position-relative">

        @include('hrm.dashboard.header')

        <div class="checkin-card">

            @if ($errors->any())
                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
            @endif

            {{-- =========== STEP 1: Select transport =========== --}}
            <div class="tr-step" id="step-1">
                <div class="tr-user">
                    <img src="{{ $user->image ? asset($user->image) : asset('backend/images/avatar/default/user.jpg') }}" alt="">
                    <div><b>{{ $user->name }}</b><small>{{ $user->user_name }}</small></div>
                </div>

                <h4 class="tr-title">Select Transport for Today</h4>
                <p class="tr-sub">How did you travel today?</p>

                <button type="button" class="tr-opt" data-type="company">
                    <i class="fas fa-bus main"></i><span class="name">Company Bus</span><span class="radio"></span>
                </button>
                <button type="button" class="tr-opt" data-type="rta">
                    <i class="fas fa-bus-alt main"></i><span class="name">RTA Bus</span><span class="radio"></span>
                </button>
                <p class="text-center text-muted small mb-3" id="company-hint" style="display:none">No location or amount is needed.</p>

                <button type="button" class="tr-btn" id="btn-next" disabled>Next <i class="fas fa-arrow-right"></i></button>
            </div>

            {{-- =========== STEP 2: RTA details =========== --}}
            <div class="tr-step" id="step-2">
                <button type="button" class="tr-back" data-go="1"><i class="fas fa-chevron-left"></i> Back</button>
                <h4 class="tr-title">RTA Transport Details</h4>
                <p class="tr-sub">Add your trip</p>

                <div class="tr-panel">
                    <div class="tr-field">
                        <label><i class="fas fa-map-marker-alt"></i> From (Start Location)</label>
                        <select class="form-select" id="trip-from">
                            <option value="">Select location</option>
                            @foreach ($locations as $loc)
                                <option value="{{ $loc }}">{{ $loc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="tr-field">
                        <label><i class="fas fa-map-marker-alt" style="color:#e5202a"></i> To (Destination)</label>
                        <select class="form-select" id="trip-to">
                            <option value="">Select destination</option>
                            @foreach ($locations as $loc)
                                <option value="{{ $loc }}">{{ $loc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="tr-field mb-0">
                        <label><i class="fas fa-coins"></i> Amount (AED)</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="trip-amount" placeholder="0">
                    </div>
                </div>
                <div class="text-danger small mb-2" id="trip-error"></div>
                <button type="button" class="tr-btn" id="btn-add">Add to List</button>
            </div>

            {{-- =========== STEP 3: Today's trip list (before confirm) =========== --}}
            <div class="tr-step" id="step-3">
                <h4 class="tr-title">Today's Transport (RTA)</h4>
                <p class="tr-sub">Check your trips, then confirm</p>

                <div class="tr-panel" id="trip-list"></div>

                <button type="button" class="tr-btn outline mb-2" id="btn-another"><i class="fas fa-plus"></i> Add Another Trip</button>

                <div class="tr-total"><span>Total Amount (AED)</span><b id="trip-total">0</b></div>

                <form action="{{ route('admin.transport.store') }}" method="POST" id="transport-form">
                    @csrf
                    <input type="hidden" name="transport_type" id="f-type" value="rta">
                    <div id="f-trips"></div>
                    <button type="submit" class="tr-btn">Confirm Transport</button>
                </form>
            </div>

            {{-- =========== STEP 4: Recorded successfully =========== --}}
            <div class="tr-step" id="step-4">
                @if ($recorded)
                    <div class="tr-success">
                        <div class="ok"><i class="fas fa-check"></i></div>
                        <h5>Transport Recorded Successfully!</h5>
                    </div>
                    <div class="tr-panel mt-3">
                        <div class="tr-sum"><i class="fas fa-bus"></i><div><small>Transport Type</small><b>{{ $recorded['type'] }}</b></div></div>
                        <div class="tr-sum"><i class="fas fa-map-marker-alt" style="color:#e5202a"></i><div><small>Route</small><b>{!! nl2br(e($recorded['route'])) !!}</b></div></div>
                        <div class="tr-sum"><i class="fas fa-coins"></i><div><small>Amount</small><b>AED {{ rtrim(rtrim(number_format($recorded['amount'], 2), '0'), '.') }}</b></div></div>
                        <div class="tr-sum"><i class="fas fa-calendar-alt"></i><div><small>Date</small><b>{{ $recorded['date'] }}</b></div></div>
                    </div>
                    <button type="button" class="tr-btn" data-go="5">Done</button>
                @endif
            </div>

            {{-- =========== STEP 5: Today's transport (saved) =========== --}}
            <div class="tr-step" id="step-5">
                <h4 class="tr-title">Today's Transport</h4>
                <p class="tr-sub">{{ now()->format('j F Y') }}</p>

                <div class="tr-panel">
                    @forelse ($todayTrips as $i => $t)
                        @php
                            $tag = $i == 0 ? 'Morning' : ($i == 1 ? 'Evening' : 'Trip ' . ($i + 1));
                        @endphp
                        <div class="tr-trip">
                            <i class="fas fa-bus ic"></i>
                            <div class="route">
                                {{ $t->remarks ?: 'Transport' }}
                                <small>AED {{ rtrim(rtrim(number_format($t->expense_amount, 2), '0'), '.') }}</small>
                            </div>
                            <span class="tag">{{ $tag }}</span>
                        </div>
                    @empty
                        <p class="text-center text-muted mb-0">No transport recorded today.</p>
                    @endforelse
                </div>

                <div class="tr-total"><span>Total Transport (AED)</span><b>{{ rtrim(rtrim(number_format($todayTotal, 2), '0'), '.') ?: '0' }}</b></div>

                <button type="button" class="tr-btn outline" data-go="1"><i class="fas fa-plus"></i> Add Transport</button>
            </div>

        </div><br><br>

        @include('hrm.dashboard.bottom_navigation')
    </div>
</div>
@endsection

@push('js')
<script>
$(function () {
    let type = null;
    let trips = [];

    function go(n) {
        $('.tr-step').removeClass('active');
        $('#step-' + n).addClass('active');
        window.scrollTo({top: 0, behavior: 'smooth'});
    }
    $(document).on('click', '[data-go]', function () { go($(this).data('go')); });

    // ---- Step 1 ----
    $('.tr-opt').on('click', function () {
        $('.tr-opt').removeClass('selected');
        $(this).addClass('selected');
        type = $(this).data('type');
        $('#btn-next').prop('disabled', false);
        $('#company-hint').toggle(type === 'company');
    });

    $('#btn-next').on('click', function () {
        if (type === 'company') {
            // no location / amount needed -> save directly
            $('#f-type').val('company');
            $('#f-trips').empty();
            $('#transport-form').trigger('submit');
        } else {
            $('#f-type').val('rta');
            go(2);
        }
    });

    // ---- Step 2 ----
    $('#btn-add').on('click', function () {
        const from = $('#trip-from').val(), to = $('#trip-to').val(), amount = parseFloat($('#trip-amount').val());
        let err = '';
        if (!from || !to) err = 'Please select both start location and destination.';
        else if (from === to) err = 'Start and destination cannot be the same.';
        else if (isNaN(amount) || amount < 0) err = 'Please enter a valid amount.';
        $('#trip-error').text(err);
        if (err) return;

        trips.push({from, to, amount});
        $('#trip-from, #trip-to').val('');
        $('#trip-amount').val('');
        render();
        go(3);
    });

    // ---- Step 3 ----
    $('#btn-another').on('click', function () { go(2); });
    $(document).on('click', '.tr-del', function () {
        trips.splice($(this).data('i'), 1);
        render();
        if (!trips.length) go(2);
    });

    function fmt(n) { return (Math.round(n * 100) / 100).toString(); }
    function esc(s) { return $('<div>').text(s).html(); }

    function render() {
        let html = '', total = 0, hidden = '';
        trips.forEach((t, i) => {
            total += t.amount;
            html += '<div class="tr-trip"><i class="fas fa-bus ic"></i>' +
                    '<div class="route">' + esc(t.from) + ' → ' + esc(t.to) + '<small>AED ' + fmt(t.amount) + '</small></div>' +
                    '<button type="button" class="tr-del" data-i="' + i + '"><i class="fas fa-trash-alt"></i></button></div>';
            hidden += '<input type="hidden" name="trips[' + i + '][from]" value="' + esc(t.from) + '">' +
                      '<input type="hidden" name="trips[' + i + '][to]" value="' + esc(t.to) + '">' +
                      '<input type="hidden" name="trips[' + i + '][amount]" value="' + t.amount + '">';
        });
        $('#trip-list').html(html);
        $('#trip-total').text(fmt(total));
        $('#f-trips').html(hidden);
    }

    // prevent double submit
    $('#transport-form').on('submit', function () { $(this).find('button[type=submit]').prop('disabled', true); });

    go({{ $startStep }});
});
</script>
@endpush

@endif