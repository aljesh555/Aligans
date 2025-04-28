@extends('admin.layouts.app')

@section('title', 'Terms & Conditions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Terms & Conditions Management</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.terms.edit') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit Terms
                        </a>
                        <a href="{{ route('admin.terms.history') }}" class="btn btn-info btn-sm ml-2">
                            <i class="fas fa-history"></i> View History
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <h4>Current Active Terms</h4>
                        <p>Last Updated: <strong>{{ $activeTerms->last_updated->format('F j, Y') }}</strong></p>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5>{{ $activeTerms->title }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="border p-3 bg-light">
                                {!! $activeTerms->content !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 