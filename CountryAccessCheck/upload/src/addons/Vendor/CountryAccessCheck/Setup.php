<?php

namespace Vendor\CountryAccessCheck;

use XF\AddOn\AbstractSetup;
use XF\Db\Schema\Create;

class Setup extends AbstractSetup
{
    public function installStep1()
    {
        $schema = $this->schemaManager();

        if (!$schema->tableExists('xf_country_access_log')) {
            $schema->createTable('xf_country_access_log', function(Create $table) {
                $table->addColumn('log_id', 'int')->autoIncrement();
                $table->addColumn('ip_hash', 'varbinary', 64);
                $table->addColumn('country_code', 'varchar', 2)->setDefault('');
                $table->addColumn('first_seen', 'datetime');
                $table->addColumn('last_seen', 'datetime');
                $table->addColumn('count', 'int')->setDefault(1);
                $table->addColumn('blocked', 'tinyint')->setDefault(0);

                $table->addPrimaryKey('log_id');
                $table->addKey('country_code');
                $table->addKey('last_seen');
            });
        }
    }

    public function installStep2()
    {
        // Registra cron cleanup
        $this->app->jobManager()->enqueueUnique(
            'countryAccessCleanup',
            'Vendor\\CountryAccessCheck:Cron\\Cleanup'
        );
    }
}