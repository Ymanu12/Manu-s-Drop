<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <-- cette ligne est obligatoire !

class MonthSeeder extends Seeder
{
    public function run()
    {
        $months = [
            ['name' => 'January'],
            ['name' => 'February'],
            ['name' => 'March'],
            ['name' => 'April'],
            ['name' => 'May'],
            ['name' => 'June'],
            ['name' => 'July'],
            ['name' => 'August'],
            ['name' => 'September'],
            ['name' => 'October'],
            ['name' => 'November'],
            ['name' => 'December'],
        ];

        DB::table('month_names')->insert($months);
    }
}
