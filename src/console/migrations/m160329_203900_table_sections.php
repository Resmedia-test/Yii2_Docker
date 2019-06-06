<?php

use yii\db\Migration;

class m160329_203900_table_sections extends Migration
{
    private $tableName = '{{%sections}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'module' => $this->string()->notNull(),
            'controller' => $this->string()->notNull(),
            'action' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'url' => $this->string()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue(0),
            'time_update' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
