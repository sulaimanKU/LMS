@extends('applayouts.app')

@section('contents')
    <div class="container mt-3">
        <div class=" border-0 rounded-4 shadow-lg">

            <form action="{{ route('permission.update',$permission->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-4">Update Permission</h5>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-2">permission Name</label>
                        <input type="text" name="name" value="{{$permission->name}}" class="form-control bg-light border-0 py-3" placeholder="Enter role name..." required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary py-3 rounded-3 fw-bold">Update permission</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection
