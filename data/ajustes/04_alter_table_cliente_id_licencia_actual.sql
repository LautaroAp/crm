/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  mica
 * Created: 26-sep-2018
 */
ALTER TABLE cliente add id_licencia_actual integer;
update cliente set id_licencia_actual = (select id_licencia from licencia as l where l.nombre = cliente.licencia_actual);


