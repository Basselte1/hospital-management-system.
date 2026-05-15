<?php

namespace Database\Seeders; 
use Illuminate\Database\Seeder; 
use Illuminate\Support\Facades\DB; 

class VisitePreanesthesiquesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('visite_preanesthesiques')->delete();
        
        
        
    }
}