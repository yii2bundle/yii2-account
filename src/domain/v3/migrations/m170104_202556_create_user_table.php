<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

class m170104_202556_create_user_table extends Migration
{
	
	public  $table = 'user';
	
	public function getColumns()
	{
		return [
			'id' => $this->primaryKey(),
			'login' => $this->string()->unique(),
			'email' => $this->string(),
			'status' => $this->smallInteger()->notNull(), // в статусе должно быть предусмотрено: активный, бан, премодерация, активация
			'auth_key' => $this->string(64)->unique(),
			'password_hash' => $this->string(),
			'created_at' => $this->timestamp()->notNull(),
		];
	}
	
}
