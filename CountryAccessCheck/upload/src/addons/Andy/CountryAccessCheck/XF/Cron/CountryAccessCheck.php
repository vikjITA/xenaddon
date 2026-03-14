<?php

namespace Andy\CountryAccessCheck\XF\Cron;

class CountryAccessCheck
{
	public static function runCountryAccessCheck()
	{
        // get dateline
		$dateline = time() - (3600 * 24 * 7);
		
		// get db
		$db = \XF::db();				

		// delete rows
		$db->query("
			DELETE FROM xf_andy_country_access_check
			WHERE dateline < ?
		", $dateline);
	}
}