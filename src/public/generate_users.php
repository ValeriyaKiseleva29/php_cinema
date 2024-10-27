<?php

require_once __DIR__ . '/../../vendor/autoload.php';
use Faker\Factory;
use App\RMVC\Database\DB;

$faker = Factory::create();
$db = new DB();

for ($i = 0; $i < 1000; $i++) {
    $username = $faker->userName;
    $email = $faker->email;
    $password = password_hash('password', PASSWORD_DEFAULT);
    $age = $faker->numberBetween(18, 60);
    $dob = $faker->date('Y-m-d', '-18 years');
    $gender = $faker->randomElement(['male', 'female']);
    $interests = $faker->randomElement(['movies', 'music', 'sports']);


    $sql = "INSERT INTO users (username, email, password, dob, age, gender, interests) 
            VALUES (:username, :email, :password, :dob, :age, :gender, :interests)";

    $db->execute($sql, [
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'dob' => $dob,
        'age' => $age,
        'gender' => $gender,
        'interests' => json_encode([$interests])
    ]);

    echo "Добавлен пользователь: $username, $email\n";
}
