<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'email' => $faker->email,
    'name' => $faker->name,
    'city_id' => $faker->numberBetween(1, 2),
    'password' => $faker->password,
];