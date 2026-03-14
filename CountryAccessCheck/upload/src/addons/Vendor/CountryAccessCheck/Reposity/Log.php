<?php

namespace Vendor\CountryAccessCheck\Repository;

use XF\Mvc\Entity\Repository;

class Log extends Repository
{
    public function getLast7Days()
    {
        return $this->finder('Vendor\CountryAccessCheck:Log')
            ->where('last_seen', '>=', date('Y-m-d H:i:s', time() - 604800))
            ->order('last_seen', 'DESC')
            ->fetch();
    }
}