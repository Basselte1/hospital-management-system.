<?php

namespace Database\Seeders; 
use Illuminate\Database\Seeder; 
use Illuminate\Support\Facades\DB;

class SurveillanceScoresTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('surveillance_scores')->delete();
        
        
        
    }
}