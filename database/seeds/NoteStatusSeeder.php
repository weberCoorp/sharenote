<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NoteStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            'Public',
            'Private',
        ];
        foreach($statuses as $status){
            DB::table('note_statuses')->insert([
                'slug' => Str::slug($status,'_'),
                'name' => $status,
            ]);
        }
    }
}
