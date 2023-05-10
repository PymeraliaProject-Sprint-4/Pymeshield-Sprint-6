<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'name' => 'Institut Montsià',
            'email' => 'info@iesmontsia.org',
            'phone' => '977700043',
            'cif' => 'A35864370',
        ]);

        #Company::factory(50)->create();
    }
}
