@extends('layouts.admin.edit_app')

@section('content')
    <div class="row g-3">
        @if (Auth::user()->hasRole('Software Admin'))
           <div class="col-lg-6 col-sm-6">
                <label for="parent_id" class="form-label"><b>Hotel Name <span class="text-danger">*</span></b></label>
                <select name="parent_id" id="parent_id" class="select form-select" data-placeholder="Select Company" required>
                    <option value=""></option>
                    @foreach ($hotels as $hotel)
                        <option value="{{ $hotel->id }}" {{ $data->parent_id == $hotel->id ? 'selected' : '' }}>
                            {{ $hotel->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
        <!-- <div class="col-lg-4 col-sm-6">
            <label for="parent_id" class="form-label"><b>Parent Category</b></label>
            <select name="parent_id" id="parent_id" class="select form-select" data-placeholder="Select Parent..">
                <option value=""></option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $data->parent_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @foreach ($category->children as $child)
                        <option value="{{ $child->id }}" {{ $data->parent_id == $child->id ? 'selected' : '' }}>
                            {{ $child->name }}
                        </option>
                    @endforeach
                @endforeach
            </select>
        </div> -->
        <div class="col-lg-6 col-sm-6">
            <label for="name" class="form-label"><b>Name <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control" id="name" name="name" required placeholder="Department Name"
                value="{{ $data->name }}">
        </div>
        <!-- <div class="col-lg-4 col-sm-6">
            <label for="image" class="form-label"><b>Image <span class="text-danger">(500x500)</span></b></label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
            @if (file_exists($data->image))
                <div class="pt-2">
                    <img src="{{ asset($data->image) }}" height="40" alt="">
                </div>
            @endif
        </div>
        <div class="col-lg-4 col-sm-6">
            <label for="vendor_id" class="form-label"><b>Vendor Names</b></label>
            <select name="vendor_id[]" id="vendor_id" class="select form-select" multiple
                data-placeholder="Select Vendors..">
                @foreach ($vendors as $vendor)
                    <option value="{{ $vendor->id }}" {{ in_array($vendor->id, $selected_vendors) ? 'selected' : '' }}>
                        {{ $vendor->name }}
                    </option>
                @endforeach
            </select>
        </div> -->
        <!-- <div class="col-lg-4 col-sm-6">
            <label for="meta_title" class="form-label"><b>Meta Title</b></label>
            <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Meta Title"
                value="{{ $data->meta_title }}">
        </div>
        <div class="col-lg-4 col-sm-6">
            <label for="meta_keyword" class="form-label"><b>Meta Keyword</b></label>
            <input type="text" class="form-control" id="meta_keyword" name="meta_keyword" placeholder="Meta Keyword"
                value="{{ $data->meta_keyword }}">
        </div> -->
       
        <div class="col-12">
            <label for="meta_description" class="form-label"><b>Meta Description</b></label>
            <textarea name="meta_description" id="meta_description" cols="30" rows="4" class="form-control"
                placeholder="Meta Description">{{ $data->meta_description }}</textarea>
        </div>
    </div>
@endsection
