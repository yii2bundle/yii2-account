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
			'id' => $this->primaryKey()->notNull()->comment('Идентификатор'),
			'login_id' => $this->integer(11)->notNull(),
			'password_hash' => $this->string(255)->notNull(),
		];
	}

	public function afterCreate()
	{
		$this->myAddForeignKey(
			'login_id',
			'user_login',
			'id',
			'CASCADE',
			'CASCADE'
		);
		$this->myCreateIndexUnique(['login_id']);
	}

}