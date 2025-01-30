@extends('layout');
@section('main-content')
    <a href="{{ route('create') }}" class="btn btn-primary">Add User</a>
    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importModal">Import</a>
    <a href="{{ route('export') }}" class="btn btn-primary">Export</a>
    <table class="table">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Mobile</th>
                <th scope="col">Role</th>
                <th scope="col">Password</th>
                <th scope="col">Image</th>
                <th scope="col">Date</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($data))
                @foreach ($data as $key => $value)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $value['name'] ?? '' }}</td>
                        <td>{{ $value['email'] ?? '' }}</td>
                        <td>{{ $value['mobile'] ?? '' }}</td>
                        <td>{{ $value['role'] ?? '' }}</td>
                        <td>{{ $value['password'] ?? '' }}</td>
                        <td>
                            <img src="{{ asset('storage')}}/{{ isset($value['image']) ? $value['image'] : ''  }}"  width="100px"
                                height="100px">
                        </td>
                        <td>{{ date('Y-M-d', strtotime($value['date'])) ?? '' }}</td>
                        <td>
                            <a href="{{ route('edit', ['id' => $key]) }}" class="btn btn-primary">Edit</a>
                            <a href="{{ route('delete', ['id' => $key]) }}" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    @if (!empty($data))
        <a href="{{ route('finalSubmit') }}" class="btn btn-primary">Final Submit</a>
    @endif

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Users</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Import Form -->
                    <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Choose Excel File</label>
                            <input type="file" name="file" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
