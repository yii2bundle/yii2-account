<?php

use yii2lab\db\domain\db\MigrationAddColumn;

/**
 * Handles adding data to table `user_confirm`.
 */
class m180717_060437_add_is_activated_column_to_user_confirm_table extends MigrationAddColumn
{
    
	public  $table = '{{%user_confirm}}';
	
	public function getColumns()
	{
		return [
			'is_activated' => $this->boolean()->after('code'),
		];
	}
	
}
