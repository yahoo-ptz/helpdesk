CREATE TABLE `tasks` (
    `id`  int UNSIGNED NULL AUTO_INCREMENT ,
    `from`  text NULL ,
    `subject`  text NULL ,
    `message`  text NULL ,
    `time_created`  timestamp NULL DEFAULT 'CURRENT_TIMESTUMP' ,
    `time_received` timestamp NULL DEFAULT '0000-00-00 00:00:00' ,
    PRIMARY KEY (`id`)
);
