<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

class m170512_090948_create_user_registration_table extends Migration
{
	public $table = '{{%user_registration}}';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'login' => $this->string(50)->unique(),
			'email' => $this->string(100),
			'activation_code' => $this->integer(6),
			'ip' => $this->string(16),
			'created_at' => $this->timestamp(),
		];
	}

}
