<?php

namespace Vendor\CountryAccessCheck\Cron;

class Cleanup
{
    public static function run()
    {
        \XF::db()->delete(
            'xf_country_access_log',
            'last_seen < DATE_SUB(NOW(), INTERVAL 7 DAY)'
        );
    }
}