<?php

use yii2lab\db\domain\db\MigrationAddColumn;

/**
 * Handles adding data to table `user_confirm`.
 */
class m180328_121907_add_data_column_to_user_confirm_table extends MigrationAddColumn
{
    
	public  $table = '{{%user_confirm}}';
	
	public function getColumns()
	{
		return [
			'data' => $this->text()->after('code'),
		];
	}
	
}
