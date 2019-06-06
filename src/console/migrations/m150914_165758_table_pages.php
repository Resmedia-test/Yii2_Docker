<?php

use common\models\Page;
use yii\db\Migration;

class m150914_165758_table_pages extends Migration
{
    private $tableName = '{{%pages}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'url' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'content' => $this->text()->notNull(),
            'time_update' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
        ]);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
