<?php

use common\components\User;
use yii\db\Schema;
use yii\db\Migration;

class m130524_201442_table_users extends Migration
{
    public function up()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->defaultValue(""),
            'gender' => $this->integer()->notNull()->defaultValue(\common\models\User::GENDER_UNSET),
            'birthday' => $this->integer()->notNull()->defaultValue(0),
            'email' => $this->string()->notNull()->unique()->defaultValue(""),
            'phone' => $this->string()->notNull()->defaultValue(""),
            'auth_key' => $this->string(32)->notNull(),
            'about' => $this->string()->notNull()->defaultValue(""),
            'experience' => $this->string()->notNull()->defaultValue(""),
            'lastname' => $this->string()->notNull()->defaultValue(""),
            'username' => $this->string()->notNull()->defaultValue(""),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->notNull()->defaultValue(""),
            'activation_token' => $this->string()->notNull()->defaultValue(""),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'rates' => $this->text()->notNull(),
            'last_login' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%users}}');
    }
}
