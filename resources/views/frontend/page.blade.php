@extends('layouts.frontend.app')
@section('content')
    <div class="py-4">
        <div class="container">
            <h1 class="h1">{{ $page->sub_title }}</h1>
            <div>
                {!! $page->description !!}
            </div>
        </div>
    </div>
@endsection
