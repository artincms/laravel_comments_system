<?php
namespace ArtinCMS\LCS\Database\Seeds;
use Illuminate\Database\Seeder;

class LmmMorphableTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('lmm_morphable')->delete();
        
        \DB::table('lmm_morphable')->insert(array (
            0 =>
                array (
                    'id' => 3,
                    'pck_name' => 'lcm',
                    'dev_name' => 'comment_model',
                    'name' => 'نظرات',
                    'model_name' => 'ArtinCMS\\LCS\\Model\\Comment',
                    'target_column_name' => 'comment',
                    'target_column_alias' => 'نظرات',
                    'generate_url_func' => NULL,
                    'created_by' => 0,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
        ));
        
        
    }
}