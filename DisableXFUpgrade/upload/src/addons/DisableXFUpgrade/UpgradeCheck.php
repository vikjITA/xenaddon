<?php
/* Developed by XenVn.Com */

namespace DisableXFUpgrade;

class UpgradeCheck extends XFCP_UpgradeCheck
{
	public function canCheckForUpgrades(&$error = null)
	{
		return false;
	}
}
