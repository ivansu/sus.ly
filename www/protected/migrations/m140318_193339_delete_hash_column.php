<?php

class m140318_193339_delete_hash_column extends CDbMigration
{
	public function up()
	{
		$this->dropColumn('short_url', 'hash');
	}

	public function down()
	{
		$this->addColumn('short_url', 'hash', 'varchar(20) NOT NULL');
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
