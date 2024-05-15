@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Prescription') }}

                    @if (session()->has('success'))
                    <div class="alert alert-success mt-4">{{ session('success') }}</div>
                    @endif

                </div>

                <div class="card-body">
                    <form action="{{ route('prescriptions.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group mt-2">
                            <label for="images">Prescription Images (max 5)</label>
                            <input type="file" name="images[]" id="images" class="form-control" multiple>
                            @error('images')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="form-group mt-2">
                            <label for="address">Delivery Address</label>
                            <input type="text" name="address" id="address" value="{{ old('address') }}" class="form-control">
                            @error('address')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-2">
                            <label for="delivery_time">Delivery Time</label>
                            <input type="time" name="delivery_time" id="delivery_time" class="form-control">
                            @error('delivery_time')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-2">
                            <label for="note">Note</label>
                            <textarea name="note" id="note" cols="30" rows="5" class="form-control"></textarea>
                            @error('note')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-2">
                            <button type="submit" class="btn btn-primary form-control">Submit Prescription</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection