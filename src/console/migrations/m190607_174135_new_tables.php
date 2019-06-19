<?php

use common\models\TaskArticle;
use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m190607_174135_new_tables
 */
class m190607_174135_new_tables extends Migration
{
    protected $articles = "{{%articles}}";
    protected $comments = "{{%comments}}";
    protected $subscriptions = "{{%subscriptions_comments}}";
    protected $abuse = "{{%comments_abuses}}";
    protected $subscribe = '{{%subscription}}';
    protected $gallery = '{{%gallery_image}}';
    protected $task = '{{%tasks_articles}}';

    public function up()
    {
        $this->createTable($this->articles, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->defaultValue(0),
            'name' => $this->string()->notNull(),
            'small_desc' => $this->text()->notNull(),
            'full_desc' => $this->text()->notNull(),
            'time_create' => $this->integer()->notNull()->defaultValue(0),
            'time_update' => $this->integer()->notNull()->defaultValue(0),
            'time_publish' => $this->integer()->notNull()->defaultValue(0),
            'views' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'url' => $this->string()->notNull(),
            'article_main' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'rate' => $this->double(2)->notNull()->defaultValue(0),
            'rates' => $this->integer()->notNull()->defaultValue(0),
        ]);

        $this->createTable($this->subscriptions, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->defaultValue(0),
            'model' => $this->string()->notNull(),
            'model_id' => $this->integer()->notNull()->defaultValue(0),
            'type_id' => $this->smallInteger()->notNull()->defaultValue(0),
            'comments' => $this->integer()->notNull()->defaultValue(0),
        ]);

        $this->createTable($this->abuse,[
            'id' => $this->primaryKey(),
            'comment_id' => $this->integer()->notNull()->defaultValue(0),
            'user_id' => $this->integer()->notNull()->defaultValue(0),
            'time_create' => $this->integer()->notNull()->defaultValue(0),
        ]);

        $this->createTable($this->task, [
            'id' => $this->primaryKey(),
            'models' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
            'time' => $this->integer()->notNull()->defaultValue(0),
        ]);

        $this->createTable($this->comments, [
            'id' => $this->primaryKey(),
            'model' => $this->string()->notNull(),
            'model_id' => $this->integer()->notNull()->defaultValue(0),
            'user_id' => $this->integer()->notNull()->defaultValue(0),
            'parent_id' => $this->integer()->notNull()->defaultValue(0),
            'reply_id' => $this->integer()->notNull()->defaultValue(0),
            'text' => $this->text()->notNull(),
            'ip' => $this->string()->notNull(),
            'likes' => $this->integer()->notNull()->defaultValue(0),
            'time_create' => $this->integer()->notNull()->defaultValue(0),
            'time_update' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
        ]);

        $this->createTable($this->subscribe, [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'user_id' => $this->integer()->unsigned(),
            'email' => $this->string(),
            'date' => $this->dateTime(),
            'news' => $this->boolean()->defaultValue(true),
            'life' => $this->boolean()->defaultValue(true),
            'articles' => $this->boolean()->defaultValue(true),
            'direct' => $this->boolean()->defaultValue(true)
        ]);

        $this->createTable($this->gallery, [
            'id' => Schema::TYPE_PK,
            'type' => Schema::TYPE_STRING,
                'ownerId' => Schema::TYPE_STRING . ' NOT NULL',
                'rank' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
                'name' => Schema::TYPE_STRING,
                'description' => Schema::TYPE_TEXT
            ]
        );
    }

    public function down()
    {
        $this->dropTable($this->articles);
        $this->dropTable($this->comments);
        $this->dropTable($this->subscriptions);
        $this->dropTable($this->subscribe);
        $this->dropTable($this->abuse);
        $this->dropTable($this->gallery);
        $this->dropTable($this->task);
    }
}
