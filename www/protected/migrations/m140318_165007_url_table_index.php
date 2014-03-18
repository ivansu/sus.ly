<?php

class m140318_165007_url_table_index extends CDbMigration
{
	public function up()
	{
		$this->createIndex('hash_index', 'short_url', 'hash', true);
	}

	public function down()
	{
		$this->dropIndex('hash_index', 'short_url');
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
