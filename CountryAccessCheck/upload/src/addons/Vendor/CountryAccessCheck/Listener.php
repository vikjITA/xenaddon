<?php

namespace Vendor\CountryAccessCheck;

use XF\App;
use XF\Mvc\Dispatcher;

class Listener
{
    public static function controllerPreDispatch(App $app, Dispatcher $dispatcher, $controller, $action)
    {
        $service = $app->service('Vendor\CountryAccessCheck:CountryAccessService');

        $ip = $app->request()->getIp();
        $country = $service->resolveCountry($ip);
        $blocked = $service->isBlockedCountry($country);

        $service->logVisit($ip, $country, $blocked);

        if ($blocked) {
            throw $app->exception(
                $app->responseException(
                    $app->controller('Vendor\CountryAccessCheck:Blocked')->actionIndex()
                )
            );
        }
    }
}