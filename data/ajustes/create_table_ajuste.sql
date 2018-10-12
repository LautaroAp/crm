/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  mica
 * Created: 26-sep-2018
 */
create table ajuste(
id integer not null auto_increment,
script varchar(255),
fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
spring integer,
fix integer,
constraint pk_ajuste primary key (id)
);
