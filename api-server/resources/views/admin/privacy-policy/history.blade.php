@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Privacy Policy History</h3>
                    
                    <div class="card-tools">
                        <a href="{{ route('admin.privacy-policy.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Privacy Policy
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    @if($privacyPolicyHistory->isEmpty())
                        <div class="alert alert-info">
                            <i class="icon fas fa-info"></i> No privacy policy history found.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Created At</th>
                                        <th>Status</th>
                                        <th>Content Preview</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($privacyPolicyHistory as $policy)
                                        <tr>
                                            <td>{{ $policy->id }}</td>
                                            <td>{{ $policy->title }}</td>
                                            <td>{{ $policy->created_at->format('F j, Y, g:i a') }}</td>
                                            <td>
                                                @if($policy->is_active)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm view-content-btn" data-toggle="modal" data-target="#contentModal{{ $policy->id }}">
                                                    <i class="fas fa-eye"></i> View Content
                                                </button>
                                                
                                                <!-- Content Modal -->
                                                <div class="modal fade" id="contentModal{{ $policy->id }}" tabindex="-1" role="dialog" aria-labelledby="contentModalLabel{{ $policy->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="contentModalLabel{{ $policy->id }}">{{ $policy->title }}</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="text-muted mb-3">
                                                                    <small>Created: {{ $policy->created_at->format('F j, Y, g:i a') }}</small>
                                                                </div>
                                                                <div class="border p-3 bg-light">
                                                                    {!! $policy->content !!}
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $privacyPolicyHistory->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 