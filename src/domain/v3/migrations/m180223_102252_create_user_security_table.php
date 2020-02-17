<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
 * Class m180223_102252_create_user_security_table
 * 
 * @package 
 */
class m180223_102252_create_user_security_table extends Migration {

	public $table = 'user_security';
    public $tableComment = 'Хэш пароля пользователя';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'id' => $this->primaryKey()->notNull()->comment('Идентификатор'),
			'identity_id' => $this->integer(11)->notNull()->comment('ID учетной записи'),
			'password_hash' => $this->string(255)->notNull()->comment('Хэш пароля'),
		];
	}

	public function afterCreate()
	{
		$this->myCreateIndexUnique(['identity_id']);
		$this->myAddForeignKey(
			'identity_id',
			'user_identity',
			'id',
			'CASCADE',
			'CASCADE'
		);
	}

}