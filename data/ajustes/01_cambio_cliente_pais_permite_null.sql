/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  mica
 * Created: 26-sep-2018
 */
ALTER TABLE `bd_crm`.`cliente` 
DROP FOREIGN KEY `fk_cliente_pais`;
ALTER TABLE `bd_crm`.`cliente` 
CHANGE COLUMN `id_pais` `id_pais` INT(11) NULL DEFAULT '1' ;
ALTER TABLE `bd_crm`.`cliente` 
ADD CONSTRAINT `fk_cliente_pais`
  FOREIGN KEY (`id_pais`)
  REFERENCES `bd_crm`.`pais` (`id_pais`);
