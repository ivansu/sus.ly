<?php

class m140318_163326_url_table_creation extends CDbMigration
{
	public function up()
	{
		$this->createTable('short_url', array(
			'id' => 'pk',
			'url' => 'text NOT NULL',
			'hash' => 'varchar(20) NOT NULL',
			'created_at' => 'timestamp default CURRENT_TIMESTAMP'
		));
	}

	public function down()
	{
		$this->dropTable('short_url');
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
