CREATE TABLE `monitoreo` (
  `idMonitoreo` varchar(6) NOT NULL,
  `idEvaluacion` varchar(6) NOT NULL,
  `idNumeral` varchar(10) NOT NULL,
  `evaluacion` float NOT NULL COMMENT '0=No, 1=Sí, 0.5=Parcialmente',
  `fecha_creado` datetime NOT NULL,
  `fecha_actualizado` datetime DEFAULT NULL,
  PRIMARY KEY (`idMonitoreo`),
  KEY `fk_numeral_monitoreo` (`idNumeral`),
  KEY `fk_empresa_monitoreo` (`idEvaluacion`),
  CONSTRAINT `fk_empresa_monitoreo` FOREIGN KEY (`idEvaluacion`) REFERENCES `evaluacion` (`idEvaluacion`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_numeral_monitoreo` FOREIGN KEY (`idNumeral`) REFERENCES `numeral` (`idNumeral`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



De esta tabla, hazme la consulta que me devuelva el resultado de la evaluacion por categoria.

como salida espero

categoria=numerales con nivel 0 [numeralPadre]
total=el resultado de los calculos.


mas o menos este seria el procedimiento para el calculo:

1-selecciona todos los numerales de nivel 0 (Seran los numerales padres)

2-Para el primer numeralpadre de la lista recuperada:
    2.1-selecciona los monitoreos de la Evaluacion elegida y con los numerales que sean hijos del numeralpadre recorrido
    2.2- cuenta cuantos numerales hijos tiene el numeral padre recorrido
        totalconteo=conteo de los numerales hijos del numeralPadre recorrido
    2.2- has los conteos de los numerales segun la respuesta:
        respuestas_si=conteo de los monitoreos con evaluacion=1
        respuestas_no=conteo de los monitoreos  con evaluacion=0
        respuestas_parcial=conteo de los monitoreos con evaluacion=0.5

    2.3 Calcula el resultado
        total= (respuestas_si*1 + respuestas_no*0+respuestas_parcial*0.5)/totalconteo

3.Repetir paso 2 para los siguientes numerales padres

Para eso has un recuento de los monitoreos de los numerales agrupados por los numerales de nivel 0 (esta sera la categoria)