<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // add teachers
        $teacher = DB::table('teachers')->insertGetId([
            'name' => '超级管理员',
            'email' => 'herobs@herobs.cn',
            'password' => bcrypt('123456'),
            'tel' => '15988143063',
            'rights' => 4294967295,
        ]);
        // add exams
        $examStudentIdLimited = DB::table('exams')->insertGetId([
            'name' => '测试考试 - 特定学号',
            'start' => Carbon\Carbon::now()->subHours(5),
            'duration' => 60 * 60 * 2,
            'holder' => $teacher,
            'type' => 'student',
            'hidden' => 0
        ]);
        $examImportAccountLimited = DB::table('exams')->insertGetId([
            'name' => '测试考试 - 单独账号',
            'start' => Carbon\Carbon::now()->addHours(5),
            'duration' => 60 * 60 * 3,
            'holder' => $teacher,
            'type' => 'account',
            'hidden' => 0
        ]);
        $examPublicPasswordLimited = DB::table('exams')->insertGetId([
            'name' => '测试考试 - 公共密码',
            'start' => Carbon\Carbon::now(),
            'duration' => 60 * 60 * 5,
            'holder' => $teacher,
            'type' => 'password',
            'password' => '123456',
            'hidden' => 0
        ]);

        $exams = [
            $examStudentIdLimited,
            $examImportAccountLimited,
            $examPublicPasswordLimited,
        ];

        // add users
        $user = DB::table('users')->insertGetId([
            'name' => '何阳',
            'email' => 'herobs@herobs.cn',
            'password' => bcrypt('123456'),
            'student' => '13055415',
            'major' => '网络工程',
        ]);
        $user = DB::table('users')->find($user);
        // add students
        DB::table('students')->insert([
            [
                'exam' => $examStudentIdLimited,
                'student' => $user->student,
                'name' => '',
                'major' => '',
                'password' => null,
                'ip' => '0.0.0.0',
            ]
        ]);
        DB::table('students')->insert([
            [
                'exam' => $examImportAccountLimited,
                'student' => '13055415',
                'name' => '何阳',
                'major' => '网络工程',
                'password' => bcrypt('123456'),
                'ip' => '0.0.0.0',
            ]
        ]);

        // exam questions
        foreach ($exams as $exam) {
            // true-false questions
            $trueFalse = DB::table('questions')->insertGetId([
                'user' => $teacher,
                'exam' => $exam,
                'type' => 'true-false',
                'description' => '在 C 语言中，`int` 数据类型占用 4 字节内存。',
                'score' => 10,
                'source' => 0,
                'ref' => 0,
            ]);
            // insert answer
            DB::table('standard_true_false')->insert([
                [
                    'id' => $trueFalse,
                    'answer' => 'true',
                ]
            ]);

            $trueFalse = DB::table('questions')->insertGetId([
                'user' => $teacher,
                'exam' => $exam,
                'type' => 'true-false',
                'description' => 'PHP 是世界上最好的语言！！',
                'score' => 20,
                'source' => 0,
                'ref' => 0,
            ]);
            // insert answer
            DB::table('standard_true_false')->insert([
                [
                    'id' => $trueFalse,
                    'answer' => 'true',
                ]
            ]);

            // multi-choice questions
            $multiChoice = DB::table('questions')->insertGetId([
                'user' => $teacher,
                'exam' => $exam,
                'type' => 'multi-choice',
                'description' => '选出下列选项中的关系型数据库。',
                'score' => 10,
                'source' => 0,
                'ref' => 0,
            ]);
            DB::table('question_multi_choice')->insert([
                [
                    'id' => $multiChoice,
                    'order' => 0,
                    'option' => 'MySQL',
                ], [
                    'id' => $multiChoice,
                    'order' => 1,
                    'option' => 'MongoDB',
                ], [
                    'id' => $multiChoice,
                    'order' => 2,
                    'option' => 'PostgreSQL',
                ], [
                    'id' => $multiChoice,
                    'order' => 3,
                    'option' => 'Redis',
                ]
            ]);
            // insert answer
            DB::table('standard_multi_choice')->insert([
                [
                    'id' => $multiChoice,
                    'answer' => 5,
                ]
            ]);

            $multiChoice = DB::table('questions')->insertGetId([
                'user' => $teacher,
                'exam' => $exam,
                'type' => 'multi-choice',
                'description' => '下面哪些选项是 C 语言中的基本数据类型。',
                'score' => 10,
                'source' => 0,
                'ref' => 0,
            ]);
            DB::table('question_multi_choice')->insert([
                [
                    'id' => $multiChoice,
                    'order' => 0,
                    'option' => 'int',
                ], [
                    'id' => $multiChoice,
                    'order' => 1,
                    'option' => 'char',
                ], [
                    'id' => $multiChoice,
                    'order' => 2,
                    'option' => 'stack',
                ], [
                    'id' => $multiChoice,
                    'order' => 3,
                    'option' => 'boolean',
                ]
            ]);
            // insert answer
            DB::table('standard_multi_choice')->insert([
                [
                    'id' => $multiChoice,
                    'answer' => 3,
                ]
            ]);

            // blank-fill questions
            $blankFill = DB::table('questions')->insertGetId([
                'user' => $teacher,
                'exam' => $exam,
                'type' => 'blank-fill',
                'description' => '@@ 是 Google 开源的一款 Web 前端 MVVC 框架，目前的最新版本为 @@。',
                'score' => 10,
                'source' => 0,
                'ref' => 0,
            ]);
            DB::table('standard_blank_fill')->insert([
                [
                    'id' => $blankFill,
                    'order' => 0,
                    'answer' => 'Angular',
                ], [
                    'id' => $blankFill,
                    'order' => 1,
                    'answer' => 'r\'/^2|Angular 2$/\'',
                ],
            ]);
            // short-answer questions
            DB::table('questions')->insert([
                [
                    'user' => $teacher,
                    'exam' => $exam,
                    'type' => 'short-answer',
                    'description' => '请简述什么是软件。',
                    'score' => 20,
                    'source' => 0,
                    'ref' => 0,
                ]
            ]);
            // general questions
            DB::table('questions')->insert([
                [
                    'user' => $teacher,
                    'exam' => $exam,
                    'type' => 'general',
                    'description' => '使用你最擅长的语言设计一个[布隆过滤器](https://en.wikipedia.org/wiki/Bloom_filter)。',
                    'score' => 50,
                    'source' => 0,
                    'ref' => 0,
                ]
            ]);
            // program questions
            $program = DB::table('questions')->insertGetId([
                'user' => $teacher,
                'exam' => $exam,
                'type' => 'program',
                'description' => 'A + B',
                'score' => 50,
                'source' => 0,
                'ref' => 0,
            ]);
            DB::table('question_program')->insert([
                [
                    'id' => $program,
                    'title' => '简单的 A + B',
                    'output_limit' => 1024,
                    'special_judge' => 'false',
                    'test_case' => 2,
                ]
            ]);
            DB::table('program_limits')->insert([
                [
                    'id' => $program,
                    'type' => 'time',
                    'language' => 'default',
                    'value' => 1000,
                ], [
                    'id' => $program,
                    'type' => 'time',
                    'language' => 'Java',
                    'value' => 2000,
                ], [
                    'id' => $program,
                    'type' => 'memory',
                    'language' => 'default',
                    'value' => 65535,
                ]
            ]);
            DB::table('program_files')->insert([
                [
                    'id' => $program,
                    'case' => 1,
                    'type' => 'input',
                    'content' => '1 1',
                ], [
                    'id' => $program,
                    'case' => 1,
                    'type' => 'output',
                    'content' => '2',
                ], [
                    'id' => $program,
                    'case' => 2,
                    'type' => 'input',
                    'content' => '-1 -1',
                ], [
                    'id' => $program,
                    'case' => 2,
                    'type' => 'output',
                    'content' => '-2',
                ]
            ]);

            $program = DB::table('questions')->insertGetId([
                'user' => $teacher,
                'exam' => $exam,
                'type' => 'program',
                'description' => 'A + B 进阶',
                'score' => 100,
                'source' => 0,
                'ref' => 0,
            ]);
            DB::table('question_program')->insert([
                [
                    'id' => $program,
                    'title' => 'A + B 十六进制版',
                    'output_limit' => 1024,
                    'special_judge' => 'false',
                    'test_case' =>1,
                ]
            ]);
            DB::table('program_limits')->insert([
                [
                    'id' => $program,
                    'type' => 'time',
                    'language' => 'default',
                    'value' => 1000,
                ], [
                    'id' => $program,
                    'type' => 'memory',
                    'language' => 'default',
                    'value' => 65536,
                ], [
                    'id' => $program,
                    'type' => 'memory',
                    'language' => 'java',
                    'value' => 65535 * 2,
                ]
            ]);
            DB::table('program_files')->insert([
                [
                    'id' => $program,
                    'case' => 1,
                    'type' => 'input',
                    'content' => '1a 8',
                ], [
                    'id' => $program,
                    'case' => 1,
                    'type' => 'output',
                    'content' => '22',
                ]
            ]);

            // update all questions ref
            DB::table('questions')
                ->update(['ref' => DB::raw('id')]);

        }
        // generate lots of exams
        //$this->call(ExamsSeeder::class);
    }
}
