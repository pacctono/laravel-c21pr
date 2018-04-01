<?php

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
        $this->truncateTables([
            'users',
            'deseos',
            'origens',
            'propiedads',
            'resultados',
            'zonas',
            'clientes'
        ]);

        // $this->call(UsersTableSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(DeseoSeeder::class);
        $this->call(OrigenSeeder::class);
        $this->call(PrecioSeeder::class);
        $this->call(PropiedadSeeder::class);
        $this->call(ResultadoSeeder::class);
        $this->call(ZonaSeeder::class);
        $this->call(ClienteSeeder::class);

        DB::statement('INSERT INTO venezueladdns (estado_zona, ciudad_sector, ddn) SELECT estado_zona, ciudad_sector, ddn FROM pablo.venezueladdns;');
        DB::statement('CREATE INDEX ddn ON venezueladdns (ddn);');
    }

    protected function truncateTables(array $tables)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
 
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
