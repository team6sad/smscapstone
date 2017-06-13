<?php

use Illuminate\Database\Seeder;

class BarangayTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('barangay')->delete();
        
        \DB::table('barangay')->insert(array (
            0 => 
            array (
                'id' => 1,
                'district_id' => 1,
                'description' => 'Manila',
                'is_active' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'district_id' => 2,
                'description' => 'Moriones',
                'is_active' => 1,
            ),
        ));
        
        
    }
}