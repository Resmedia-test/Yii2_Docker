<?php

use yii\db\Migration;

class m161121_190905_add_table_request_contacts extends Migration
{
    protected $tableName = 'requests_contacts';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'ip' => $this->string()->notNull()->defaultValue(0),
            'name' => $this->string()->notNull()->defaultValue(''),
            'email' => $this->string()->notNull()->defaultValue(''),
            'phone' => $this->string()->notNull()->defaultValue(''),
            'text' => $this->text()->notNull(),
            'time_create' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
        ]);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
