<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
$questions = 'true-false|multi-choice|blank-fill|short-answer|general|program';

// index
Route::get('/', function() {
    return redirect('/exams/');
});
// add auth routes(/login, /register, /password/reset ...)
Route::auth();
Route::get('/home', function() {
    return redirect('/');
});

// exam list
Route::get('/exams/{type?}', 'Exam\Exam@getExams')
    ->where('type', 'all|pending|running|ended');
// exam auth
Route::group([
    'namespace' => 'Exam',
    'prefix' => 'exam/{exam}',
], function() {
    // exam login with import accounts and public password
    Route::get('login', 'AuthController@getLogin');
    Route::post('login', 'AuthController@postLogin');
    // exam logout
    Route::get('logout', 'AuthController@getLogout');
    // exam forbidden
    Route::get('forbidden', 'AuthController@getForbidden');
});
// exam routes
Route::group([
    'namespace' => 'Exam',
    'middleware' => 'auth.exam',
    'prefix' => 'exam/{exam}',
], function() {
    // exam infomation
    Route::get('/', 'Exam@getIndex');
    // true-false question
    Route::get('true-false', 'TrueFalseQuestion@show');
    Route::post('true-false', 'TrueFalseQuestion@save');
    // multi-choice question
    Route::get('multi-choice', 'MultiChoiceQuestion@show');
    Route::post('multi-choice', 'MultiChoiceQuestion@save');
    // blank-fill question
    Route::get('blank-fill', 'BlankFillQuestion@show');
    Route::post('blank-fill', 'BlankFillQuestion@save');
    // short-answer question
    Route::get('short-answer', 'ShortAnswerQuestion@show');
    Route::post('short-answer', 'ShortAnswerQuestion@save');
    // general question
    Route::get('general', 'GeneralQuestion@show');
    Route::post('general', 'GeneralQuestion@save');
    // program-blank-fill question
    // Route::get('program-blank-fill', 'ProgramBlankFillQuestion@show');
    // Route::post('program-blank-fill', 'ProgramBlankFillQuestion@save');
    // program question
    Route::get('program', 'ProgramQuestion@index');
    Route::get('program/{question}', 'ProgramQuestion@show');
    Route::post('program/{question}', 'ProgramQuestion@save');
});

// admin auth
Route::group([
    'namespace' => 'Admin\Auth',
    'prefix' => 'admin',
], function() {
    // admin login
    Route::get('login', 'AuthController@getLogin');
    Route::post('login', 'AuthController@postLogin');
    // admin logout
    Route::get('logout', 'AuthController@getLogout');
    // admin register
    Route::get('register', 'AuthController@getRegister');
    Route::post('register', 'AuthController@postRegister');
    // admin password reset
    Route::get('password/reset/{token?}', 'PasswordController@getReset');
    Route::post('password/email', 'PasswordController@postEmail');
    Route::post('password/reset', 'PasswordController@postReset');
});
// admin routes
Route::group([
    'namespace' => 'Admin',
    'prefix' => 'admin',
    'middleware' => 'auth.admin',
], function() use ($questions) {
    // admin index
    Route::get('/', 'Admin@index');
    // all exams holder by cureent admin
    Route::get('exams', 'Exam@index');
    // exam infomation
    Route::get('exam/new', 'Exam@create');
    Route::post('exam', 'Exam@save');
    Route::get('exam/{exam}/delete', 'Exam@delete');
    Route::get('exam/{exam}', 'Exam@get');
    Route::group([
        'namespace' => 'Exam',
        'prefix' => 'exam/{exam}',
    ], function() use ($questions) {
        // students
        Route::get('student', 'Student@index');
        Route::get('student/{student}', 'Student@get');
        Route::get('student/new', 'Student@create');
        Route::post('student', 'Student@save');
        Route::get('student/{student}/delete', 'Student@delete');
        Route::get('student/clear', 'Student@clear');
        Route::get('student/import', 'Student@importForm');
        Route::post('student/import', 'Student@import');
        Route::get('student/export', 'Student@export');

        // true-false questions
        Route::get('true-false/{question}', 'TrueFalseQuestion@get');
        Route::get('true-false/{question}/delete', 'TrueFalseQuestion@delete');
        // multi-choice questions
        Route::get('multi-choice/{question}', 'MultiChoiceQuestion@get');
        Route::get('multi-choice/{question}/delete', 'MultiChoiceQuestion@delete');
        // blank-fill questions
        Route::get('blank-fill/{question}', 'BlankFillQuestion@get');
        Route::get('blank-fill/{question}/delete', 'BlankFillQuestion@delete');
        // short-answer questions
        Route::get('short-answer/{question}', 'ShortAnswerQuestion@get');
        Route::get('short-answer/{question}/delete', 'ShortAnswerQuestion@delete');
        // blank-fill questions
        Route::get('general/{question}', 'GeneralQuestion@get');
        Route::get('general/{question}/delete', 'GeneralQuestion@delete');
        // program questions
        Route::get('program', 'ProgramQuestion@index');
        Route::get('program/{question}', 'ProgramQuestion@get');
        Route::get('program/{question}/delete', 'ProgramQuestion@delete');
        // testcase
        Route::get('program/{question}/testcase', 'TestCase@index');
        Route::get('program/{question}/testcase/new', 'TestCase@create');
        Route::get('program/{question}/testcase/{testcase}', 'TestCase@get');
        Route::post('program/{question}/testcase', 'TestCase@save');
        Route::get('program/{question}/testcase/{testcase}/delete', 'TestCase@delete');

        // get specific type questions of an exam
        Route::get('{type}', 'Question@index')
            ->where('type', $questions);
        // get create from for specific question type
        Route::get('{type}/new', 'Question@create')
            ->where('type', $questions);
        // save specific type question
        Route::post('{type}', 'Question@save');

        // import questions
        Route::get('import/{category}/{type}', 'Import@index')
            ->where('category', 'public|private')
            ->where('type', $questions);
        Route::get('import/{category}/{type}/{question}', 'Import@import')
            ->where('category', 'public|private')
            ->where('type', $questions);
    });

    // questions
    // redirect if specific question type not exists
    Route::get('{category}', 'Question@redirect')
        ->where('category', 'public|private');
    Route::group([
        'namespace' => 'Question',
        'prefix' => '{category}'
    ], function($group) {
        // true-false questions
        Route::get('true-false/{question}', 'TrueFalseQuestion@get');
        Route::get('true-false/{question}/delete', 'TrueFalseQuestion@delete');
        // multi-choice questions
        Route::get('multi-choice/{question}', 'MultiChoiceQuestion@get');
        Route::get('multi-choice/{question}/delete', 'MultiChoiceQuestion@delete');
        // blank-fill questions
        Route::get('blank-fill/{question}', 'BlankFillQuestion@get');
        Route::get('blank-fill/{question}/delete', 'BlankFillQuestion@delete');
        // short-answer questions
        Route::get('short-answer/{question}', 'ShortAnswerQuestion@get');
        Route::get('short-answer/{question}/delete', 'ShortAnswerQuestion@delete');
        // general questions
        Route::get('general/{question}', 'GeneralQuestion@get');
        Route::get('general/{question}/delete', 'GeneralQuestion@delete');
        // program questions
        Route::get('program/{question}', 'ProgramQuestion@get');
        Route::get('program/{question}/delete', 'ProgramQuestion@delete');
        // testcase
        Route::get('program/{question}/testcase', 'TestCase@index');
        Route::get('program/{question}/testcase/new', 'TestCase@create');
        Route::get('program/{question}/testcase/{testcase}', 'TestCase@get');
        Route::post('program/{question}/testcase', 'TestCase@save');
        Route::get('program/{question}/testcase/{testcase}/delete', 'TestCase@delete');

        foreach ($group->getRoutes() as $route) {
            $route->where('category', 'public|private');
        }
    });
    // list all specific type of questions
    Route::get('{category}/{type}', 'Question@index')
        ->where('category', 'public|private')
        ->where('type', $questions);
    // get create form for specific question type
    Route::get('{category}/{type}/new', 'Question@create')
        ->where('category', 'public|private')
        ->where('type', $questions);
    // save specific type question
    Route::post('{category}/{type}', 'Question@save')
        ->where('category', 'public|private')
        ->where('type', $questions);

    Route::get('teachers', 'Teacher@index');
    Route::get('teacher/new', 'Teacher@create');
    Route::get('teacher/{teacher}', 'Teacher@get');
    Route::post('teacher', 'Teacher@save');

    // edit profile
    Route::get('edit', 'Admin@get');
    Route::post('edit', 'Admin@save');
});
