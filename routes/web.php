<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AttendenceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TimetableController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\TeacherMiddleware;
use App\Http\Middleware\StudentMiddleware;
// <----------------Home Page Route----------------->

Route::get('/', [HomeController::class, 'home_index'])->name('home');
Route::get('/home/courses', [HomeController::class, 'courses_index'])->name('courses.index');
Route::get('/team', [HomeController::class, 'team_index'])->name('team.index');
// <----------------Authentication Routes----------------->
Route::get('/register', [RegisterController::class, 'register_index'])->name('register');
Route::post('/register/submit', [RegisterController::class, 'register_store'])->name('register.store');
Route::get('/registeration/success/{id}', [RegisterController::class, 'registerationSuccess'])->name('registration.success');

// Temporary route to create a test student and setup permissions
Route::get('/test-setup-student', function() {
    // Create the permissions if they don't exist
    if (class_exists(\Spatie\Permission\Models\Permission::class)) {
        $p1 = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'manage-enrollments', 'guard_name' => 'web']);
        $p2 = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'manage-user-passwords', 'guard_name' => 'web']);
        
        // Give them to the admin role
        $adminRole = \Spatie\Permission\Models\Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo([$p1, $p2]);
        }
    }

    $user = \App\Models\User::firstOrCreate(
        ['email' => 'teststudent@example.com'],
        [
            'name' => 'Test Student',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]
    );

    // Assign student role if Spatie Permission is set up
    if (method_exists($user, 'assignRole')) {
        $user->assignRole('student');
    }

    // Optionally enroll in a course to see data on dashboard
    $course = \App\Models\Courses::where('status', 'active')->first();
    if ($course) {
        \App\Models\Enrollment::firstOrCreate([
            'user_id' => $user->id,
            'module_id' => $course->id,
            'status' => 'enrolled'
        ]);
    }

    return "Test student created! Email: teststudent@example.com | Password: password123. <a href='/login'>Go to Login</a>";
});

Route::post('trackRegisteration', [RegisterController::class, 'trackRegisteration'])->name('registration.track');
Route::get('/payment/upload', [PaymentController::class, 'uploadPaymentView'])->name('payment.upload');
Route::post('payment/slipUploaded', [PaymentController::class, 'uploadPayment'])->name('payment.submit');


// <----------------------------Login and logout------------------------>

Route::get('/login', [RegisterController::class, 'loginView'])->name('login');
Route::post('/submitLogin', [RegisterController::class, 'submitLogin'])->name('login.submit');
Route::post('/logout', [RegisterController::class, 'logout'])->name('logout');

// Smart Dashboard Redirect
Route::get('/dashboard', function() {
    $user = auth()->user();
    if ($user->hasRole('admin'))   return redirect()->route('dashboard');
    if ($user->hasRole('teacher')) return redirect()->route('teacher.main');
    if ($user->hasRole('student')) return redirect()->route('student.main');
    return redirect('/');
})->middleware(['auth']);





// <!-- <---------------- AdminDashboard Page Route----------------->
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard_index'])->name('dashboard');
    Route::get('teacher/Management', [DashboardController::class, 'teacherManagementView'])->name('teacher.Management.View');
    Route::post('register/teacher', [DashboardController::class, 'teacherRegister'])->name('regester.teacher');
    Route::post('teacher/{id}/assign-course', [DashboardController::class, 'teacherAssignCourse'])->name('teacher.assign.course');
    Route::put('teacher/{id}/update', [DashboardController::class, 'teacherUpdate'])->name('teacher.update');
    Route::delete('teacher/{id}/delete', [DashboardController::class, 'teacherDelete'])->name('teacher.delete');
    Route::get('/student/management', [DashboardController::class, 'studentManagement'])->name('admin.student.management');
    Route::get('/student/{id}/details', [DashboardController::class, 'studentDetails'])->name('admin.student.details');
    Route::get('/certificates/management', [DashboardController::class, 'certificatesManagement'])->name('admin.certificates.management');
    Route::post('/student/certificate', [DashboardController::class, 'assignCertificate'])->name('admin.student.certificate');
    Route::post('approve/{id}/student', [DashboardController::class, 'adminApproveStudent'])->name('admin.approve.student');
    Route::delete('delete-registration/{id}', [DashboardController::class, 'deleteRegistration'])->name('admin.registration.delete');
    Route::post('manual-registration', [DashboardController::class, 'manualRegistration'])->name('admin.registration.manual');
    Route::post('/enrollment/update-status', [DashboardController::class, 'updateEnrollmentStatus'])->name('admin.enrollment.updateStatus');
    Route::post('/user/update-password', [DashboardController::class, 'adminUpdateUserPassword'])->name('admin.user.updatePassword');
    Route::get('/admin/rolesOrPermissions', [DashboardController::class, 'roleOrPermissionsView'])->name('admin.role');
    Route::post('/roles/created', [RolesController::class, 'store'])->name('roles.store');
    Route::get('/roles/{roleId}/update', [RolesController::class, 'updateView'])->name('role.update.view');
    Route::post('/role/{roleId}/updated', [RolesController::class, 'update'])->name('role.update');
    Route::delete('/role/delete/{id}', [RolesController::class, 'destroy'])->name('role.delete');

    Route::post('/permission/created', [PermissionsController::class, 'store'])->name('permissions.store');
    Route::get('/permission/{roleId}/update', [PermissionsController::class, 'updateView'])->name('permission.update.view');
    Route::post('/permission/{roleId}/updated', [PermissionsController::class, 'update'])->name('permission.update');
    Route::delete('/permission/delete/{id}', [PermissionsController::class, 'destroy'])->name('permission.delete');

    Route::get('role/permissions', [RolesController::class, 'rolesOrpermission'])->name('roles.permissions.view');
    Route::post('role/permission/Assigned', [RolesController::class, 'roleORpermissions'])->name('role.permission.assign');
    Route::get('/update/{id}/rolepermissions', [RolesController::class, 'updateRolepermission'])->name('update.rolepermissions');
    Route::post('/update/{id}/rolepermissions', [RolesController::class, 'editRolepermission'])->name('role.permission.update');
    Route::delete('/role/permission/{id}', [RolesController::class, 'deleteRole'])->name('role.permission.delete');
    Route::get('/role/manage', [RolesController::class, 'roleManageView'])->name('manage.role.view');
    Route::post('user/role/assinged/{id}', [RolesController::class, 'userRoleAssined'])->name('user.role.assign');
    Route::delete('user/delete/{id}', [RolesController::class, 'deleteuserRole'])->name('user.delete');


    Route::get('departments',[DepartmentController::class,'index'])->name('department.view');
    Route::get('classes/department',[DepartmentController::class,'classDep'])->name('classDep.view');
    Route::post('classes/store',[DepartmentController::class,'classStore'])->name('admin.class.store');
    Route::delete('classes/{id}/delete',[DepartmentController::class,'classDelete'])->name('admin.class.delete');
    Route::get('subjects/department',[DepartmentController::class,'subjectDep'])->name('subjectDep.view');
    Route::get('virtualRoom/view',[DepartmentController::class,'virtualRoom'])->name('virtualRoom.view');
    Route::get('financial/reports/view',[ReportsController::class,'index'])->name('financial.view');
    Route::get('systems/logs/view',[ReportsController::class,'systemLogs'])->name('systemlogs.view');
    Route::post('systems/logs/clear',[ReportsController::class,'clearLogs'])->name('systemlogs.clear');
    
    // System Settings (Admin only)
    Route::put('settings/update/system',[SettingController::class,'updateSystemSetting'])->name('settings.update.system');

    Route::get('system/admins', [DashboardController::class, 'systemAdminsView'])->name('admin.system.admins');
    Route::post('system/admins/store', [DashboardController::class, 'systemAdminStore'])->name('admin.system.admins.store');
    Route::delete('system/admins/{id}/delete', [DashboardController::class, 'systemAdminDelete'])->name('admin.system.admins.delete');
});

Route::middleware(['auth'])->group(function() {
    Route::get('settings/view',[SettingController::class,'index'])->name('settings.view');
    Route::put('settings/update',[SettingController::class,'updateSetting'])->name('settings.update');
});

Route::get('courses', [CoursesController::class, 'courses_index'])->name('course.index');
Route::get('courses/create', [CoursesController::class, 'create'])->name('course.create');
Route::get('courses/{id}/edit', [CoursesController::class, 'edit'])->name('course.edit');
Route::get('courses/{id}', [CoursesController::class, 'show'])->name('course.show');
Route::post('courses/store', [CoursesController::class, 'store'])->name('course.store');
Route::put('courses/{id}/update', [CoursesController::class, 'update'])->name('course.update');
Route::delete('courses/{id}/delete', [CoursesController::class, 'destroy'])->name('course.destroy');

// Dedicated Workshops Section
Route::get('workshops', [\App\Http\Controllers\WorkshopsController::class, 'index'])->name('workshops.index');
Route::get('workshops/create', [\App\Http\Controllers\WorkshopsController::class, 'create'])->name('workshops.create');
Route::post('workshops/store', [\App\Http\Controllers\WorkshopsController::class, 'store'])->name('workshops.store');
Route::get('workshops/{id}/edit', [\App\Http\Controllers\WorkshopsController::class, 'edit'])->name('workshops.edit');
Route::put('workshops/{id}/update', [\App\Http\Controllers\WorkshopsController::class, 'update'])->name('workshops.update');
Route::delete('workshops/{id}/delete', [\App\Http\Controllers\WorkshopsController::class, 'destroy'])->name('workshops.destroy');
Route::get('assignments', [AssignmentController::class, 'assignment_index'])->name('assignments');
Route::get('timetable', [TimetableController::class, 'index_timetable'])->name('time.table');
Route::get('create/timetable', [TimetableController::class, 'create_timetable_view'])->name('create.timetable.view');
Route::get('attendence/view', [AttendenceController::class, 'attendence_view'])->name('attendence.view');
Route::get('fees', [FeeController::class, 'fee_view'])->name('fee.view');

// });
// <!-- <----------------teacher Page Route----------------->

Route::prefix('teacher')->middleware(['auth'])->group(function () {
        Route::get('/teacher/dashboard', [TeacherController::class, 'teacherMain'])->name('teacher.main');

  Route::get('classes',[TeacherController::class,'teacherClassView'])->name('teacherClass.view');
  Route::get('my-students', [TeacherController::class, 'teacherStudentsView'])->name('teacher.students.view');
  Route::get('manage/lessons',[TeacherController::class,'manageLessonsView'])->name('manageLessons.view');
  Route::get('module/{moduleId}/students', [TeacherController::class, 'getModuleStudents'])->name('module.students');
  Route::post('class-notification/send', [TeacherController::class, 'sendClassNotification'])->name('class.notification.send');
  Route::post('teacher/lessons/store',[TeacherController::class,'teacherLessonsStore'])->name('teacher.lessons.store');
  Route::delete('teacher/lessons/destroy/{id}',[TeacherController::class,'teacherLessonsDestroy'])->name('teacher.lessons.destroy');
  Route::put('lessons/update/{id}',[TeacherController::class,'teacherLessonsUpdate'])->name('teacher.lessons.update');
  Route::get('create/Online/Class',[TeacherController::class,'createOnlineClass'])->name('createOnlineClass.view');
  Route::get('recorded/Leacture',[TeacherController::class,'recordedLeacture'])->name('recordedLeacture.view');
  Route::get('student/Grade/Upload',[TeacherController::class,'stdGradeUpload'])->name('stdGradeUpload.view');
  Route::get('assignment/Reviews',[TeacherController::class,'assignmentReviews'])->name('assignmentReviews.view');
  Route::get('upload/Materials/Index',[TeacherController::class,'uploadMaterialsIndex'])->name('uploadMaterials.view');
  Route::post('resources/store',[TeacherController::class,'teacherResourcesStore'])->name('teacher.resources.store');
  Route::delete('resource/delete/{id}',[TeacherController::class,'teacherResourceDelete'])->name('teacher.resource.delete');
  Route::post('teacher/online-classes/store',[TeacherController::class,'teacherOnline_classesStore'])->name('teacher.online-classes.store');
  Route::patch('online-classes/{id}/updateStatus',[TeacherController::class,'teacherOnline_classesUpdateStatus'])->name('teacher.online-classes.updateStatus');
   Route::get('online-class/{id}/join', [AttendenceController::class, 'logAttendanceAndJoin'])->name('student.joinClassAction');
   Route::get('/teacher/assignments/create', [AssignmentController::class, 'index'])->name('teacher.assignments.uplodaView');
Route::post('/teacher/assignments', [AssignmentController::class, 'teacherAssignmentsStore'])->name('teacher.assignments.store');
Route::delete('/teacher/assignments/{id}', [AssignmentController::class, 'destroy'])->name('teacher.assignments.destroy');
Route::post('/submissions/grade', [TeacherController::class, 'submitGrade'])->name('teacher.submissions.grade');
});
//   Route::post('/student/approve', [DashboardController::class, 'approve'])->name('student.approve');


// <!-- <----------------student Page Route----------------->

Route::prefix('student')->middleware(['student:student'])->group(function () {
    Route::get('/dashboard', [StudentController::class, 'studentIndex'])->name('student.main');
    Route::get('enrolled/Courses', [StudentController::class, 'enrolledCourses'])->name('enrolledCourses.view');
    Route::get('jion/Class', [StudentController::class, 'jionClass'])->name('jionClass.view');
    Route::get('learning/materials',[StudentController::class,'learningMaterialsView'])->name('learning.materials.view');
    Route::get('assigments/upload',[StudentController::class,'assigmentsUploadView'])->name('assigments.upload.view');
    Route::post('/student/assignment/submit', [StudentController::class, 'storeSubmission'])->name('student.submissions.store');
    Route::post('/student/review/submit', [StudentController::class, 'storeReview'])->name('student.review.submit');
    Route::get('/my-certificates', [StudentController::class, 'certificatesView'])->name('student.certificates.view');
});

// Temporary route to clear cache on live server
Route::get('/clear-config', function() {
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    return "Configuration and Cache cleared successfully! Please try to approve the student now.";
});
