@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Privacy Policy Management</h3>
                    
                    <div class="card-tools">
                        <a href="{{ route('admin.privacy-policy.edit') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit Privacy Policy
                        </a>
                        <a href="{{ route('admin.privacy-policy.history') }}" class="btn btn-info btn-sm ml-2">
                            <i class="fas fa-history"></i> View History
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-check"></i> Success!</h5>
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($activePrivacyPolicies->isNotEmpty())
                        @foreach($activePrivacyPolicies as $policy)
                            <div class="privacy-policy-container">
                                <h4 class="mb-3">{{ $policy->title }}</h4>
                                
                                <div class="text-muted mb-3">
                                    <small>Last updated: {{ $policy->updated_at->format('F j, Y, g:i a') }}</small>
                                </div>
                                
                                <div class="privacy-policy-content border p-3 bg-light">
                                    {!! $policy->content !!}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            <i class="icon fas fa-info"></i> No active privacy policy found.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 