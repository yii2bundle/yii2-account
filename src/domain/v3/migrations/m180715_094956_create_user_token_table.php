<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
 * Class m180715_094956_create_user_token_table
 * 
 * @package 
 */
class m180715_094956_create_user_token_table extends Migration {

	public $table = 'user_token';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'user_id' => $this->integer(11)->notNull(),
            'type' => $this->string(16),
			'token' => $this->string(255)->notNull(),
			'ip' => $this->string(40)->notNull(),
			'platform' => $this->string(32),
			'browser' => $this->string(32),
			'version' => $this->string(10),
			'created_at' => $this->integer()->notNull(),
			'expire_at' => $this->integer()->notNull(),
		];
	}

	public function afterCreate()
	{
		$this->myCreateIndexUnique('token');
	}

}