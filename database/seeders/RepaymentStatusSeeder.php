<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RepaymentStatus;
class RepaymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RepaymentStatus::create([
            'status' => 'UNPAID'           
        ]);

        RepaymentStatus::create([
           'status' => 'PAID'
       ]);
    }
}
