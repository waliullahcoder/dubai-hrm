@extends('layouts.frontend.app')
@section('content')
    <div class="pt-5">
        <div class="container">
            <h2 class="h2 mb-2 text-uppercase text-center">{{ $contact->heading }}</h2>
            <p class="mb-4 text-center">
                {!! $contact->title !!}
            </p>
            <form action="{{ route('frontend.client-message.store') }}" method="POST" class="w-full p-6" id="contactform">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product_id }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" placeholder="Your name...." class="form-control rounded-0" name="name">
                    </div>
                    <div class="col-md-6">
                        <input type="text" placeholder="E-mail...." name="email" class="form-control rounded-0">
                    </div>
                    <div class="col-md-6">
                        <input type="text" placeholder="Your phone...." name="phone"
                            class="md:w-[34%] form-control rounded-0">
                    </div>
                    <div class="col-md-6">
                        <input type="text" placeholder="Address...." name="address"
                            class="md:w-[66%] form-control rounded-0">
                    </div>
                    <div class="col-12">
                        <textarea type="text" placeholder="Write Here...." name="message" class="form-control rounded-0" rows="4"></textarea>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="text-uppercase btn btn-primary">
                            send us message <i class="fa fa-long-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </form>

            <div class="py-5">
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <h2 class="h5 py-2 text-uppercase">ADDRESS</h2>
                        <div class="pt-1">
                            {!! $contact->address !!}
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <h2 class="h5 py-2 text-uppercase">WORK HOURS</h2>
                        <div class="pt-1">
                            {!! $contact->work_time !!}
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <h2 class="h5 py-2 text-uppercase">Phone</h2>
                        <div class="pt-1">
                            {{ $contact->primary_mobile }} <br>
                            International Orders & WhatsApp: <br>
                            {{ $contact->secondary_mobile }}
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <h2 class="h5 py-2 text-uppercase">E-MAIL</h2>
                        <div class="pt-1">
                            {{ $contact->primary_email }} <br>
                            {{ $contact->primary_email }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex map-wrapper">
            <iframe src="{{ $contact->map_url }}" width="100%" frameborder="0" style="border:0"
                allowfullscreen=""></iframe>
        </div>
    </div>
@endsection
