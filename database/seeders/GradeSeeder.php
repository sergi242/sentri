<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = ["brigadier","brigadier-chef","adjudant","adjudant-chef","sous-lieutenant","lieutenant","capitaine","commandant","lieutenant-colonel","colonel"];

        foreach($data as $d){
            $in = Grade::where("grade",$d)->first();
            if($in == null){
                Grade::create([
                    "grade"=>$d
                ]);
            }
        }
    }
}
