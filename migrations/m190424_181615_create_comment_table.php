<?php

use yii\db\Migration;


class m190424_181615_create_comment_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('comment', [
            'id' => $this->primaryKey(),
            'text'=>$this->string(),
            'user_id'=>$this->integer(),
            'article_id'=>$this->integer(),
            'status'=>$this->integer()
        ]);

        $this->createIndex(
            'idx-post-user_id',
            'comment',
            'user_id'
        );

        $this->addForeignKey(
            'fk-post-user_id',
            'comment',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-article_id',
            'comment',
            'article_id'
        );

        $this->addForeignKey(
            'fk-article_id',
            'comment',
            'article_id',
            'article',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('comment');
    }
}