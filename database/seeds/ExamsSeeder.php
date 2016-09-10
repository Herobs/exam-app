<?php

use Illuminate\Database\Seeder;
use App\Models\Exam;

class ExamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Exam::class, 200)->create();
    }
}
