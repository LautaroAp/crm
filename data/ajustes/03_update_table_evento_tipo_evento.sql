/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  mica
 * Created: 26-sep-2018
 */

update evento set tipo_evento = 2 where tipo_evento=3 or tipo_evento=4;
update evento set tipo_evento = 3 where tipo_evento=6 or tipo_evento=7;
update evento set tipo_evento = 4 where tipo_evento=2 or tipo_evento=5 or tipo_evento=13;
update evento set tipo_evento = 5 where tipo_evento=11 or tipo_evento=12;
update evento set tipo_evento = 6 where tipo_evento=8 or tipo_evento=9;
update evento set tipo_evento = 7 where tipo_evento=10 or tipo_evento=14 or tipo_evento=16;

alter table tipo_evento drop column descripcion;

update tipo_evento set nombre ="Email" where id_tipo_evento =2;
update tipo_evento set nombre ="Llamada" where id_tipo_evento =3;
update tipo_evento set nombre ="Pago" where id_tipo_evento =4;
update tipo_evento set nombre ="Soporte" where id_tipo_evento =5;
update tipo_evento set nombre ="Cotizacion" where id_tipo_evento =6;
update tipo_evento set nombre ="Otro" where id_tipo_evento =7;


delete from tipo_evento where id_tipo_evento > 7;