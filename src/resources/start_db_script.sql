CREATE SCHEMA IF NOT EXISTS `alexis_gda_api` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `alexis_gda_api`;

DROP TABLE IF EXISTS `alexis_gda_api`.`regions`;

CREATE TABLE IF NOT EXISTS `alexis_gda_api`.`regions` (
`id_reg` INT NOT NULL AUTO_INCREMENT COMMENT '', `description` VARCHAR(90)
NOT NULL COMMENT '',
`status` ENUM('A', 'I', 'trash') NOT NULL DEFAULT 'A' COMMENT '', PRIMARY KEY
(`id_reg`) COMMENT '')
ENGINE = MyISAM;

DROP TABLE IF EXISTS `alexis_gda_api`.`communes`;

CREATE TABLE IF NOT EXISTS `alexis_gda_api`.`communes` (
`id_com` INT NOT NULL AUTO_INCREMENT COMMENT '', `id_reg` INT NOT NULL
COMMENT '',
`description` VARCHAR(90) NOT NULL COMMENT '',
`status` ENUM('A', 'I', 'trash') NOT NULL DEFAULT 'A' COMMENT '', PRIMARY KEY
(`id_com`, `id_reg`) COMMENT '',
INDEX `fk_communes_region_idx` (`id_reg` ASC) COMMENT '')
ENGINE = MyISAM;

DROP TABLE IF EXISTS `alexis_gda_api`.`customers`;

CREATE TABLE IF NOT EXISTS `alexis_gda_api`.`customers` (
`dni` VARCHAR(45) NOT NULL COMMENT 'Documento de Identidad',
`id_reg` INT NOT NULL COMMENT '',
`id_com` INT NOT NULL COMMENT '',
`email` VARCHAR(120) NOT NULL COMMENT 'Correo Electrónico',
`name` VARCHAR(45) NOT NULL COMMENT 'Nombre',
`last_name` VARCHAR(45) NOT NULL COMMENT 'Apellido',
`address` VARCHAR(255) NULL COMMENT 'Dirección',
`date_reg` DATETIME NOT NULL COMMENT 'Fecha y hora del registro',
`status` ENUM('A', 'I', 'trash') NOT NULL DEFAULT 'A' COMMENT 'estado del registro:\nA
: Activo\nI : Desactivo\ntrash : Registro eliminado',
PRIMARY KEY (`dni`, `id_reg`, `id_com`) COMMENT '',
INDEX `fk_customers_communes1_idx` (`id_com` ASC, `id_reg` ASC) COMMENT '',
UNIQUE INDEX `email_UNIQUE` (`email` ASC) COMMENT '')
ENGINE = MyISAM;


GRANT ALL PRIVILEGES ON alexis_gda_api.* TO 'alexandre'@'localhost' IDENTIFIED BY 'P@s5w0rd22!';


INSERT INTO regions (description, status) VALUES ("Hidalgo", "A");
INSERT INTO regions (description, status) VALUES ("Veracruz", "A");

INSERT INTO communes (id_reg, description, status) VALUES (1, "Tulancingo", "A");
INSERT INTO communes (id_reg, description, status) VALUES (1, "Pachuca", "A");
INSERT INTO communes (id_reg, description, status) VALUES (2, "Tecolutla", "A");


CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Alexis', 'HR', 'alexis@mail.com', '$2y$07$BCryptRequires22Chrcte/VlQH0piJtjXl.0t1XkA8pw9dMXTpOq', '2021-11-18 00:00:00', '2021-11-18 00:00:00');

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

