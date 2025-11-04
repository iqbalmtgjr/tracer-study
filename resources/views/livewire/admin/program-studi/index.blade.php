<div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Program Studi</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Program Studi</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive shadow-sm rounded">
                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Nama Program Studi</th>
                                            <th>Kode Prodi</th>
                                            <th class="text-center">Jumlah Alumni</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($programStudi as $index => $prodi)
                                        <tr>
                                            <td class="text-center">{{ 1 + $index }}</td>
                                            <td>{{ $prodi->program_studi }}</td>
                                            <td>{{ $prodi->kode_program_studi }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-primary">{{ $prodi->alumni->count() }}</span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                <i class="fas fa-exclamation-circle mr-2"></i> Tidak ada data Program Studi ditemukan.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>