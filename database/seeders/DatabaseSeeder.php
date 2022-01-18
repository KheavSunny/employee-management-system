<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            DepartmentSeeder::class,
            DivisionSeeder::class,
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            EmployeeSeeder::class
        ]);
    }
}
