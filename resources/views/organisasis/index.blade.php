@extends('app')
@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{session('success')}}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
</div>
@endif
<div class="text-end mb-2">
  <a class="btn btn-light" href="{{ route('exportpdf') }}"> Export</a>
  <a class="btn btn-success" href="{{ route('organisasis.create') }}"> Add Organisasi</a>
</div>
<table id="example" class="table table-striped" style="width:100%">
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">Nama Organisasi</th>
      <th scope="col">Ruangan</th>
      <th scope="col">Nama Kordinator</th>
      <th scope="col">Tanggal</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    @php $no = 1 @endphp
    @foreach ($organisasis as $data)
    <tr>
      <td>{{ $no ++ }}</td>
      <!-- <td>{{ $data->id }}</td> -->
      <td>{{ $data->nama_organisasi }}</td>
      <td>{{ $data->ruangan }}</td>
      <td>{{ $data->nama_kordinator }}</td>
      <td>{{ $data->tanggal }}</td>
      <td>
        <form action="{{ route('organisasis.destroy',$data->id) }}" method="Post">
          <a class="btn btn-primary" href="{{ route('organisasis.edit',$data->id) }}">Edit</a>
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection
@section('js')
<script>
  $(document).ready(function() {
    $('#example').DataTable();
  });
</script>
@endsection