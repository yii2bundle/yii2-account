<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

class m170713_061213_create_user_confirm_table extends Migration
{
	public $table = 'user_confirm';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'login' => $this->string(50),
			'action' => $this->string(32),
			'code' => $this->integer(6),
            'is_activated' => $this->boolean(),
            'data' => $this->text(),
            'expire' => $this->integer(),
			'created_at' => $this->timestamp(),
		];
	}

	public function afterCreate()
	{
		$this->myCreateIndexUnique(['login', 'action']);
	}

}
