UPDATE propiedads
SET fecha_inicial = IF(created_at<=IF(IFNULL(fecha_reserva, '2099-12-31')<=IFNULL(fecha_firma, '2099-12-31'),
					IFNULL(fecha_reserva, '2099-12-31'),
					IFNULL(fecha_firma, '2099-12-31')
				),
			created_at,
			IF(IFNULL(fecha_reserva, '2099-12-31')<=IFNULL(fecha_firma, '2099-12-31'),
				IFNULL(fecha_reserva, '2099-12-31'),
				IFNULL(fecha_firma, '2099-12-31')
			)
		)
