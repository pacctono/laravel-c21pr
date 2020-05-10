INSERT INTO avisos (user_id, turno_id, tipo, fecha, descripcion, user_creo, created_at, updated_at)
SELECT user_id AS user_id, id AS turno_id, IF(ISNULL(llegada), 'C', 'T') AS tipo, turno AS fecha,
       IF(ISNULL(llegada), CONCAT('No se conecto en su turno de la <b>', IF(DATE_FORMAT(turno,'%H')='08', 'Mañana', 'Tarde'), DATE_FORMAT(turno, '</b> el <em>%d/%m/%Y</em>')),
                           CONCAT('Llego tarde el ', DATE_FORMAT(turno, '<em>%d/%m/%Y</em> en la <b>'), IF(DATE_FORMAT(turno,'%H')='08', 'Mañana', 'Tarde'), '</b> a las <em>', llegada, '</em>'))
          AS descripcion, 1, now(), now()
FROM turnos
WHERE (turno BETWEEN '2020-01-01' AND NOW())
 AND  (ISNULL(llegada) OR
      ((DATE_FORMAT(turno,'%H') = '08') AND (llegada > '09:00')) OR
      ((DATE_FORMAT(turno,'%H') = '12') AND (llegada > '13:00')))

