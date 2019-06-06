<?php

use yii\db\Schema;
use yii\db\Migration;

class m150914_174655_table_menus_items extends Migration
{
    private $tableName = '{{%menus_links}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'menu_id' => $this->integer()->notNull()->defaultValue(0),
            'parent_id' => $this->integer()->notNull()->defaultValue(0),
            'url' => $this->string()->notNull(),
            'class' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'order' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
        ]);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
