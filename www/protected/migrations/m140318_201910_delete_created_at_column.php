<?php

class m140318_201910_delete_created_at_column extends CDbMigration
{
	public function up()
	{
		$this->dropColumn('short_url', 'created_at');
	}

	public function down()
	{
		$this->addColumn('short_url', 'created_at', 'timestamp default CURRENT_TIMESTAMP');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}
