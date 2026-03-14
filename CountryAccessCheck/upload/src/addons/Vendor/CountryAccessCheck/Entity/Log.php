<?php

namespace Vendor\CountryAccessCheck\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Log extends Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_country_access_log';
        $structure->shortName = 'Vendor\CountryAccessCheck:Log';
        $structure->primaryKey = 'log_id';

        $structure->columns = [
            'log_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'ip_hash' => ['type' => self::BINARY, 'maxLength' => 64],
            'country_code' => ['type' => self::STR, 'maxLength' => 2],
            'first_seen' => ['type' => self::STR],
            'last_seen' => ['type' => self::STR],
            'count' => ['type' => self::UINT, 'default' => 1],
            'blocked' => ['type' => self::BOOL, 'default' => false]
        ];

        return $structure;
    }
}