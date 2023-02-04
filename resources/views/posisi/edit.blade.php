@extends('layouts.app')

@section('title') {{-- This Title location is at header --}}
<li><a href="{{ route('posisi.index') }}">Posisi</a></li>
<li><span>Edit</span></li>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <h4 class="header-title">Edit Posisi</h4>
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('posisi.update', $posisi) }}" method="POST">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $posisi->name }}" required>
                </div>
                <div class="form-group">
                    <label for="divisi_id">Divisi</label>
                    <select name="divisi_id" id="divisi_id" class="form-control mb-4">
                        <option value="" disabled selected>Select Divisi</option>
                        @foreach($divisis as $divisi)
                            <option value="{{ $divisi->id }}" @selected($divisi->id == $posisi->divisi_id) >{{ ucfirst($divisi->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="is_active" class="mr-2">Status</label>
                    <label class="switch">
                        <input type="hidden" name="is_active" id="is_active" value="{{ $posisi->is_active }}">
                        <input type="checkbox" id="status" onclick="changeValue(this)" @checked($posisi->is_active)>
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
