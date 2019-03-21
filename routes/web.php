<?php

use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/loads', function() {

    return view('home');
});

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Clear cache cleared</h1>';
});
Route::any('/{lang}/home','BaseController@lang');
Route::any('/{lang}','BaseController@lang');
Route::any('/','BaseController@lang');
Route::get('{lang}/users/send-message/{userid}','BaseController@sendMessageView');
Route::post('{lang}/users/send-message-to-user/{userid}','BaseController@sendMessage');


Route::get('/{lang}/userprofile','UserProfileController@userProfile');

Route::group(['prefix' => '{lang}/admins','middleware' => ['admins']], function () {
    Route::any('/new','UsersController@newuser');
    Route::any('/{id}/edit','UsersController@edit');
    Route::any('/{id}/update','UsersController@update');
    Route::any('/{id}/delete','UsersController@delete');
    Route::any('/savenew','UsersController@store');
    Route::any('/{id}/rest-password-admin','UsersController@restPsswordAdmin');
    Route::any('/{id}/rest-password','UsersController@restPssword');
    Route::any('/','UsersController@indexAdmin');
    Route::any('/filter','UsersController@filter');

});



Route::group(['prefix' => '{lang}/category','middleware' => ['uptomanger']], function () {
    Route::get('/editcategory','CategoriesController@editCategories');
    Route::post('/savesort','CategoriesController@savesortCategories');
    Route::post('/addcategory','CategoriesController@addCategories');
    Route::get('/delete','CategoriesController@deletecategory');
});
Route::any('/{lang}/category','CategoriesController@indexCategory');

Route::get('/{lang}/category/viewAddCategory','CategoriesController@viewAddCategory')->middleware('uptomanger');;
Route::get('/{lang}/category/viewsort','CategoriesController@viewSort')->middleware('uptomanger');;
Route::any('/{lang}/category/{id}/vieweditcategory','CategoriesController@viewedit')->middleware('uptomanger');;

Route::group(['prefix' => '{lang}/levels','middleware' => ['uptomanger']], function () {
    Route::get('/viewAddLevel','LevelsController@viewAddLevel');
    Route::post('/add','LevelsController@addLevels');
    Route::get('/addlevels','LevelsController@addLevels');
    Route::any('/{id}/vieweditlevels','LevelsController@viewedit');
    Route::post('/editlevels','LevelsController@editlevels');
    Route::get('/delete','LevelsController@deletelevels');


});
Route::any('/{lang}/levels','LevelsController@indexLevels')->middleware('teatcher');
Route::get('/{lang}/levels/send-message/{levelid}','LevelsController@sendMessageView')->middleware('teatcher');
Route::post('/{lang}/levels/send-message/{levelid}','LevelsController@sendMessageToLevelStu')->middleware('teatcher');



Route::any('/{lang}/schedule','ScheduleController@indexSchedule');

Route::post('/{lang}/schedule/saveschedule','ScheduleController@SaveSchedule');
Route::post('/{lang}/schedule/savescheduletea','ScheduleController@SaveScheduleTeacher');

Route::any('/{lang}/schedule/class','ScheduleController@ClassSchedule');


Route::post('/{lang}/curriculums/{id}/syllabus','CurriculumsController@syllabus')->name('curriculums.syllabus');


Route::group(['prefix' => '{lang}/lessons','middleware' => ['teatcher']], function () {
    Route::get('/{id}/viewedit','LessonsController@viewedit');
    Route::post('/{id}/saveedit','LessonsController@saveedit');
    Route::get('/{id}/deletelesson','LessonsController@delete');
    Route::get('/add','LessonsController@viewadd');
    Route::post('/saveadd','LessonsController@saveadd');
    Route::get('/assign_lesson_to_group/{id}','LessonsController@assignLessonToGroups');
    Route::post('{id}/add_lesson_to_group','LessonsController@addLessonTogroups');
    Route::get('/get-pivots','LessonsController@getPivots');
    Route::get('/get-standards','LessonsController@getStandards');
});
Route::get('/{lang}/lessons','LessonsController@index');



Route::get('/{lang}/lessonsbuilder/{id}/edit','LessonsController@lessonsbuilderedit')->name('lesson.editor')->middleware('teatcher');;
Route::get('/{lang}/lessonsviewer/{id}','LessonsController@lessonsViewer')->name('lesson.viewer');
Route::get('/{lang}/lessonsbuilder/{id}/viewmedia','LessonsController@viewmedia')->name('lesson.viewmedia');
Route::post('/{lang}/lessonsbuilder/{id}/save','LessonsController@lessonsbuildersave')->name('lesson.save');
Route::any('/{lang}/viewiframe','LessonsController@viewIframe');


Route::group(['prefix' => '{lang}/homework','middleware' => ['teatcher']], function () {
    Route::get('/viewadd','HomeworksController@viewAdd');
    Route::get('/{id}/viewmedia','HomeworksController@viewmedia');
    Route::post('/create','HomeworksController@create');
    Route::get('/delete','HomeworksController@delete');
    Route::post('/saveedit','HomeworksController@edit');
    Route::get('/addmymedia','HomeworksController@addmedia');
    Route::get('/deletemymedia','HomeworksController@deletemymedia');
    Route::get('/viewmymedia','HomeworksController@viewmymedia');
    Route::get('/{id}/viewassign','HomeworksController@viewassign');
    Route::get('/{id}/edit','HomeworksController@editor');//new by hussam - to show editor as lesson editor
    Route::get('/addassignhomework','HomeworksController@addassign');
    Route::get('/browseassignment','HomeworksController@browseassignment');
    Route::get('/{id}/{idtarget}/showresult','HomeworksController@showresult');

});
Route::get('/{lang}/homework','HomeworksController@index');
Route::get('/{lang}/homework/{id}/viewedit','HomeworksController@viewedit')->middleware('teatcher');

Route::get('/{lang}/homework/{id}','HomeworksController@homeworkViewer');//viewer by Hussam modify by hasan


Route::get('/{lang}/homework/showresult', function ($lang=null) {
    App::setLocale($lang);
    return view('homework.showresult');
});
Route::get('/{lang}/viewchat', function ($lang=null) {
    App::setLocale($lang);
    return view('viewchat');
});


// Localization
Route::get('{lan}/js/lang.js', function ($lan) {
    Cache::flush();
    $strings = Cache::rememberForever('lang.js', function () {
        $lang = config('app.locale');

        $files   = glob(resource_path('lang/' . $lang . '/*.php'));
        $strings = [];

        foreach ($files as $file) {
            $name           = basename($file, '.php');
            $strings[$name] = require $file;
        }

        return $strings;
    });

    header('Content-Type: text/javascript');
    echo('window.Lang = ' . json_encode($strings) . ';');
    exit();
})->name('assets.lang');

Route::group(['prefix' => '{lang}/teachers','middleware' => ['uptomanger']], function () {
    Route::any('/new','TeachersController@newuser');
    Route::any('/{id}/edit','TeachersController@edit');
    Route::any('/{id}/update','TeachersController@update');
    Route::any('/{id}/delete','TeachersController@delete');
    Route::any('/save','TeachersController@store');
    Route::any('/{id}/rest-password-teacher','TeachersController@restPsswordTeacher');
    Route::any('/{id}/rest-password','TeachersController@restPssword');

});

Route::any('/{lang}/teachers','TeachersController@index');
Route::any('/{lang}/teachers/filter','TeachersController@filter')->middleware('uptomanger');;
Route::any('/{lang}/teachers/{levelId}/show-teachers-level','TeachersController@showTeachersLevel')->middleware('uptomanger');;


// student cotroller routes meddileware teatcher
Route::group(['prefix' => '{lang}/students','middleware' => ['teatcher']], function () {
    Route::any('/new','StudentController@newuser');
    Route::any('/{id}/edit','StudentController@edit');
    Route::any('/{id}/update','StudentController@update');
    Route::any('/{id}/delete','StudentController@delete');
    Route::any('/save','StudentController@store');
    Route::any('/{id}/rest-password-student','StudentController@restPsswordStudent');
    Route::any('/{id}/rest-password','StudentController@restPssword');

});
Route::any('/{lang}/students','StudentController@index');
Route::any('/{lang}/students/filter','StudentController@filter')->middleware('upparent');
Route::any('/{lang}/students/childs-parent/{idParent}','StudentController@childsParent')->middleware('teatcher');
Route::any('/{lang}/students/{levelId}/show-standards-level','StudentController@showStudentsLevel');
Route::any('/{lang}/students/get-classes-level/{id}','StudentController@getClassesLevel');
Route::any('/{lang}/students/group/{groupId}','StudentController@getStudentGroup');

Route::group(['prefix' => '{lang}/classes','middleware' => ['uptomanger']], function () {
    Route::any('/new','ClassesController@create');
    Route::any('/save','ClassesController@store');
    Route::any('/{id}/edit','ClassesController@edit');
    Route::any('/{id}/update','ClassesController@update');
    Route::any('/{id}/delete','ClassesController@destroy');



});
Route::any('/{lang}/classes','ClassesController@index')->middleware('teatcher');
Route::any('/{lang}/classes/{id}/show','ClassesController@show')->middleware('teatcher');;
Route::any('/{lang}/classes/filter','ClassesController@filter')->middleware('teatcher');
Route::get('/{lang}/classes/send-message/{classid}','ClassesController@sendMessageView')->middleware('teatcher');
Route::post('/{lang}/classes/send-message/{classid}','ClassesController@sendMessageToClassStu')->middleware('teatcher');



Route::group(['prefix' => '{lang}/groups','middleware' => ['teatcher']], function () {
    Route::any('/new','GroupsController@create');
    Route::any('/save','GroupsController@store');
    Route::any('/{id}/edit','GroupsController@edit');
    Route::any('/update','GroupsController@update');
    Route::any('/{id}/delete','GroupsController@destroy');
    Route::any('/{id}/assign-student','GroupsController@assignStudent');
    Route::any('/get-classes-level/{id}','GroupsController@getClassesLevel');
    Route::any('/{group_id}/get-users-class/{level_id}/{class_id}','GroupsController@getUsersClass');
    Route::any('/{group_id}/assign-user/{id}','GroupsController@assignUser');
    Route::any('/{group_id}/delete-assign-user/{id}','GroupsController@deleteAssignUser');


});

Route::get('/{lang}/groups/send-message/{groupid}','GroupsController@sendMessageView');
Route::post('/{lang}/groups/send-message/{groupid}','GroupsController@sendMessageToGroupStu');
Route::any('/{lang}/groups','GroupsController@index');
Route::any('/{lang}/groups/{id}/show-teatcher-groups','GroupsController@showTeatcheGroups')->middleware('teatcher');



Route::group(['prefix' => '{lang}/domains','middleware' => ['uptomanger']], function () {
    Route::any('/new','DomainsController@create');
    Route::any('/save','DomainsController@store');
    Route::any('/{id}/edit','DomainsController@edit');
    Route::any('/{id}/update','DomainsController@update');
    Route::any('/{id}/delete','DomainsController@destroy');

});

Route::any('/{lang}/domains','DomainsController@index')->middleware('teatcher');
Route::any('/{lang}/domains/filter','DomainsController@filter')->middleware('uptomanger');

Route::get('/{lang}/domains/get-domains-category','DomainsController@getDomainsCategory');

Route::group(['prefix' => '{lang}/pivots','middleware' => ['uptomanger']], function () {
    Route::any('/new','PivotController@create');
    Route::any('/save','PivotController@store');
    Route::any('/{id}/edit','PivotController@edit');
    Route::any('/{id}/update','PivotController@update');
    Route::any('/{id}/delete','PivotController@destroy');

});
Route::any('/{lang}/pivots','PivotController@index')->middleware('teatcher');
Route::any('/{lang}/pivots/filter','PivotController@filter')->middleware('uptomanger');;
Route::any('/{lang}/pivots/{domain}/show-pivots-domain','PivotController@showPivotsDomain')->middleware('uptomanger');;
Route::get('/{lang}/pivots/get-pivots-domain','PivotController@getPivotsDomain');

Route::group(['prefix' => '{lang}/standards','middleware' => ['uptomanger']], function () {
    Route::any('/new','StandardsController@create');
    Route::any('/save','StandardsController@store');
    Route::any('/{id}/edit','StandardsController@edit');
    Route::any('/{id}/update','StandardsController@update');
    Route::any('/{id}/delete','StandardsController@destroy');

});
Route::any('/{lang}/standards','StandardsController@index')->middleware('teatcher');
Route::any('/{lang}/standards/{pivot_id}/show-standers-pivot','StandardsController@showStandersPivot');
Route::any('/{lang}/standards/{domain}/show-standards-domain','StandardsController@showStandersDomain');
Route::any('/{lang}/standards/filter','StandardsController@filter');
Route::get('/{lang}/standards/get-standards-pivot','StandardsController@getStandardsPivot');


Route::group(['prefix' => '{lang}/parents','middleware' => ['teatcher']], function () {
    Route::any('/','ParentController@index');
    Route::any('/new','ParentController@create');
    Route::any('/save','ParentController@store');
    Route::any('/{id}/edit','ParentController@edit');
    Route::any('/{id}/update','ParentController@update');
    Route::any('/{id}/delete','ParentController@destroy');
    Route::any('/{id}/rest-password-parent','ParentController@restPsswordParent');
    Route::any('/{id}/rest-password','ParentController@restPssword');
    Route::any('/childs/{id}','ParentController@childs');
    Route::any('/getStudent','ParentController@getUsersClass');
    Route::any('/{parent_id}/add-child/{student_id}','ParentController@addChild');
    Route::any('/{parent_id}/add-child/{student_id}','ParentController@addChild');
    Route::any('/delete-child','ParentController@deleteChild');

});



Route::group(['prefix' => '{lang}/competencies','middleware' => ['uptomanger']], function () {
    Route::any('/new','CompetenciesController@create');
    Route::any('/save','CompetenciesController@store');
    Route::any('/{id}/edit','CompetenciesController@edit');
    Route::any('/{id}/update','CompetenciesController@update');
    Route::any('/{id}/delete','CompetenciesController@destroy');

});
Route::any('/{lang}/competencies','CompetenciesController@index')->middleware('teatcher');;
Route::any('/{lang}/competencies/filter','CompetenciesController@filter')->middleware('uptomanger');;
Route::any('/{lang}/competencies/{pivot_id}/show-competencies-pivot','CompetenciesController@showCompetenciesPivot')->middleware('uptomanger');;
Route::any('/{lang}/competencies/{domain}/show-competencies-domain','CompetenciesController@showCompetenciesDomain')->middleware('uptomanger');;
Route::any('/{lang}/competencies/{standard}/show-competencies-standard','CompetenciesController@showCompetenciesStandard')->middleware('uptomanger');;


Route::group(['prefix' => '{lang}/badges','middleware' => ['teatcher']], function () {
    Route::any('/create','BadgesController@create');
    Route::any('/{id}/edit','BadgesController@edit');
    Route::post('/store','BadgesController@store');
    Route::any('/{id}/update','BadgesController@update');
    Route::any('/{id}/delete','BadgesController@destroy');
    Route::any('/{id}/assignLesson','BadgesController@assignLesson');


});
Route::any('/{lang}/badges/{id}/saveassign','BadgesController@saveassign');
Route::any('/{lang}/badges','BadgesController@index');
Route::any('/{lang}/badges/filter','BadgesController@filter');


//Auth guest
Route::group(['prefix' => '{lang}/login'], function () {
    Route::get('/','Auth\LoginController@login')->middleware('guest');
    Route::post('/', 'Auth\LoginController@postLogin')->middleware('guest');

});
Route::get('{lang}/forgotpassword', 'Auth\LoginController@forgotpassword')->middleware('guest');
Route::get('{lang}/confim-rest', 'Auth\ResetPasswordController@confrimTockenRestPass')->middleware('guest');
Route::Post('{lang}/confirmed', 'Auth\ResetPasswordController@confirmed');


Route::get('{lang}/new-password', 'Auth\ResetPasswordController@newPassword')->middleware('guest');
Route::Post('{lang}/new-password-user', 'Auth\ResetPasswordController@newPasswordUser')->middleware('guest');

Route::post('{lang}/new-password', 'Auth\ResetPasswordController@newPassword')->middleware('guest');
Route::post('{lang}/rest-pass', 'Auth\ResetPasswordController@restPassword')->middleware('guest');
//Logout
Route::post('{lang}/logout', [
    'uses' => 'Auth\LoginController@logout',
    'as' => 'logout'
]);

Route::get('{lang}/logout', [
    'uses' => 'Auth\LoginController@logout',
    'as' => 'logout'
])->middleware('guest');

Route::post('/{lang}/userprofile','UserProfileController@update');

Route::get('/{lang}/progress/parent','ProgressController@parent');
Route::get('/{lang}/progress/parentdetails','ProgressController@parentdetails');


Route::get('/{lang}/progress','ProgressController@index');

Route::get('/{lang}/progress/awards', 'ProgressController@awards');

Route::get('/{lang}/progress/class', 'ProgressController@class');

Route::get('/{lang}/progress/all', 'ProgressController@all');



Route::get('/{lang}/notifications/get','NotificationController@get');
Route::get('/{lang}/viewnotification','NotificationController@viewAll');
Route::get('/{lang}/notifications/read-last-not','NotificationController@readLastNotif');
Route::get('/{lang}/notifications/load-more','NotificationController@loadMore');


Route::get('/{lang}/messages/get-all','MessagesController@getAllMessages');
Route::get('/{lang}/messages/show-all','MessagesController@showAllMessages');
Route::get('/{lang}/viewchat','MessagesController@viewchat');
Route::post('/{lang}/messages/send-mess','MessagesController@sendMess');
Route::get('/{lang}/messages/get-last-mess','MessagesController@getLastMessages');
Route::get('/{lang}/messages/read-last-mess','MessagesController@readLastMessages');
Route::get('/{lang}/messages/load-more','MessagesController@loadMore');


Route::get('/{lang}/calender', 'CalendarController@index');
Route::get('/{lang}/calender/get-events', 'CalendarController@getEvents');

Route::group(['prefix' => '{lang}/curriculums','middleware' => ['uptomanger']], function () {
    Route::any('/add','CurriculumsController@create');
    Route::any('/save','CurriculumsController@store');
//Route::any('/{lang}/curriculums/getbooks','CurriculumsController@getBooksAPI');
    Route::get('/delete-and-add-lesson','CurriculumsController@deleteAndAddLesson');
    Route::any('/{id}/viewedit','CurriculumsController@viewedit');

    Route::any('/{id}/saveedit','CurriculumsController@saveedit');
    Route::any('/{id}/deleteCurriculum','CurriculumsController@delete_curr');

});
Route::any('/{lang}/curriculums','CurriculumsController@index');

Route::any('{lang}/quiz','QuizController@index');

Route::group(['prefix' => '{lang}/quiz','middleware' => ['teatcher']], function () {
    Route::get('/create','QuizController@create');
    Route::post('/store','QuizController@store');
    Route::get('/{id}/edit','QuizController@edit');
    Route::post('/{id}/update','QuizController@update');
    Route::delete('/{id}','QuizController@destroy');

    Route::any('/{idQuiz}/assign-to','QuizController@assignTo');
    Route::get('/get-assign-to/{type}','QuizController@getAssignTo');
    Route::any('/{idQuiz}/save-assign','QuizController@saveAssign');
    Route::get('/{idQuiz}/viewmedia','QuizController@viewMedia');
    Route::get('/add-media','QuizController@addMedia');
    Route::get('/get-all-media','QuizController@getAllMedia');
    Route::get('/get-my-media','QuizController@getMyMedia');
    Route::get('/deletemedia','QuizController@deletemedia');

    Route::get('/browseassignment','QuizController@browseassignment');
    Route::get('/{id}/{idtarget}/showresult','QuizController@showresult');

});
Route::get('/{lang}/quiz/{id}','QuizController@quizViewer');

Route::get('{lang}/quiz/get-groups','QuizController@getGroups');
Route::get('{lang}/quiz/get-students','QuizController@getStudents');
Route::get('{lang}/quiz/get-clasess','QuizController@getClasess');

Route::group(['prefix' => 'api'], function () {
    Route::get('learner_name','ApiControllers\Scorm@getusername');
    Route::get('success_status','ApiControllers\Scorm@setsuccess_status');
    Route::get('scoremax','ApiControllers\Scorm@setscoremax');
    Route::get('scoremin','ApiControllers\Scorm@setscoremin');
    Route::get('sessiontime','ApiControllers\Scorm@setsessiontime');
    Route::get('scoreraw','ApiControllers\Scorm@setscoreraw');
    Route::get('completionstatus','ApiControllers\Scorm@setcompletionstatus');
});

Route::group(['prefix' => 'api/homework-api'], function () {
    Route::get('/learner_name','ApiControllers\ScromHomework@getusername');
    Route::get('/success_status','ApiControllers\ScromHomework@setsuccess_status');
    Route::get('/scoremax','ApiControllers\ScromHomework@setscoremax');
    Route::get('/scoremin','ApiControllers\ScromHomework@setscoremin');
    Route::get('/sessiontime','ApiControllers\ScromHomework@setsessiontime');
    Route::get('/scoreraw','ApiControllers\ScromHomework@setscoreraw');
    Route::get('/completionstatus','ApiControllers\ScromHomework@setcompletionstatus');
});

// api for quiz
Route::group(['prefix' => 'api/qiz-api'], function () {
    Route::get('/learner_name','ApiControllers\ScromQuiz@getusername');
    Route::get('/success_status','ApiControllers\ScromQuiz@setsuccess_status');
    Route::get('/scoremax','ApiControllers\ScromQuiz@setscoremax');
    Route::get('/scoremin','ApiControllers\ScromQuiz@setscoremin');
    Route::get('/sessiontime','ApiControllers\ScromQuiz@setsessiontime');
    Route::get('/scoreraw','ApiControllers\ScromQuiz@setscoreraw');
    Route::get('/completionstatus','ApiControllers\ScromQuiz@setcompletionstatus');
});

Route::get('{lang}/quiz/{id}/view','QuizController@view');
Route::get('{lang}/quiz/get-groups','QuizController@getGroups');
Route::get('{lang}/quiz/get-students','QuizController@getStudents');
Route::get('{lang}/quiz/get-clasess','QuizController@getClasess');

Route::get('/{lang}/job/finish-job','JobController@finish');
