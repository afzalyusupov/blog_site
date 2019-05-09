<?php

use yii\db\Migration;


class m190424_181511_create_tag_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('tag', [
            'id' => $this->primaryKey(),
            'title'=>$this->string()
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('tag');
    }
}