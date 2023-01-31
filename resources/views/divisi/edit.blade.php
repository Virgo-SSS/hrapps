@extends('layouts.app')

@section('title') {{-- This Title location is at header --}}
<li><a href="{{ route('divisi.index') }}">Divisi</a></li>
<li><span>Edit</span></li>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <h4 class="header-title">Edit Divisi</h4>
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('divisi.update', $divisi) }}" method="POST">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $divisi->name }}" required>
                </div>
                <div class="form-group">
                    <label for="is_active" class="mr-2">Status</label>
                    <label class="switch">
                        <input type="hidden" name="is_active" id="is_active" value="{{ $divisi->is_active }}">
                        <input type="checkbox" id="status" onclick="changeValue(this)" {{ ($divisi->is_active) ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Submit</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function changeValue(e){
            if(e.checked){
                $('#is_active').val(1);
            }else{
                $('#is_active').val(0);
            }
        }
    </script>
@endsection
