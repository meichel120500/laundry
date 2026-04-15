<?php

namespace Database\Seeders;

use App\Models\TypeOfService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [ 
            [
                'service_name' => 'Cuci dan Gosok',
                'price' => 5000,
                'description' => 'Pakaian Di cuci sekaligus di setrika'
            ],
            [
                'service_name' => 'Cuci Kering',
                'price'=> 4500,
                'description'=> 'Pakaian Hanya dicuci saja'
            ],
            [
                'service_name' => 'Hanya Gosok',
                'price'=> 5000,
                'description'=> 'Pakaian Hanya di setrika saja'
            ],
            [
                 'service_name'=> 'Laundry Besar',
                 'price' => 7000,
                 'description' => 'Khusus untuk mencuci Selimut, Karpet, Mantel dan Sprei'
            ],
            ];

            foreach ($services as $service){
                TypeOfService::create($service);
            }
    }
}
