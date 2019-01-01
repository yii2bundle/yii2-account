<?php

use yii2lab\db\domain\db\MigrationAddColumn;

/**
 * Handles adding type to table `user_token`.
 */
class m181025_072023_add_type_column_to_user_token_table extends MigrationAddColumn
{
	
	public  $table = '{{%user_token}}';
	
	public function getColumns()
	{
		return [
			'type' => $this->string('16')->after('user_id'),
		];
	}
	
}
