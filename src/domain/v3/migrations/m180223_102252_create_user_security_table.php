<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
 * Class m180223_102252_create_user_security_table
 * 
 * @package 
 */
class m180223_102252_create_user_security_table extends Migration {

	public $table = 'user_security';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'id' => $this->integer(11)->notNull(),
			'email' => $this->string(255)->notNull(),
			'auth_key' => $this->string(64)->notNull(),
			'password_hash' => $this->string(255)->notNull(),
		];
	}

	public function afterCreate()
	{
		$this->myAddForeignKey(
			'id',
			'user',
			'id',
			'CASCADE',
			'CASCADE'
		);
		$this->myCreateIndexUnique(['auth_key']);
		$this->myCreateIndexUnique(['id']);
	}

}