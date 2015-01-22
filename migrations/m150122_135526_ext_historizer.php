<?php

use yii\db\Schema;
use yii\db\Migration;

class m150122_135526_ext_historizer extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE IF NOT EXISTS `ext_historizer_archives` (
                          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                          `model_class` varchar(512) NOT NULL,
                          `model_id` int(11) NOT NULL,
                          `model_attributes` text NOT NULL,
                          `created_at` INT NOT NULL,
                          `created_user` INT NOT NULL,
                          `updated_at` INT NOT NULL,
                          `updated_user` INT NOT NULL,
                          `status` varchar(1) NOT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    }

    public function down()
    {
        echo "m150122_135526_ext_historizer cannot be reverted.\n";

        return false;
    }
}
