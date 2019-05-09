<?php

use yii\db\Migration;


class m190424_181541_create_user_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(),
            'email'=>$this->string()->defaultValue(null),
            'password'=>$this->string(),
            'isAdmin'=>$this->integer()->defaultValue(0),
            'photo'=>$this->string()->defaultValue(null)
        ]);
    }


    public function safeDown()
    {
        $this->dropTable('user');
    }
}