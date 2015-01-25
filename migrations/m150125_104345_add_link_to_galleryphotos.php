<?php

use yii\db\Schema;
use yii\db\Migration;

class m150125_104345_add_link_to_galleryphotos extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE tbl_cms_gallery_photos ADD COLUMN link VARCHAR(255) AFTER `description`;");
    }

    public function down()
    {
        echo "m150125_104345_add_link_to_galleryphotos cannot be reverted.\n";

        return false;
    }
}
