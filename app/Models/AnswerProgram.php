<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerProgram extends Model
{
    /**
     * Indicates if the table should be timestamped(create_at and update_at).
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'answer_program';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['student', 'question'];
}
