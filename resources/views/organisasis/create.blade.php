@extends('app')
@section('content')

    <form action="{{ route('organisasis.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Create Organisasi</div>
                        <div class="card-body">
                            <div class="form-group">
                                <strong>Nama Organisasi:</strong>
                                <input type="text" name="nama_organisasi" class="form-control" placeholder="Nama Organisasi">
                                @error('nama_organisasi')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Ruangan:</strong>
                                    <input type="text" name="ruangan" class="form-control" placeholder="Ruangan">
                                    @error('ruangan')
                                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Nama Kordinator:</strong>
                                    <input type="text" name="nama_kordinator" class="form-control" placeholder="Nama Kordinator">
                                    @error('nama_kordinator')
                                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Hari:</strong>
                                    <input type="date" name="tanggal" class="form-control" placeholder="Tanggal">
                                    @error('tanggal')
                                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row col-xs-12 col-sm-12 col-md-12 mt-3">
                                <div class="col-md-10 form-group">
                                    <input type="text" name="search" id="search" class="form-control"
                                        placeholder="Masukkan Nama Mahasiswa">
                                    @error('search')
                                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2 form-group text-center">
                                    <button class="btn btn-secondary" type="button" name="btnAdd" id="btnAdd">
                                        <i class="fa fa-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 mt-3">
                                <table id="example" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">NIM</th>
                                            <th scope="col">Nama Mahasiswa</th>
                                            <th scope="col">Jabatan</th>
                                            <th scope="col">Jumlah Mahasiswa</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail">

                                    </tbody>
                                </table>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Jumlah Mahasiswa:</strong>
                                    <input type="text" name="jml" class="form-control" placeholder="Jumlah Mahasiswa">
                                    @error('jml')
                                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3 ml-3">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('js')
    <script type="text/javascript">
        var path = "{{ route('search.mahasiswa') }}";

        $("#search").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: path,
                    type: 'GET',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                $('#search').val(ui.item.label);
                console.log($("input[name=jml]").val());
                if ($("input[name=jml]").val() > 0) {
                    for (let i = 1; i <= $("input[name=jml]").val(); i++) {
                        id = $("input[name=id_mahasiswa" + i + "]").val();
                        if (id == ui.item.id) {
                            alert(ui.item.value + ' sudah ada!');
                            break;
                        } else {
                            add(ui.item.id);
                        }
                    }
                } else {
                    add(ui.item.id);
                }
                return false;
            }
        });

        function add(id) {
            const path = "{{ route('mahasiswa.index') }}/" + id;
            var html = "";
            var no = 0;
            if ($('#detail tr').length > no) {
                html = $('#detail').html();
                no = no + $('#detail tr').length;
            }
            $.ajax({
                url: path,
                type: 'GET',
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    no++;
                    html += '<tr>' +
                        '<td>' + no + '<input type="hidden" name="id' + no + '" class="form-control" value="' + data.id + '"></td>' +
                        '<td><input type="text" name="nim' + no + '" class="form-control" value="' + data.nim + '"></td>' +
                        '<td><input type="text" name="nama_mahasiswa' + no + '" class="form-control" value="' + data.nama_mahasiswa + '"></td>' +
                        '<td><input type="text" name="jabatan' + no + '" class="form-control"' + data.mata_kuliah + '"></td>' +
                        '<td><input type="text" name="jumlah_mahasiswa' + no + '" class="form-control"' + data.jumlah_mahasiswa + '"></td>' +
                        '<td><a href="#" class="btn btn-sm btn-danger">Delete</a></td>' +
                        '</tr>';
                    $('#detail').html(html);
                    $('input[name=jml]').val(no);
                }
            });
        }

    </script>
@endsection
