<?php

use Illuminate\Database\Seeder;

class TimezoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('timezones')->insert([
            [
                'name' => 'Atlantic Standard Time (AST)',
                'abbr' => 'AST',
                'utc' => 'UTC-4',
            ],
            [
                'name' => 'Eastern Standard Time (EST)',
                'abbr' => 'EST',
                'utc' => 'UTC-5',
            ],
            [
                'name' => 'Central Standard Time (CST)',
                'abbr' => 'CST',
                'utc' => 'UTC-6',
            ],
            [
                'name' => 'Mountain Standard Time (MST)',
                'abbr' => 'MST',
                'utc' => 'UTC-7',
            ],
            [
                'name' => 'Pacific Standard Time (PST)',
                'abbr' => 'PST',
                'utc' => 'UTC-8',
            ],
            [
                'name' => 'Alaska Standard Time (AKST)',
                'abbr' => 'AKST',
                'utc' => 'UTC-9',
            ],
            [
                'name' => 'Hawaii-Aleutian Standard Time (HAST)',
                'abbr' => 'HAST',
                'utc' => 'UTC-10',
            ],
            [
                'name' => 'Samoa Standard Time',
                'abbr' => 'UTC-11',
                'utc' => 'UTC-11',
            ],
            [
                'name' => 'Chamorro Standard Time',
                'abbr' => 'UTC+10',
                'utc' => 'UTC+10',
            ],
        ]);
    }
}
