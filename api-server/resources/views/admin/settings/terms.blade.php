@extends('admin.layouts.app')

@section('title', 'Terms & Conditions')

@section('styles')
<!-- Include CKEditor styles if needed -->
<style>
    .ck-editor__editable {
        min-height: 400px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Terms & Conditions Settings</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.settings.terms.update') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                                value="{{ old('title', $terms['title']) }}" 
                                placeholder="e.g. Terms & Conditions" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="content">Content <span class="text-danger">*</span></label>
                            <textarea name="content" id="editor" class="form-control @error('content') is-invalid @enderror" 
                                rows="15" required>{{ old('content', $terms['content']) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Enter the content for your Terms & Conditions page. You can use the editor to format the text.</small>
                        </div>
                        
                        <div class="form-group">
                            <p>Last Updated: <strong>{{ $terms['last_updated'] }}</strong></p>
                            <p class="text-muted">The "Last Updated" date will be automatically set to today's date when you save changes.</p>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                Save Changes
                            </button>
                            <a href="{{ url('/terms') }}" target="_blank" class="btn btn-outline-info ml-2">
                                <i class="fas fa-external-link-alt mr-1"></i> View Terms Page
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Include CKEditor -->
<script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });
</script>
@endsection 