/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  mica
 * Created: 26-sep-2018
 */
-- El spring va a ser 1 siempre
-- El fix es el numero de insert que haces, sirve para saber cuantos scripts insertamos

--Como haer un insert

-- INSERT INTO ajuste (script,spring,fix)
-- VALUES ("", 1, );

INSERT INTO ajuste (script,spring,fix)
VALUES ("01_cambio_cliente_pais_permite_null", 1, 1);

INSERT INTO ajuste (script,spring,fix)
VALUES ("02_cambio_pais_1_a_null_clientes", 1, 2);

INSERT INTO ajuste (script,spring,fix)
VALUES ("03_update_table_evento_tipo_evento", 1, 3);

INSERT INTO ajuste (script,spring,fix)
VALUES ("04_alter_table_cliente_id_licencia_actual", 1, 4);

INSERT INTO ajuste (script,spring,fix)
VALUES ("05_alter_table_producto_agregar_id", 1, 4);

