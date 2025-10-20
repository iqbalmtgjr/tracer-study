@extends('layouts.admin.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @role('admin')
                <!-- Info boxes -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ \App\Models\Alumni::count() }}</h3>
                                <p>Total Alumni</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <a href="{{ route('admin.alumni.index') }}" class="small-box-footer">
                                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ \App\Models\ProgramStudi::count() }}</h3>
                                <p>Program Studi</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <a href="{{ route('admin.program-studi.index') }}" class="small-box-footer">
                                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ \App\Models\Kuesioner::active()->count() }}</h3>
                                <p>Kuesioner Aktif</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <a href="{{ route('admin.kuesioner.index') }}" class="small-box-footer">
                                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ \App\Models\Responden::where('status', 'selesai')->count() }}</h3>
                                <p>Total Responden</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <a href="{{ route('admin.laporan.index') }}" class="small-box-footer">
                                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endrole

            @role('alumni')
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Selamat Datang, {{ Auth::user()->name }}</h3>
                            </div>
                            <div class="card-body">
                                <p>Silakan lengkapi profil Anda dan isi kuesioner yang tersedia.</p>
                                <a href="{{ route('alumni.kuesioner') }}" class="btn btn-primary">
                                    <i class="fas fa-clipboard-list"></i> Isi Kuesioner
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endrole
        </div>
    </section>
@endsection
