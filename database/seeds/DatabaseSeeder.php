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
            'precios',
            'propiedads',
            'resultados',
            'turnos',
            'zonas',
            'clientes',
            'contactos'
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

        DB::statement('INSERT INTO venezueladdns (estado_zona, ciudad_sector, ddn)
                        SELECT estado_zona, ciudad_sector, ddn FROM pablo.venezueladdns;');
        DB::statement('CREATE INDEX ddn ON venezueladdns (ddn);');

        $this->call(ContactoSeeder::class);
        $this->call(TurnoSeeder::class);

        DB::statement('DROP VIEW vista_agenda;');

        DB::statement("create view vista_agenda as 
        (select c.id AS contacto_id, c.user_id AS user_id, date_format(c.fecha_evento, '%Y-%m-%d') AS fecha_evento,
                date_format(c.fecha_evento, '%H:%i') AS hora_evento, r.descripcion AS descripcion,
                c.name AS name, c.telefono AS telefono, c.email AS email, c.direccion AS direccion
         from (c21pr.contactos c join c21pr.resultados r on (r.id = c.resultado_id))
         where (c.resultado_id in (4,5,6,7)))
        union
        (select null AS contacto_id, t.user_id AS user_id, date_format(t.turno_en, '%Y-%m-%d') AS fecha_evento,
                if(('08' = date_format(t.turno_en, '%H')), 'Mañana', 'Tarde') AS hora_evento,
                'Turno en oficina' AS descripcion, '' AS name, '' AS telefono, '' AS email,
                '' AS direccion
         from c21pr.turnos t)
        order by 1,2;");
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
