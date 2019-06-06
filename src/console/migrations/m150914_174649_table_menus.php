<?php

use yii\db\Schema;
use yii\db\Migration;

class m150914_174649_table_menus extends Migration
{
    private $tableName = '{{%menus}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'levels' => $this->smallInteger()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
        ]);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
