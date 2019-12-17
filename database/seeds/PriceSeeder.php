<?php

use Illuminate\Database\Seeder;

class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Price::create([
            'menor' => 0.00,
            'mayor' => 5000.00
        ]);
    
        Price::create([
            'menor' => 5000,01,
            'mayor' => 10000.00
        ]);
    
        Price::create([
            'menor' => 10000.01,
            'mayor' => 20000.00
        ]);
    
        Price::create([
            'menor' => 20000.01,
            'mayor' => 30000.00
        ]);
    
        Price::create([
            'menor' => 30000.01,
            'mayor' => 40000.00
        ]);
    
        Price::create([
            'menor' => 40000.01,
            'mayor' => 50000.00
        ]);
    
        Price::create([
            'menor' => 50000.01,
            'mayor' => 60000.00
        ]);
    
        Price::create([
            'menor' => 60000.01,
            'mayor' => 70000.00
        ]);
    
        Price::create([
            'menor' => 70000.01,
            'mayor' => 80000.00
        ]);
    
        Price::create([
            'menor' => 80000.01,
            'mayor' => 90000.00
        ]);
    
        Price::create([
            'menor' => 90000.01,
            'mayor' => 100000.00
        ]);
    
        Price::create([
            'menor' => 100000.01,
            'mayor' => 200000.00
        ]);
    
        Price::create([
            'menor' => 200000.01,
            'mayor' => 300000.00
        ]);
    
        Price::create([
            'menor' => 300000.01,
            'mayor' => 400000.00
        ]);
    
        Price::create([
            'menor' => 400000.01,
            'mayor' => 500000.00
        ]);
    
        Price::create([
            'menor' => 500000.01,
            'mayor' => 600000.00
        ]);
    
        Price::create([
            'menor' => 600000.01,
            'mayor' => 700000.00
        ]);

        Price::create([
            'menor' => 700000.01,
            'mayor' => 800000.00
        ]);

        Price::create([
            'menor' => 800000.01,
            'mayor' => 900000.00
        ]);

        Price::create([
            'menor' => 900000.01,
            'mayor' => 1000000.00
        ]);

        Price::create([
            'menor' => 1000000.01,
            'mayor' => 5000000.00
        ]);

        Price::create([
            'menor' => 5000000.01,
            'mayor' => 10000000.00
        ]);

        Price::create([
            'menor' => 10000000.01,
            'mayor' => 100000000.00
        ]);

        Price::create([
            'menor' => 100000000.01,
            'mayor' => 1000000000.00
        ]);
    }
}
