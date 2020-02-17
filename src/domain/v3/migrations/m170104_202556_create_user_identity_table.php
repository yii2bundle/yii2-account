<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
 * Class m190106_182202_create_main.users_table
 * 
 * @package 
 */
class m170104_202556_create_user_identity_table extends Migration {

	public $table = 'user_identity';
    public $tableComment = 'Аккаунт пользователя';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'id' => $this->primaryKey()->notNull()->comment('Идентификатор'),
			'login' => $this->string(255)->notNull()->comment('Логин'),
			'status' => $this->smallInteger()->comment('Статус'),
			'created_at' => $this->timestamp()->defaultValue(null)->comment('Дата создания'),
			'updated_at' => $this->timestamp()->defaultValue(null)->comment('Дата обновления'),
		];
	}

	public function afterCreate()
	{
		$this->myCreateIndexUnique(['login']);
	}

}