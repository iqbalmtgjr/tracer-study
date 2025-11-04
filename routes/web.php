<?php

use Illuminate\Support\Facades\Route;
// use App\Livewire\QuizForm;
// use App\Livewire\CheckBox;
// use App\Livewire\Grid;
use App\Livewire\Pilih;

Route::view('/', 'welcome')->name('home');
Route::get('isi-kuesioner', \App\Livewire\Kuesioner\Form::class)->name('kuesioner.form');
// Route::get('/kuesioner-tracer', QuizForm::class)->name('tracer.form');
// Route::get('/check-box', CheckBox::class)->name('checkbox.form');
// Route::get('/grid', Grid::class)->name('grid.form');
Route::get('/kuesioner-tracer', Pilih::class)
    ->middleware('tracer.verified')
    ->name('tracer.form');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Program Studi - hanya 1 route
        Route::get('program-studi', \App\Livewire\Admin\ProgramStudi\Index::class)->name('program-studi.index');

        // Route lainnya
        Route::get('alumni', \App\Livewire\Admin\Alumni\Index::class)->name('alumni.index');
        Route::get('kuesioner', \App\Livewire\Admin\Kuesioner\Index::class)->name('kuesioner.index');
        Route::get('laporan', \App\Livewire\Admin\Laporan\Index::class)->name('laporan.index');
        Route::get('pertanyaan/{kuesioner}', \App\Livewire\Admin\Pertanyaan\Index::class)->name('pertanyaan.index');
    });

    // Alumni Routes
    Route::middleware(['role:alumni'])->prefix('alumni')->name('alumni.')->group(function () {
        Route::get('profil', fn() => view('alumni.profil'))->name('profil');
        Route::get('kuesioner', fn() => view('alumni.kuesioner'))->name('kuesioner');
        Route::get('riwayat', fn() => view('alumni.riwayat'))->name('riwayat');
    });
});

require __DIR__ . '/auth.php';
