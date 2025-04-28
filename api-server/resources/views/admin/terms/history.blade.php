@extends('admin.layouts.app')

@section('title', 'Terms & Conditions History')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Terms & Conditions Version History</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.terms.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Terms
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($termsHistory) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Version</th>
                                        <th>Title</th>
                                        <th>Last Updated</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($termsHistory as $index => $term)
                                        <tr>
                                            <td>{{ $termsHistory->firstItem() + $index }}</td>
                                            <td>{{ $term->title }}</td>
                                            <td>{{ $term->last_updated->format('F j, Y') }}</td>
                                            <td>
                                                @if($term->is_active)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $term->created_at->format('F j, Y g:i A') }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info" 
                                                    data-toggle="modal" 
                                                    data-target="#viewModal{{ $term->id }}">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                        
                                        <!-- View Modal -->
                                        <div class="modal fade" id="viewModal{{ $term->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel{{ $term->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="viewModalLabel{{ $term->id }}">{{ $term->title }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Last Updated:</strong> {{ $term->last_updated->format('F j, Y') }}</p>
                                                        <p><strong>Created At:</strong> {{ $term->created_at->format('F j, Y g:i A') }}</p>
                                                        <hr>
                                                        <div class="border p-3 bg-light">
                                                            {!! $term->content !!}
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $termsHistory->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            No terms and conditions history found.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 