<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\scan;
use App\Models\slotitem;
use App\Models\parentScan;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        parentScan::create(['name' => 'X-Ray (Radiography)']);
        $data = [
            ["name" => "Urniary Tract Scan", "price" => "150", 'scan_category_id' => 1,],
            ["name" => "Appendix & Bowel Scan", "price" => "175", 'scan_category_id' => 1,],
            ["name" => "Testicular Scan", "price" => "150", 'scan_category_id' => 1,],
            ["name" => "Fertility & IVF Scan", "price" => "150", 'scan_category_id' => 1,],
            ["name" => "PMB Ultrasound", "price" => "150", 'scan_category_id' => 1,],
            ["name" => "Upper Abdomen Scan", "price" => "150", 'scan_category_id' => 1,],
            ["name" => "Abdomen & Pelvic Scan", "price" => "220", 'scan_category_id' => 1,],
            ["name" => "Hernia Scan", "price" => "150", 'scan_category_id' => 1,],
            ["name" => "Pelvic Ultrasound Scan", "price" => "175", 'scan_category_id' => 1,],
        ];


        foreach ($data as $item) {
            scan::create([
                'name' => $item['name'],
                'price' => $item['price'],
                'scan_category_id' => $item['scan_category_id'],
            ]);
        }

        $slotPrice = [
            ['time' => '00:00', 'price' => 519.40],
            ['time' => '00:45', 'price' => 519.40],
            ['time' => '01:30', 'price' => 519.40],
            ['time' => '02:15', 'price' => 519.40],
            ['time' => '03:00', 'price' => 519.40],
            ['time' => '03:45', 'price' => 519.40],
            ['time' => '04:30', 'price' => 519.40],
            ['time' => '05:15', 'price' => 519.40],
            ['time' => '06:00', 'price' => 445.20],
            ['time' => '06:45', 'price' => 445.20],
            ['time' => '07:30', 'price' => 445.20],
            ['time' => '08:15', 'price' => 445.20],
            ['time' => '09:00', 'price' => 445.20],
            ['time' => '09:45', 'price' => 445.20],
            ['time' => '10:00', 'price' => 341.32],
            ['time' => '10:45', 'price' => 371.00],
            ['time' => '11:30', 'price' => 371.00],
            ['time' => '12:15', 'price' => 371.00],
            ['time' => '13:00', 'price' => 371.00],
            ['time' => '13:45', 'price' => 371.00],
            ['time' => '14:30', 'price' => 371.00],
            ['time' => '15:15', 'price' => 371.00],
            ['time' => '16:00', 'price' => 445.20],
            ['time' => '16:45', 'price' => 445.20],
            ['time' => '17:30', 'price' => 445.20],
            ['time' => '18:15', 'price' => 445.20],
            ['time' => '19:00', 'price' => 445.20],
            ['time' => '19:45', 'price' => 445.20],
            ['time' => '20:30', 'price' => 445.20],
            ['time' => '21:00', 'price' => 482.30],
            ['time' => '21:45', 'price' => 482.30],
            ['time' => '22:30', 'price' => 482.30],
            ['time' => '23:15', 'price' => 482.30],
        ];

        foreach ($slotPrice as $item) {
            slotitem::create([
                'time' => $item['time'],
                'price' => $item['price'],
            ]);
        }
    }
}
