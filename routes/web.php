<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\SessionController;
use App\Http\Controllers\API\NotificationSettingController;
use App\Http\Controllers\API\SmsConfigController;
use App\Http\Controllers\API\EmailConfigController;
use App\Http\Controllers\API\PaymentSettingController;
use App\Http\Controllers\API\FrontCmsSettingController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\LanguageController;
use App\Http\Controllers\API\CurrencyController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ModuleController;
use App\Http\Controllers\API\CustomFieldController;
use App\Http\Controllers\API\CaptchaController;
use App\Http\Controllers\API\SystemFieldController;
use App\Http\Controllers\API\FiletypeController;
use App\Http\Controllers\API\SidebarmenuController;
use App\Http\Controllers\API\UpdaterController;
use App\Http\Controllers\API\WhatsappConfigController;
use App\Http\Controllers\API\CertificateController;
use App\Http\Controllers\API\BackupController;
use App\Http\Controllers\API\EnquiryController;
use App\Http\Controllers\API\VisitorsController;
use App\Http\Controllers\API\GeneralCallController;
use App\Http\Controllers\API\DispatchController;
use App\Http\Controllers\API\ComplaintController;
use App\Http\Controllers\API\VisitorsPurposeController;
use App\Http\Controllers\API\ComplaintTypeController;
use App\Http\Controllers\API\SourceController;
use App\Http\Controllers\API\ReferenceController;

Route::prefix('api')->group(function () {
    // SettingController
    Route::get('/settings/mysql-version', [SettingController::class, 'getMysqlVersion']);
    Route::get('/settings/sql-mode', [SettingController::class, 'getSqlMode']);
    Route::get('/settings/{id?}', [SettingController::class, 'get']);
    Route::post('/settings', [SettingController::class, 'add']);
    Route::delete('/settings/{id}', [SettingController::class, 'remove']);

    // SessionController
    Route::get('/sessions/{id?}', [SessionController::class, 'get']);
    Route::get('/sessions/all', [SessionController::class, 'getAllSession']);
    Route::get('/sessions/pre/{session_id}', [SessionController::class, 'getPreSession']);
    Route::get('/sessions/student/{student_id}', [SessionController::class, 'getStudentAcademicSession']);
    Route::post('/sessions', [SessionController::class, 'add']);
    Route::delete('/sessions/{id}', [SessionController::class, 'remove']);

    // NotificationSettingController
    Route::get('/notification-settings/{id?}', [NotificationSettingController::class, 'get']);
    Route::post('/notification-settings', [NotificationSettingController::class, 'add']);
    Route::put('/notification-settings', [NotificationSettingController::class, 'update']);
    Route::put('/notification-settings/batch', [NotificationSettingController::class, 'updateBatch']);

    // SmsConfigController
    Route::get('/sms-config/{id?}', [SmsConfigController::class, 'get']);
    Route::post('/sms-config', [SmsConfigController::class, 'add']);
    Route::get('/sms-config/active', [SmsConfigController::class, 'getActiveSMS']);

    // EmailConfigController
    Route::get('/email-config', [EmailConfigController::class, 'get']);
    Route::get('/email-config/type/{email_type}', [EmailConfigController::class, 'getEmailByType']);
    Route::put('/email-config/update/{email_type}', [EmailConfigController::class, 'updateEmailConfig']);
    Route::post('/email-config', [EmailConfigController::class, 'add']);
    Route::get('/email-config/active', [EmailConfigController::class, 'getActiveEmail']);

    // PaymentSettingController
    Route::get('/payment-settings', [PaymentSettingController::class, 'get']);
    Route::get('/payment-settings/active', [PaymentSettingController::class, 'getActiveMethod']);
    Route::post('/payment-settings', [PaymentSettingController::class, 'add']);
    Route::post('/payment-settings/validate', [PaymentSettingController::class, 'validPaymentSetting']);
    Route::put('/payment-settings/active/{other?}', [PaymentSettingController::class, 'active']);

    // FrontCmsSettingController
    Route::get('/front-cms-settings/{id?}', [FrontCmsSettingController::class, 'get']);
    Route::post('/front-cms-settings', [FrontCmsSettingController::class, 'add']);
    Route::post('/front-cms-settings/validate', [FrontCmsSettingController::class, 'validCheckExists']);

    // RoleController
    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/roles/permission/{id}', [RoleController::class, 'permission']);
    Route::put('/roles/{id}', [RoleController::class, 'edit']);
    Route::delete('/roles/{id}', [RoleController::class, 'delete']);

    // LanguageController
    Route::get('/languages/rows', [LanguageController::class, 'getRows']);
    Route::get('/languages/{id?}', [LanguageController::class, 'get']);
    Route::get('/languages/enabled', [LanguageController::class, 'getEnableLanguages']);
    Route::delete('/languages/{id}', [LanguageController::class, 'remove']);
    Route::post('/languages', [LanguageController::class, 'add']);
    Route::put('/languages/staff/{id}', [LanguageController::class, 'setUserLang']);
    Route::put('/languages/student/{id}', [LanguageController::class, 'setStudentLang']);
    Route::put('/languages/parent/{id}', [LanguageController::class, 'setParentLang']);
    Route::post('/languages/validate', [LanguageController::class, 'validCheckExists']);
    Route::get('/languages/update520', [LanguageController::class, 'update520']);

    // CurrencyController
    Route::get('/currencies/{id?}', [CurrencyController::class, 'get']);
    Route::post('/currencies', [CurrencyController::class, 'add']);
    Route::put('/currencies/settings', [CurrencyController::class, 'updateCurrency']);

    // UserController
    Route::post('/users', [UserController::class, 'add']);
    Route::post('/users/parent', [UserController::class, 'addNewParent']);
    Route::post('/users/login', [UserController::class, 'checkLogin']);
    Route::post('/users/login-parent', [UserController::class, 'checkLoginParent']);
    Route::get('/users/information/{users_id}', [UserController::class, 'readUserInformation']);

    // ModuleController
    Route::get('/modules/permissions', [ModuleController::class, 'getPermission']);
    Route::get('/modules/parent-permissions', [ModuleController::class, 'getParentPermission']);
    Route::get('/modules/student-permissions', [ModuleController::class, 'getStudentPermission']);
    Route::put('/modules/status', [ModuleController::class, 'changeStatus']);
    Route::get('/modules/permission/{module_name}', [ModuleController::class, 'getPermissionByModulename']);
    Route::get('/modules/{id?}', [ModuleController::class, 'get']);
    Route::get('/modules/parent/{id?}', [ModuleController::class, 'getParent']);
    Route::get('/modules/student/{id?}', [ModuleController::class, 'getStudent']);
    Route::get('/modules/user-permissions/{role}', [ModuleController::class, 'getUserPermission']);
    Route::put('/modules/parent-status', [ModuleController::class, 'changeParentStatus']);
    Route::put('/modules/student-status', [ModuleController::class, 'changeStudentStatus']);
    Route::get('/modules/has/{module_shortcode}', [ModuleController::class, 'hasModule']);
    Route::get('/module-permissions/{id?}', [ModuleController::class, 'getModulePermissions']);

    // CustomFieldController
    Route::get('/custom-fields/{id?}', [CustomFieldController::class, 'get']);
    Route::delete('/custom-fields/{id}', [CustomFieldController::class, 'remove']);
    Route::post('/custom-fields', [CustomFieldController::class, 'add']);
    Route::put('/custom-fields/order', [CustomFieldController::class, 'updateOrder']);
    Route::get('/custom-fields/belong/{belong_to}', [CustomFieldController::class, 'getByBelong']);
    Route::post('/custom-fields/insert', [CustomFieldController::class, 'insertRecord']);
    Route::put('/custom-fields/update', [CustomFieldController::class, 'updateRecord']);
    Route::get('/custom-fields/custom/{belongs_to}/{display_table?}', [CustomFieldController::class, 'getCustomFields']);
    Route::post('/custom-fields/online-admission/insert', [CustomFieldController::class, 'onlineAdmissionInsertRecord']);
    Route::put('/custom-fields/online-admission/update', [CustomFieldController::class, 'onlineAdmissionUpdateRecord']);

    // CaptchaController
    Route::get('/captcha/settings', [CaptchaController::class, 'getSetting']);
    Route::get('/captcha/status/{name}', [CaptchaController::class, 'getStatus']);
    Route::put('/captcha/status', [CaptchaController::class, 'updateStatus']);

    // SystemFieldController
    Route::get('/system-fields', [SystemFieldController::class, 'index']);
    Route::put('/system-fields/status', [SystemFieldController::class, 'changeStatus']);

    // FiletypeController
    Route::get('/filetypes/{id?}', [FiletypeController::class, 'get']);
    Route::post('/filetypes', [FiletypeController::class, 'add']);

    // SidebarmenuController
    Route::get('/sidebar-menus/{id?}', [SidebarmenuController::class, 'get']);
    Route::delete('/sidebar-menus/{id}', [SidebarmenuController::class, 'remove']);
    Route::post('/sidebar-menus', [SidebarmenuController::class, 'add']);
    Route::post('/sidebar-sub-menus', [SidebarmenuController::class, 'addSubMenu']);
    Route::get('/sidebar-menus/with-submenus/{sidebar_display?}', [SidebarmenuController::class, 'getMenuwithSubmenus']);
    Route::get('/sidebar-sub-menus/menu/{menu_id}/{sidebar_display?}', [SidebarmenuController::class, 'getSubmenusByMenuId']);
    Route::get('/sidebar-sub-menus/{id}', [SidebarmenuController::class, 'getSubmenuById']);
    Route::put('/sidebar-menus/order', [SidebarmenuController::class, 'updateMenuOrder']);
    Route::put('/sidebar-sub-menus/order', [SidebarmenuController::class, 'updateSubmenuOrder']);
    Route::put('/sidebar-sub-menus/key', [SidebarmenuController::class, 'updateSubmenuByKey']);

    // UpdaterController
    Route::get('/updater/{chk?}', [UpdaterController::class, 'index']);
    Route::post('/updater', [UpdaterController::class, 'store']);

    // WhatsappConfigController
    Route::get('/whatsapp-configs', [WhatsappConfigController::class, 'index'])->name('admin.whatsapp.index');
    Route::post('/whatsapp-configs/update', [WhatsappConfigController::class, 'whatsapp'])->name('admin.whatsapp');

    // CertificateController
    Route::get('/certificates', [CertificateController::class, 'index'])->name('admin.certificate.index');
    Route::post('/certificates/create', [CertificateController::class, 'create'])->name('admin.certificate.create');
    Route::post('/certificates/edit/{id}', [CertificateController::class, 'edit'])->name('admin.certificate.edit');
    Route::delete('/certificates/delete/{id}', [CertificateController::class, 'delete'])->name('admin.certificate.delete');
    Route::post('/certificates/view', [CertificateController::class, 'view'])->name('admin.certificate.view');
    Route::post('/certificates/view1', [CertificateController::class, 'view1'])->name('admin.certificate.view1');

    // BackupController
    Route::match(['get', 'post'], '/backup', [BackupController::class, 'backup'])->name('admin.backup');
    Route::get('/downloadbackup/{filename}', [BackupController::class, 'download'])->name('admin.backup.download');
    Route::delete('/dropbackup/{filename}', [BackupController::class, 'delete'])->name('admin.backup.delete');

    // EnquiryController
    Route::prefix('enquiry')->group(function () {
        Route::get('/', [EnquiryController::class, 'index']);
        Route::post('/', [EnquiryController::class, 'store']);
        Route::get('/{id}', [EnquiryController::class, 'show']);
        Route::put('/{id}', [EnquiryController::class, 'update']);
        Route::delete('/{id}', [EnquiryController::class, 'destroy']);
        Route::post('/{enquiryId}/follow-up', [EnquiryController::class, 'followUp']);
        Route::get('/{enquiryId}/follow-ups', [EnquiryController::class, 'getFollowUps']);
        Route::delete('/{enquiryId}/follow-up/{followUpId}', [EnquiryController::class, 'deleteFollowUp']);
        Route::put('/{id}/status', [EnquiryController::class, 'changeStatus']);
        Route::get('/classes', [EnquiryController::class, 'getClasses']);
        Route::get('/sources', [EnquiryController::class, 'getSources']);
        Route::get('/references', [EnquiryController::class, 'getReferences']);
    });

    // VisitorsController
    Route::prefix('visitors')->group(function () {
        Route::get('/', [VisitorsController::class, 'index']);
        Route::post('/', [VisitorsController::class, 'store']);
        Route::get('/{id}', [VisitorsController::class, 'show']);
        Route::put('/{id}', [VisitorsController::class, 'update']);
        Route::delete('/{id}', [VisitorsController::class, 'destroy']);
        Route::get('/purposes', [VisitorsController::class, 'getPurposes']);
    });

    // GeneralCallController
    Route::prefix('general-calls')->group(function () {
        Route::get('/', [GeneralCallController::class, 'index']);
        Route::post('/', [GeneralCallController::class, 'store']);
        Route::get('/{id}', [GeneralCallController::class, 'show']);
        Route::put('/{id}', [GeneralCallController::class, 'update']);
        Route::delete('/{id}', [GeneralCallController::class, 'destroy']);
        Route::get('/list', [GeneralCallController::class, 'getCallList']); // DataTables endpoint
    });

    // DispatchController
    Route::prefix('dispatch')->group(function () {
        Route::get('/', [DispatchController::class, 'index']); // ?type=dispatch or ?type=receive
        Route::post('/', [DispatchController::class, 'store']);
        Route::get('/{id}', [DispatchController::class, 'show']);
        Route::put('/{id}', [DispatchController::class, 'update']);
        Route::delete('/{id}', [DispatchController::class, 'destroy']);
        Route::get('/download/{id}', [DispatchController::class, 'download']);
    });

    // ComplaintController
    Route::prefix('complaints')->group(function () {
        Route::get('/', [ComplaintController::class, 'index']);
        Route::post('/', [ComplaintController::class, 'store']);
        Route::get('/{id}', [ComplaintController::class, 'show']);
        Route::put('/{id}', [ComplaintController::class, 'update']);
        Route::delete('/{id}', [ComplaintController::class, 'destroy']);
        Route::get('/types', [ComplaintController::class, 'getComplaintTypes']);
        Route::get('/sources', [ComplaintController::class, 'getComplaintSources']);
        Route::get('/download/{id}', [ComplaintController::class, 'download']);
    });

    // VisitorsPurposeController
    Route::prefix('visitors-purposes')->group(function () {
        Route::get('/', [VisitorsPurposeController::class, 'index']);
        Route::post('/', [VisitorsPurposeController::class, 'store']);
        Route::get('/{id}', [VisitorsPurposeController::class, 'show']);
        Route::put('/{id}', [VisitorsPurposeController::class, 'update']);
        Route::delete('/{id}', [VisitorsPurposeController::class, 'destroy']);
    });

    // ComplaintTypeController
    Route::prefix('complaint-types')->group(function () {
        Route::get('/', [ComplaintTypeController::class, 'index']);
        Route::post('/', [ComplaintTypeController::class, 'store']);
        Route::get('/{id}', [ComplaintTypeController::class, 'show']);
        Route::put('/{id}', [ComplaintTypeController::class, 'update']);
        Route::delete('/{id}', [ComplaintTypeController::class, 'destroy']);
    });

    // SourceController
    Route::prefix('sources')->group(function () {
        Route::get('/', [SourceController::class, 'index']);
        Route::post('/', [SourceController::class, 'store']);
        Route::get('/{id}', [SourceController::class, 'show']);
        Route::put('/{id}', [SourceController::class, 'update']);
        Route::delete('/{id}', [SourceController::class, 'destroy']);
    });

    // ReferenceController
    Route::prefix('references')->group(function () {
        Route::get('/', [ReferenceController::class, 'index']);
        Route::post('/', [ReferenceController::class, 'store']);
        Route::get('/{id}', [ReferenceController::class, 'show']);
        Route::put('/{id}', [ReferenceController::class, 'update']);
        Route::delete('/{id}', [ReferenceController::class, 'destroy']);
    });
});