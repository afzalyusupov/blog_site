<?php

use yii\db\Migration;


class m190424_181434_create_category_table extends Migration
{

    public function safeUp()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'title'=>$this->string()
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('category');
    }
}