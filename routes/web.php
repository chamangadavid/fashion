<?php


use App\Http\Controllers\Audit\AuditLogController;
use App\Http\Controllers\Contacts\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\Staff\StaffsController;
use App\Http\Controllers\UserSearchController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::get('/test-imagick', function() {
    phpinfo();
});


/*
|--------------------------------------------------------------------------
| Site Pages
|--------------------------------------------------------------------------
*/

Route::get('/about-us', function () {
    return Inertia::render('Site/aboutUs');
})->name('aboutUs');

Route::get('/product-gallery', function () {
    return Inertia::render('Site/Gallery');
})->name('productGalleries');

Route::get('/faq', function () {
    return Inertia::render('Site/FAQ');
})->name('faq');

Route::get('/contact-us', function () {
    return Inertia::render('Site/Contact');
})->name('contactDetails');

Route::get('/management-team', function () {
    return Inertia::render('Site/ManagementTeam');
})->name('managementTeam');

Route::get('/history-details', function () {
    return Inertia::render('Site/History');
})->name('history');

Route::get('/our-services', function () {
    return Inertia::render('Site/Services');
})->name('ourServices');



Route::get('/icao-annex', function () {
    return Inertia::render('Site/ICAOAnnex');
})->name('icaoAnnex');

Route::get('/document-repository', function () {
    return Inertia::render('Site/Documents');
})->name('documentRepository');

Route::get('/report-accidents', function () {
    return Inertia::render('Site/AccidentReports');
})->name('accidentPage');

Route::get('/all-news', function () {
    return Inertia::render('Site/News');
})->name('newsPage');

Route::get('/press-releases', function () {
    return Inertia::render('Site/PressReleases');
})->name('pressReleasesPage');

Route::get('/announcement', function () {
    return Inertia::render('Site/Announcements');
})->name('announcementsPage');

//details page for management team members
// Route::get('/management-team/{id}', function ($id) {
//     $member = Team::findOrFail($id);

//     return Inertia::render('Site/ManagementTeamDetails', [
//         'member' => $member
//     ]);
// });


//public
Route::post('/contact', [ContactController::class, 'store']);
// Route::get('/faqs/all', [FaqController::class, 'getfrontIndex']); // For your FAQ page
// Route::get('/public/announcements', [AnnouncementsController::class, 'publicAnnouncementsIndex']);
// Route::get('/public/news', [NewsController::class, 'publicNewsIndex']);
// Route::get('/all-news/{id}', [NewsController::class, 'show']);
// Route::get('/public-documents/folders', [DocumentController::class, 'Publicindex']);

// Route::get('/jobs/active', [JobController::class, 'getActiveJobs']);
// Route::get('/public-members', [TeamController::class, 'publicMember']);
// Route::get('/all-jobs-details/{job}', [JobController::class, 'details']);

// Route::get('/frontend/faqs', [FaqController::class, 'getfrontIndex']); 
// Route::get('/frontend/testimonials', [TestimonyController::class, 'frontendTestimonials']);
// Route::get('/frontend/gallery', [GalleryController::class,'frontendGallery']);



Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/roles-permissions', fn () => Inertia::render('RolesPermissions'))->name('roles.permissions');
    Route::get('/roles', [RolePermissionController::class, 'roles']);
    Route::post('/roles', [RolePermissionController::class, 'storeRole']);
    Route::put('/roles/{id}', [RolePermissionController::class, 'update']);
    Route::delete('/roles/{id}', [RolePermissionController::class, 'destroy']);
    Route::post('/roles/bulk-delete', [RolePermissionController::class, 'bulkDestroy']);
    Route::get('/permissions', [RolePermissionController::class, 'permissions']);
    Route::post('/permissions', [RolePermissionController::class, 'storePermission']);
    Route::get('/rolesAndPermission', [RolePermissionController::class, 'rolesAndPermission'])->name('admin.rolesAndPermission');

    Route::get('/staff-roles', [StaffsController::class, 'staffRoles'])->name('staff.index');
    Route::post('/add-users', [StaffsController::class, 'store']);
    Route::get('/get-staff-roles', [RolePermissionController::class, 'staffRoles']);
    Route::get('/search-users', [UserSearchController::class, 'search'])->name('users.search');
    Route::get('/users/{user}', [UserSearchController::class, 'show'])->name('users.show');


    //contact routes
    Route::get('/contact-messages', [ContactController::class, 'ContactUs'])->name('contact.index');
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::delete('/contacts/{id}', [ContactController::class, 'destroy']);
    






});







require __DIR__ . '/auth.php';
