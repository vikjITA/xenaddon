<?php

namespace Andy\CountryAccessCheck\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class CountryAccessCheck extends Entity
{
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_andy_country_access_check';
		$structure->shortName = 'Andy\CountryAccessCheck:CountryAccessCheck';
		$structure->primaryKey = 'country_access_check_id';
		$structure->columns = [
			'country_access_check_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'ip' => ['type' => self::STR, 'default' => ''],
            'country' => ['type' => self::STR, 'default' => ''],
            'dateline' => ['type' => self::INT, 'default' => 0],
			'access' => ['type' => self::STR, 'default' => '']
            
		];
		$structure->getters = [];
		$structure->relations = [];

		return $structure;
	}	
}