<?php

return [
    /*
     |-------------------------------------------------------------------------
     | Judge Base Uri
     |-------------------------------------------------------------------------
     | The judge base uri, can be found at http://opjs.coder.tips/
     */

    'uri' => env('JUDGE_URI', 'http://localhost'),

    /*
     |-------------------------------------------------------------------------
     | Judge ID
     |-------------------------------------------------------------------------
     | The judge ID
     */

    'id' => env('JUDGE_ID', 'test'),

    /*
     |-------------------------------------------------------------------------
     | Judge SECRET
     |-------------------------------------------------------------------------
     | The judge api SECRET
     */

    'secret' => env('JUDGE_SECRET', 'test'),

    /*
     |-------------------------------------------------------------------------
     | Judge Language Available
     |-------------------------------------------------------------------------
     | The Languages available in this account
     */
    'languages' => [
        'c' => 'c',
        'cpp' => 'c++',
        'java' => 'java',
    ],

    /*
     |-------------------------------------------------------------------------
     | Max time limit
     |-------------------------------------------------------------------------
     | The max time limit allowed, default is 10s
     | Set to long will slow judge speed, for majority student programs,
     | 2s - 3s is reasonable
     */
    'max_time_limit' => 10000,

    /*
     |-------------------------------------------------------------------------
     | Max memory limit
     |-------------------------------------------------------------------------
     | The max memory limit allowed, default is 256M
     | Set to long will slow judge speed, for majority student programs,
     | 64M is reasonable
     */
    'max_memory_limit' => 256 * 1024 * 1024,

    /*
     |-------------------------------------------------------------------------
     | Max output limit
     |-------------------------------------------------------------------------
     | The max output limit allowed, default is 8M(in kilobytes)
     | Set to long will slow judge speed, for majority student programs,
     | 64M is reasonable
     */
    'max_output_limit' => 8 * 1024,

    /*
     |-------------------------------------------------------------------------
     | Max Testcase
     |-------------------------------------------------------------------------
     | The max tests allowed, default is 50
     | Set to long will slow judge speed, for majority student programs,
     | 5 - 10 groups is reasonable
     */
    'max_testcase' => 50,

    /*
     |-------------------------------------------------------------------------
     | Max Testcase
     |-------------------------------------------------------------------------
     | The max test file size allowed, default is 2M
     */
    'max_file_size' => 2 * 1024 * 1024,
];
