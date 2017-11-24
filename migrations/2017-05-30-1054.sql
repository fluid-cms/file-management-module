CREATE TABLE `filemanagement_category` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(80) NOT NULL,
  `description` text NULL,
  `created_on` datetime NOT NULL,
  `created_by_id` int(11) NOT NULL,
  `show_in_preview` int(1) NOT NULL DEFAULT '1',
  FOREIGN KEY (`created_by_id`) REFERENCES `admin_user` (`id`) ON DELETE RESTRICT
) COLLATE 'utf8_czech_ci';

ALTER TABLE `filemanagement_category` ADD INDEX `name` (`name`);
ALTER TABLE `filemanagement_category` ADD INDEX `show_in_preview` (`show_in_preview`);

CREATE TABLE `filemanagement_file` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(180) NOT NULL,
  `filename` varchar(80) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `type` varchar(80) NULL,
  `size` int NULL,
  `filemanagement_category_id` int(11) NOT NULL,
  FOREIGN KEY (`filemanagement_category_id`) REFERENCES `filemanagement_category` (`id`) ON DELETE RESTRICT
) COLLATE 'utf8_czech_ci';