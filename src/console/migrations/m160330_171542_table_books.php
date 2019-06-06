<?php

use yii\db\Migration;

class m160330_171542_table_books extends Migration
{
    private $tableName = '{{%books}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'section_id' => $this->integer()->notNull()->defaultValue(0),
            'parent_id' => $this->integer()->notNull()->defaultValue(0),
            'url' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'small_desc' => $this->text()->notNull(),
            'full_desc' => $this->text()->notNull(),
            'time_create' => $this->integer()->notNull()->defaultValue(0),
            'time_update' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->boolean()->notNull()->defaultValue(1),
        ]);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
