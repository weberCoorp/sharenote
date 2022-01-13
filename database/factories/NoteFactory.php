<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Note;
use App\User;
use Faker\Generator as Faker;

$factory->define(Note::class, function (Faker $faker) {
    $user = User::query()->inRandomOrder()->first()->id;
    return [
        'name'        => $faker->sentence($nbWords = 3, $variableNbWords = true),
        'description' => $faker->text($maxNbChars = 300),
        'private'      => rand(0, 1),
        'user_id'     => User::query()->inRandomOrder()->first()->id,
    ];

});

$factory->afterCreating(Note::class, function ($note, $faker) {
    if($note->private){
        $users = User::where('id', '!=', $note->user_id)->inRandomOrder()->limit(3)->get();
        $note->usersHasAccess()->attach($users);
    }

});
