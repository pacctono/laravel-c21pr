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
            //'users',
            'deseos',
            'origens',
            'precios',
            'tipos',
            'resultados',
            'zonas',
            'clientes',
            'caracteristicas',
            'municipios',
            'estados',
            'ciudades',
            //'turnos',
            //'contactos'
        ]);

        //$this->call(UserSeeder::class);
        $this->call(DeseoSeeder::class);
        //$this->call(OrigenSeeder::class);
        $this->call(PrecioSeeder::class);
        $this->call(TipoSeeder::class);
        $this->call(ResultadoSeeder::class);
        $this->call(ZonaSeeder::class);
        $this->call(CaracteristicaSeeder::class);
        $this->call(MunicipioSeeder::class);
        $this->call(EstadoSeeder::class);
        $this->call(CiudadSeeder::class);
        //this->call(ClienteSeeder::class);

        DB::statement('INSERT INTO venezueladdns (estado_zona, ciudad_sector, ddn)
                        SELECT estado_zona, ciudad_sector, ddn FROM pablo.venezueladdns;');
        DB::statement('CREATE INDEX ddn ON venezueladdns (ddn);');

        //$this->call(ContactoSeeder::class);
        //$this->call(TurnoSeeder::class);

        DB::statement('DROP VIEW IF EXISTS vista_agenda;');

        DB::statement("create view vista_agenda as 
        (select c.id AS contacto_id, c.user_id AS user_id, 'C' AS tipo,
                date_format(c.fecha_evento, '%Y-%m-%d') AS fecha_evento,
                date_format(c.fecha_evento, '%H:%i') AS hora_evento, r.descripcion AS descripcion,
                c.name AS name, c.telefono AS telefono, c.email AS email, c.direccion AS direccion
         from (contactos c join resultados r on (r.id = c.resultado_id))
         where (c.resultado_id in (4,5,6,7)))
        union
        (select null AS contacto_id, t.user_id AS user_id, 'T' AS tipo,
                date_format(t.turno, '%Y-%m-%d') AS fecha_evento,
                if(('08' = date_format(t.turno, '%H')), 'Mañana', 'Tarde') AS hora_evento,
                'Turno en oficina' AS descripcion, null AS name, null AS telefono, null AS email,
                null AS direccion
         from turnos t)
        union
        (select id AS contacto_id, a.user_id AS user_id, 'A' AS tipo, a.fecha_cita AS fecha_evento,
                a.hora_cita AS hora_evento, a.descripcion AS descripcion, a.name AS name,
		a.telefono AS telefono, a.email AS email, a.direccion AS direccion
         from agenda_personals a)
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
