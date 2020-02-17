<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
 * Class m180223_102252_create_user_security_table
 * 
 * @package 
 */
class m180223_102243_create_user_contact_table extends Migration {

	public $table = 'user_contact';
	public $tableComment = 'Контактные данные пользователя';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'id' => $this->primaryKey()->notNull()->comment('Идентификатор'),
			'identity_id' => $this->integer(11)->notNull()->comment('ID учетной записи'),
			'type' => $this->string(64)->notNull()->comment('Тип (phone,email)'),
			'data' => $this->string(255)->notNull()->comment('Данные'),
			'is_main' => $this->smallInteger()->notNull()->comment('Основной контакт'),
			'status' => $this->smallInteger()->defaultValue(1)->comment('Статус'),
		];
	}

	public function afterCreate()
	{
		$this->myCreateIndexUnique(['identity_id', 'type', 'data']);
		$this->myAddForeignKey(
			'identity_id',
			'user_identity',
			'id',
			'CASCADE',
			'CASCADE'
		);
	}

}