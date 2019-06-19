<?php

use yii\db\Schema;
use yii\db\Migration;

class m150901_071950_settings_table extends Migration
{
    public $tableName = "{{%settings}}";
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'code' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'value' => $this->text()->notNull(),
            'element' => 'ENUM("text", "textarea", "editor") NOT NULL',
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
