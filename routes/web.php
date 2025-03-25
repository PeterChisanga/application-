<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EquipmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/products-and-services', function () {
    return view('products-and-services');
});

Route::get('/careers', function () {
    return view('careers');
});

Route::get('/about-plr-zambia', function () {
    return view('about-plr-zambia');
});


// User Routes
// Route::get('/register', [UserController::class, 'create'])->name('users.create');
// Route::post('/register', [UserController::class, 'store'])->name('users.store');
// Route::get('/login', [UserController::class, 'showLoginForm'])->name('users.login');
Route::get('/login', [UserController::class, 'showLoginForm'])->name('users.login')->middleware('guest');
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::get('/logout', [UserController::class, 'logout'])->name('users.logout');

//test routes
Route::get('/test-pdf', function () {
    $data = ['message' => 'Hello, DomPDF!'];
    $pdf = Pdf::loadView('pdf.test', $data);
    return $pdf->stream('test.pdf');
});

// Application Routes
Route::get('/applications/create', [ApplicationController::class, 'create'])->name('applications.create');
Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
Route::get('/applications/tracking', [ApplicationController::class, 'getApplicationTrackingForm'])->name('applications.track');
Route::post('/applications/tracking', [ApplicationController::class, 'trackApplication'])->name('applications.track-post');
Route::post('/applications/{application}/certificates', [ApplicationController::class, 'uploadCertificates'])->name('applications.uploadCertificates');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Accessible by all authenticated users
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboards.admin');
    Route::get('/users/show', [UserController::class, 'show'])->name('users.show');
    Route::post('/change-password', [UserController::class, 'changePassword'])->name('change-password');

    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    Route::match(['get', 'post'], '/employees/{employeeId}/generate-payslip', [EmployeeController::class, 'generatePayslip'])->name('employees.generatePayslip');
    Route::get('/employees/{employee}/information', [EmployeeController::class, 'printEmployeeInformation'])->name('employees.print_employee_information');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');

    Route::get('/payslips/upload', [PayslipController::class, 'showUploadForm'])->name('payslips.uploadForm');
    Route::post('/payslips/upload', [PayslipController::class, 'upload'])->name('payslips.upload');
    Route::get('/payslips/generate', [PayslipController::class, 'generate'])->name('payslips.generate');

    Route::middleware(['role:hr,admin'])->group(function () {
        Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::get('/applications/edit/{application}', [ApplicationController::class, 'edit'])->name('applications.edit');
        Route::patch('/applications/update/{application}', [ApplicationController::class, 'update'])->name('applications.update');
    });

    Route::middleware(['role:admin'])->group(function () {
        // Equipment Routes
        Route::get('/equipments/upload', [EquipmentController::class, 'showUploadForm'])->name('equipments.uploadForm');
        Route::post('/equipments/upload', [EquipmentController::class, 'upload'])->name('equipments.upload');
        Route::resource('equipments', EquipmentController::class);
        Route::post('/reports/generate', [EquipmentController::class, 'generate'])->name('reports.generate');

        // Trip Routes
        Route::get('/trips', [EquipmentController::class, 'indexTrips'])->name('trips.index');
        Route::get('/trips/last-trip/{equipmentId}', [EquipmentController::class, 'getLastTripEndKilometers']);
        Route::get('/trips/create/{equipment}', [EquipmentController::class, 'createTrip'])->name('trips.create');
        Route::post('/trips', [EquipmentController::class, 'storeTrip'])->name('trips.store');
        Route::get('/trips/{trip}', [EquipmentController::class, 'showTrip'])->name('trips.show');
        Route::get('/trips/{trip}/edit', [EquipmentController::class, 'editTrip'])->name('trips.edit');
        Route::put('/trips/{trip}', [EquipmentController::class, 'updateTrip'])->name('trips.update');
        Route::delete('/trips/{trip}', [EquipmentController::class, 'destroyTrip'])->name('trips.destroy');

        // Machinery Usage Routes
        Route::post('/machinery-usages/store', [EquipmentController::class, 'storeMachineryUsage'])->name('machinery_usages.store');
        Route::get('/machinery-usages/last-usage/{equipment_id}', [EquipmentController::class, 'lastMachineryUsage'])->name('machinery_usages.last');

        // Spares Routes
        Route::get('/equipment-spares/create/{equipment}', [EquipmentController::class, 'createSpare'])->name('equipment_spares.create');
        Route::post('/equipment-spares/store', [EquipmentController::class, 'storeSpare'])->name('equipment_spares.store');

        // Insurance Routes
        Route::get('/equipment-insurances/create/{equipment}', [EquipmentController::class, 'createInsurance'])->name('equipment_insurances.create');
        Route::post('/equipment-insurances/store', [EquipmentController::class, 'storeInsurance'])->name('equipment_insurances.store');

        // Tax Routes
        Route::get('/equipment-taxes/create/{equipment}', [EquipmentController::class, 'createTax'])->name('equipment_taxes.create');
        Route::post('/equipment-taxes/store', [EquipmentController::class, 'storeTax'])->name('equipment_taxes.store');
    });

    // Placeholder Route (accessible to all authenticated users)
    Route::get('/not-implemented-yet', function () {
        return view('not-implemented-yet');
    })->name('not-implemented-yet');
});
