<?php

namespace Vendor\CountryAccessCheck\Pub\Controller;

use XF\Mvc\Reply\View;
use XF\Pub\Controller\AbstractController;

class Blocked extends AbstractController
{
    public function actionIndex(): View
    {
        return $this->view(
            'Vendor\CountryAccessCheck:Blocked',
            'country_blocked'
        );
    }
}