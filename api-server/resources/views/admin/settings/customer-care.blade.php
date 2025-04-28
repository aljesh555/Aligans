@extends('admin.layouts.app')

@section('title', 'Customer Care Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Customer Care Settings</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.settings.customer-care.update') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="timings">Working Hours <span class="text-danger">*</span></label>
                            <input type="text" name="timings" id="timings" class="form-control @error('timings') is-invalid @enderror" 
                                value="{{ old('timings', $customerCare['timings']) }}" 
                                placeholder="e.g. Sun-Fri: 9:00 AM - 6:00 PM" required>
                            @error('timings')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Enter your business working hours. This will be displayed in the footer.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                                value="{{ old('phone', $customerCare['phone']) }}" 
                                placeholder="e.g. +977-1-4444444">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="whatsapp">WhatsApp Number</label>
                            <input type="text" name="whatsapp" id="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" 
                                value="{{ old('whatsapp', $customerCare['whatsapp']) }}" 
                                placeholder="e.g. +977-9800000000">
                            @error('whatsapp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">This number will be used for WhatsApp chat link in the footer.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="viber">Viber Number</label>
                            <input type="text" name="viber" id="viber" class="form-control @error('viber') is-invalid @enderror" 
                                value="{{ old('viber', $customerCare['viber']) }}" 
                                placeholder="e.g. +977-9800000000">
                            @error('viber')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                value="{{ old('email', $customerCare['email']) }}" 
                                placeholder="e.g. care@yourstore.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" 
                                rows="2" placeholder="e.g. Kathmandu, Nepal">{{ old('address', $customerCare['address']) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 