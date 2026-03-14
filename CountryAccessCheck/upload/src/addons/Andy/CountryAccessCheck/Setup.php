<?php

namespace Andy\CountryAccessCheck;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;

class Setup extends AbstractSetup
{
	use StepRunnerUpgradeTrait;
	
	public function install(array $stepParams = [])
	{
		$this->schemaManager()->createTable('xf_andy_country_access_check', function(Create $table)
		{
			$table->addColumn('country_access_check_id', 'int')->autoIncrement();
			$table->addColumn('ip', 'text');
            $table->addColumn('country', 'text');
			$table->addColumn('dateline', 'int');
			$table->addColumn('access', 'text');
		});
	}

	public function upgrade(array $stepParams = [])
	{
	}

	public function uninstall(array $stepParams = [])
	{
		$this->schemaManager()->dropTable('xf_andy_country_access_check');
	}
}