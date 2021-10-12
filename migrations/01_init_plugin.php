<?php

class InitPlugin extends Migration
{
    public function up()
    {
        DBManager::get()->exec("
            CREATE TABLE `mycss_stylesheets` (
                `stylesheet_id` char(32) NOT NULL DEFAULT '',
                `title` varchar(64) NOT NULL DEFAULT '',
                `description` text DEFAULT NULL,
                `range_id` char(32) NOT NULL DEFAULT '',
                `range_type` enum('user','global','institute','course') DEFAULT NULL,
                `active` tinyint(1) DEFAULT NULL,
                `public` tinyint(1) DEFAULT NULL,
                `css` text DEFAULT NULL,
                `chdate` int(11) DEFAULT NULL,
                `mkdate` int(11) DEFAULT NULL,
                PRIMARY KEY (`stylesheet_id`),
                KEY `range_id` (`range_id`),
                KEY `range_type` (`range_type`)
            ) ENGINE=InnoDB;
        ");
        Config::get()->create("MYCSS_EDIT_PERM", array(
            'value' => 'root',
            'type' => "string",
            'range' => "global",
            'section' => "MYCSS",
            'description' => "What status does one user need to have to create or edit his or her own style? root, admin, dozent, tutor, autor are possible."
        ));

    }
}
