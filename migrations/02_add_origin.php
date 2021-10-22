<?php

class AddOrigin extends Migration
{
    public function up()
    {
        DBManager::get()->exec("
            ALTER TABLE `mycss_stylesheets`
            ADD COLUMN `origin_id` char(32) DEFAULT NULL AFTER `css`,
            ADD COLUMN `updatetime` int(11) DEFAULT NULL AFTER `origin_id`,
            ADD KEY `origin_id` (`origin_id`)
        ");
        SimpleORMap::expireTableScheme();
    }
}
