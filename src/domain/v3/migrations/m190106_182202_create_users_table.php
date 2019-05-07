<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
 * Class m190106_182202_create_main.users_table
 * 
 * @package 
 */
class m190106_182202_create_users_table extends Migration {

	public $table = 'user_login';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'id' => $this->primaryKey()->notNull()->comment('Идентификатор'),
			'person_id' => $this->integer()->notNull()->comment('Идентификатор персона'),
            'company_id' => $this->integer(),
			'login' => $this->string(255)->notNull()->comment('Логин'),
			'password' => $this->string(255)->notNull()->comment('Пароль'),
			'password_status' => $this->string(255)->comment('Статус пароля'),
			'roles' => $this->json()->comment('Роли'),
			'status' => $this->integer()->comment('Статус'),
			'remember_token' => $this->string(100)->comment('Токен для Laravel'),
			'created_at' => $this->timestamp()->defaultValue(null),
			'updated_at' => $this->timestamp()->defaultValue(null),
		];
	}

	public function afterCreate()
	{
		$this->myCreateIndexUnique(['login', 'company_id']);
        $this->myCreateIndexUnique(['person_id']);
		$this->myAddForeignKey(
			'person_id',
			'user_person',
			'id',
			'CASCADE',
			'CASCADE'
		);
	}

}