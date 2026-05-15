<?php

namespace Database\Seeders; 
use Illuminate\Database\Seeder; 
use Illuminate\Support\Facades\DB;

class DevisImagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('devis_images')->delete();
        
        
        
    }
}