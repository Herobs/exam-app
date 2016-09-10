<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    /**
     * Indicates if the table should be timestamped(create_at and update_at).
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start'];

    // get session key
    public function getSessionKey()
    {
        return 'exam_' . $this->id;
    }
}
