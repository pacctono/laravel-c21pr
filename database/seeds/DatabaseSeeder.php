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
            'prices',
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
        $this->call(PriceSeeder::class);
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

        DB::statement("CREATE VIEW vista_agenda as 
        (SELECT c.id AS contacto_id, c.user_id AS user_id, 'C' AS tipo,
                DATE_FORMAT(c.fecha_evento, '%Y-%m-%d') AS fecha_evento,
                DATE_FORMAT(c.fecha_evento, '%H:%i') AS hora_evento,
                r.descripcion AS descripcion, c.name AS name, c.telefono AS telefono,
                c.email AS email, c.direccion AS direccion
         FROM (contactos c JOIN resultados r ON (R.id = c.resultado_id))
         WHERE (c.resultado_id in (4,5,6,7))
          AND  c.deleted_at IS NULL)
        UNION
        (SELECT NULL AS contacto_id, t.user_id AS user_id, 'T' AS tipo,
                DATE_FORMAT(t.turno, '%Y-%m-%d') AS fecha_evento,
                IF(('08' = DATE_FORMAT(t.turno, '%H')), 'Mañana', 'Tarde') AS hora_evento,
                'Turno en oficina' AS descripcion, NULL AS name, NULL AS telefono,
                NULL AS email, NULL AS direccion
         FROM turnos t
         WHERE t.deleted_at IS NULL)
        UNION
        (SELECT id AS contacto_id, a.user_id AS user_id, 'A' AS tipo,
                a.fecha_cita AS fecha_evento, a.hora_cita AS hora_evento,
                a.descripcion AS descripcion,
                IF(0<IFNULL(contacto_id,0),
                    (SELECT name FROM contactos c WHERE c.id=a.contacto_id),
                        IF(0<IFNULL(cliente_id,0),
                            (SELECT name FROM clientes c WHERE c.id=a.cliente_id),
                            a.name)) AS name,
		        a.telefono AS telefono, a.email AS email, a.direccion AS direccion
         FROM agenda_personals a
         WHERE a.deleted_at IS NULL)
        ORDER BY 1,2;");

        DB::statement('DROP VIEW IF EXISTS vista_clientes;');

        DB::statement("CREATE VIEW vista_clientes as
        (SELECT id, cedula, '' AS rif, name, 'I' AS tipo, telefono, otro_telefono, user_id,
                email, fecha_evento, direccion, observaciones
         FROM contactos i
         WHERE id NOT IN (SELECT contacto_id FROM clientes c WHERE c.contacto_id = i.id)
          AND  cedula NOT IN (select c.cedula FROM clientes c WHERE c.cedula = i.cedula)
          AND  i.deleted_at IS NULL)
        UNION
        (SELECT id, cedula, rif, name, tipo, telefono, otro_telefono, user_id, email,
                fecha_nacimiento AS fecha_evento, direccion, observaciones
         FROM clientes
         WHERE deleted_at IS NULL)
        ORDER BY name;");
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
