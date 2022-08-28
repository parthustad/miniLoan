<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LoanStatus;
class LoanStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LoanStatus::create([
            'status' => 'PENDING'           
        ]);

       LoanStatus::create([
           'status' => 'APPROVED'
       ]);

      LoanStatus::create([
           'status' => 'PAID'
       ]);
    }
}
