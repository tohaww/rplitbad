<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CourseController as StudentCourseController;
use App\Http\Controllers\Profile\DocumentController;
use App\Http\Controllers\Profile\IdentityController;
use App\Http\Controllers\Profile\KegiatanIlmiahController;
use App\Http\Controllers\Profile\PelatihanProfesionalController;
use App\Http\Controllers\Profile\PenghargaanPiagamController;
use App\Http\Controllers\Profile\RiwayatPendidikanController;
use App\Http\Controllers\Profile\RiwayatPekerjaanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/symlink', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        return 'Symlink created successfully.';
    } catch (\Exception $e) {
        return 'Error creating symlink: ' . $e->getMessage();
    }
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::post('/register/check-kode-referensi', [RegisterController::class, 'checkKodeReferensi'])->name('register.check-kode-referensi');
});

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ProgramStudiController;
use App\Http\Controllers\Admin\PengajuanDashboardController;
use App\Http\Controllers\Admin\SemuaPengajuanController;
use App\Http\Controllers\Admin\KodeReferensiController;
use App\Http\Controllers\Admin\AsalPerguruanTinggiController;
use App\Http\Controllers\Admin\AssesorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CourseSubmissionController;
use App\Http\Controllers\EvaluasiMahasiswaController;
use App\Http\Controllers\PerolehanKreditController;
use App\Http\Controllers\TransferKreditController;

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/profile', 'pages.profile')->name('profile');
    Route::get('/daftar-matkul-prodi', [StudentCourseController::class, 'index'])->name('courses');
    Route::get('/pengajuan-matkul', [CourseSubmissionController::class, 'index'])->name('course-submission');
    Route::get('/transfer-kredit/pengajuan', [TransferKreditController::class, 'create'])->name('transfer-kredit.create');
    Route::post('/transfer-kredit/pengajuan', [TransferKreditController::class, 'store'])->name('transfer-kredit.store');
    Route::post('/transfer-kredit/pengajuan/matkul', [TransferKreditController::class, 'storeMatkul'])->name('transfer-kredit.matkul.store');
    Route::delete('/transfer-kredit/pengajuan/matkul/{id}', [TransferKreditController::class, 'destroyMatkul'])->name('transfer-kredit.matkul.destroy');
    Route::post('/transfer-kredit/pengajuan/{id}/submit', [TransferKreditController::class, 'submit'])->name('transfer-kredit.submit');
    Route::delete('/transfer-kredit/pengajuan/{id}', [TransferKreditController::class, 'destroy'])->name('transfer-kredit.destroy');
    Route::get('/pengajuan-perolehan-kredit', [PerolehanKreditController::class, 'create'])->name('perolehan-kredit.create');
    Route::get('/pengajuan-perolehan-kredit/get-courses', [PerolehanKreditController::class, 'getCoursesByProdi'])->name('perolehan-kredit.get-courses');
    Route::post('/pengajuan-perolehan-kredit/draft', [PerolehanKreditController::class, 'storeDraft'])->name('perolehan-kredit.store-draft');
    Route::post('/pengajuan-perolehan-kredit/add-matkul', [PerolehanKreditController::class, 'addMataKuliah'])->name('perolehan-kredit.add-matkul');
    Route::post('/pengajuan-perolehan-kredit/remove-matkul', [PerolehanKreditController::class, 'removeMataKuliah'])->name('perolehan-kredit.remove-matkul');
    Route::post('/pengajuan-perolehan-kredit', [PerolehanKreditController::class, 'store'])->name('perolehan-kredit.store');
    Route::delete('/pengajuan-perolehan-kredit/{id}', [PerolehanKreditController::class, 'destroy'])->name('perolehan-kredit.destroy');
    // Halaman daftar evaluasi mahasiswa (menu "Form Evaluasi Diri")
    Route::get('/form-evaluasi-diri', [EvaluasiMahasiswaController::class, 'index'])->name('self-evaluation');
    Route::get('/evaluasi-mahasiswa', [EvaluasiMahasiswaController::class, 'index'])->name('evaluasi-mahasiswa');

    // Halaman form evaluasi diri per mata kuliah
    Route::get('/form-evaluasi-diri/{pengajuan}/{matkul}', [EvaluasiMahasiswaController::class, 'showForm'])->name('self-evaluation.form');
    Route::post('/form-evaluasi-diri', [EvaluasiMahasiswaController::class, 'store'])->name('self-evaluation.store');
    Route::view('/pengakuan-matkul', 'pages.course-recognition')->name('course-recognition');

    // Document routes (upload handled in profile page)
    Route::post('/upload-dokumen', [DocumentController::class, 'upload'])->name('documents.upload');
    Route::get('/download-dokumen/{type}', [DocumentController::class, 'download'])->name('documents.download');

    // Identity routes
    Route::get('/lengkapi-identitas', [IdentityController::class, 'create'])->name('identity.create');
    Route::post('/lengkapi-identitas', [IdentityController::class, 'store'])->name('identity.store');

    // Riwayat Pendidikan routes
    Route::get('/isi-riwayat-pendidikan', [RiwayatPendidikanController::class, 'create'])->name('riwayat-pendidikan.create');
    Route::post('/isi-riwayat-pendidikan', [RiwayatPendidikanController::class, 'store'])->name('riwayat-pendidikan.store');
    Route::put('/isi-riwayat-pendidikan/{id}', [RiwayatPendidikanController::class, 'update'])->name('riwayat-pendidikan.update');
    Route::delete('/isi-riwayat-pendidikan/{id}', [RiwayatPendidikanController::class, 'destroy'])->name('riwayat-pendidikan.destroy');
    Route::post('/isi-riwayat-pendidikan/{id}/upload', [RiwayatPendidikanController::class, 'upload'])->name('riwayat-pendidikan.upload');
    Route::get('/isi-riwayat-pendidikan/{id}/download', [RiwayatPendidikanController::class, 'download'])->name('riwayat-pendidikan.download');

    // Pelatihan Profesional routes
    Route::get('/isi-pelatihan-profesional', [PelatihanProfesionalController::class, 'create'])->name('pelatihan-profesional.create');
    Route::post('/isi-pelatihan-profesional', [PelatihanProfesionalController::class, 'store'])->name('pelatihan-profesional.store');
    Route::put('/isi-pelatihan-profesional/{id}', [PelatihanProfesionalController::class, 'update'])->name('pelatihan-profesional.update');
    Route::delete('/isi-pelatihan-profesional/{id}', [PelatihanProfesionalController::class, 'destroy'])->name('pelatihan-profesional.destroy');
    Route::post('/isi-pelatihan-profesional/{id}/upload', [PelatihanProfesionalController::class, 'upload'])->name('pelatihan-profesional.upload');
    Route::get('/isi-pelatihan-profesional/{id}/download', [PelatihanProfesionalController::class, 'download'])->name('pelatihan-profesional.download');

    // Kegiatan Ilmiah routes
    Route::get('/isi-kegiatan-ilmiah', [KegiatanIlmiahController::class, 'create'])->name('kegiatan-ilmiah.create');
    Route::post('/isi-kegiatan-ilmiah', [KegiatanIlmiahController::class, 'store'])->name('kegiatan-ilmiah.store');
    Route::put('/isi-kegiatan-ilmiah/{id}', [KegiatanIlmiahController::class, 'update'])->name('kegiatan-ilmiah.update');
    Route::delete('/isi-kegiatan-ilmiah/{id}', [KegiatanIlmiahController::class, 'destroy'])->name('kegiatan-ilmiah.destroy');
    Route::post('/isi-kegiatan-ilmiah/{id}/upload', [KegiatanIlmiahController::class, 'upload'])->name('kegiatan-ilmiah.upload');
    Route::get('/isi-kegiatan-ilmiah/{id}/download', [KegiatanIlmiahController::class, 'download'])->name('kegiatan-ilmiah.download');

    // Penghargaan Piagam routes
    Route::get('/isi-penghargaan-piagam', [PenghargaanPiagamController::class, 'create'])->name('penghargaan-piagam.create');
    Route::post('/isi-penghargaan-piagam', [PenghargaanPiagamController::class, 'store'])->name('penghargaan-piagam.store');
    Route::put('/isi-penghargaan-piagam/{id}', [PenghargaanPiagamController::class, 'update'])->name('penghargaan-piagam.update');
    Route::delete('/isi-penghargaan-piagam/{id}', [PenghargaanPiagamController::class, 'destroy'])->name('penghargaan-piagam.destroy');
    Route::post('/isi-penghargaan-piagam/{id}/upload', [PenghargaanPiagamController::class, 'upload'])->name('penghargaan-piagam.upload');
    Route::get('/isi-penghargaan-piagam/{id}/download', [PenghargaanPiagamController::class, 'download'])->name('penghargaan-piagam.download');

    // Riwayat Pekerjaan routes
    Route::get('/isi-riwayat-pekerjaan', [RiwayatPekerjaanController::class, 'create'])->name('riwayat-pekerjaan.create');
    Route::post('/isi-riwayat-pekerjaan', [RiwayatPekerjaanController::class, 'store'])->name('riwayat-pekerjaan.store');
    Route::put('/isi-riwayat-pekerjaan/{id}', [RiwayatPekerjaanController::class, 'update'])->name('riwayat-pekerjaan.update');
    Route::delete('/isi-riwayat-pekerjaan/{id}', [RiwayatPekerjaanController::class, 'destroy'])->name('riwayat-pekerjaan.destroy');
    Route::post('/isi-riwayat-pekerjaan/{id}/upload', [RiwayatPekerjaanController::class, 'upload'])->name('riwayat-pekerjaan.upload');
    Route::get('/isi-riwayat-pekerjaan/{id}/download', [RiwayatPekerjaanController::class, 'download'])->name('riwayat-pekerjaan.download');

    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

// Asesor routes
Route::middleware(['auth'])->prefix('asesor')->name('asesor.')->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if (!$user->isAsesor()) {
            abort(403);
        }
        return view('asesor.dashboard', [
            'user' => $user,
        ]);
    })->name('dashboard');
    
    Route::get('/pengajuan', [\App\Http\Controllers\Asesor\PengajuanController::class, 'index'])->name('pengajuan');
    Route::get('/pengajuan/{jenis}/{id}', [\App\Http\Controllers\Asesor\PengajuanController::class, 'show'])->name('pengajuan.detail');
    Route::get('/pengajuan/perolehan-kredit/{pengajuanId}/evaluasi/{matkulId}', [\App\Http\Controllers\Asesor\PengajuanController::class, 'showEvaluasi'])->name('pengajuan.evaluasi');
    
    Route::get('/assessment', function () {
        $user = Auth::user();
        if (!$user->isAsesor()) {
            abort(403);
        }
        return view('asesor.assessment', [
            'user' => $user,
        ]);
    })->name('assessment');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/courses', [CourseController::class, 'index'])->name('courses');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::get('/courses/{id}/evaluasi', [CourseController::class, 'showEvaluasi'])->name('courses.evaluasi');
    Route::get('/courses/download-template', [CourseController::class, 'downloadTemplate'])->name('courses.download-template');
    Route::get('/courses/download-point-template', [CourseController::class, 'downloadPointTemplate'])->name('courses.download-point-template');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::post('/courses/import', [CourseController::class, 'import'])->name('courses.import');
    Route::post('/courses/upload-point-pertanyaan', [CourseController::class, 'uploadPointPertanyaan'])->name('courses.upload-point-pertanyaan');
    Route::put('/courses/{id}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::put('/courses/learning-outcomes/{id}', [CourseController::class, 'updateLearningOutcome'])->name('courses.learning-outcomes.update');
    Route::delete('/courses/learning-outcomes/{id}', [CourseController::class, 'destroyLearningOutcome'])->name('courses.learning-outcomes.destroy');
    Route::get('/program-studi', [ProgramStudiController::class, 'index'])->name('program-studi');
    Route::get('/program-studi/create', [ProgramStudiController::class, 'create'])->name('program-studi.create');
    Route::get('/program-studi/download-template', [ProgramStudiController::class, 'downloadTemplate'])->name('program-studi.download-template');
    Route::post('/program-studi', [ProgramStudiController::class, 'store'])->name('program-studi.store');
    Route::post('/program-studi/import', [ProgramStudiController::class, 'import'])->name('program-studi.import');
    Route::put('/program-studi/{id}', [ProgramStudiController::class, 'update'])->name('program-studi.update');
    Route::delete('/program-studi/{id}', [ProgramStudiController::class, 'destroy'])->name('program-studi.destroy');
    // Pengajuan Mahasiswa routes
    Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
        Route::get('/dashboard', [PengajuanDashboardController::class, 'index'])->name('dashboard');
        // Route evaluasi masih diperlukan untuk melihat evaluasi dari halaman semua pengajuan
        Route::get('/perolehan-kredit/{pengajuanId}/evaluasi/{matkulId}', [\App\Http\Controllers\Admin\PengajuanPerolehanKreditController::class, 'showEvaluasi'])->name('evaluasi');
        Route::get('/semua', [SemuaPengajuanController::class, 'index'])->name('semua');
        Route::get('/semua/identitas/{mahasiswaId}', [SemuaPengajuanController::class, 'showIdentitas'])->name('semua.identitas');
        Route::get('/semua/{jenis}/{id}', [SemuaPengajuanController::class, 'show'])->name('semua.show');
    });

    Route::get('/recognitions', function () { return view('admin.recognitions'); })->name('recognitions');
    Route::get('/reports', function () { return view('admin.reports'); })->name('reports');
    Route::get('/settings', function () { return view('admin.settings'); })->name('settings');
    Route::get('/config', function () { return view('admin.config'); })->name('config');
    
    // Kode Referensi routes
    Route::get('/kode-referensi', [KodeReferensiController::class, 'index'])->name('kode-referensi');
    Route::post('/kode-referensi', [KodeReferensiController::class, 'store'])->name('kode-referensi.store');
    Route::put('/kode-referensi/{id}', [KodeReferensiController::class, 'update'])->name('kode-referensi.update');
    Route::delete('/kode-referensi/{id}', [KodeReferensiController::class, 'destroy'])->name('kode-referensi.destroy');
    
    // Asal Perguruan Tinggi routes
    Route::get('/asal-perguruan-tinggi', [AsalPerguruanTinggiController::class, 'index'])->name('asal-perguruan-tinggi');
    Route::post('/asal-perguruan-tinggi', [AsalPerguruanTinggiController::class, 'store'])->name('asal-perguruan-tinggi.store');
    Route::put('/asal-perguruan-tinggi/{id}', [AsalPerguruanTinggiController::class, 'update'])->name('asal-perguruan-tinggi.update');
    Route::delete('/asal-perguruan-tinggi/{id}', [AsalPerguruanTinggiController::class, 'destroy'])->name('asal-perguruan-tinggi.destroy');
    
    // Data Assesor
    Route::get('/data-assesor', [AssesorController::class, 'index'])->name('data-assesor');
    Route::post('/data-assesor', [AssesorController::class, 'store'])->name('data-assesor.store');
    Route::put('/data-assesor/{id}', [AssesorController::class, 'update'])->name('data-assesor.update');
    Route::delete('/data-assesor/{id}', [AssesorController::class, 'destroy'])->name('data-assesor.destroy');
    
    // User (Asesor) routes
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});
