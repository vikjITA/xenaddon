<?php

namespace Andy\CountryAccessCheck\Pub\Controller;

use XF\Pub\Controller\AbstractController;

class CountryAccessCheck extends AbstractController
{
	public function actionIndex()
	{
		// check permission
		if (!\XF::visitor()->is_admin)
		{
			return $this->noPermission();
		}
		
        // get results
        $finder = \XF::finder('Andy\CountryAccessCheck:CountryAccessCheck');
        $results = $finder
            ->order('dateline', 'DESC')
            ->fetch();	
		
		// get viewParams
		$viewParams = [
			'results' => $results
		];

		// send to template
		return $this->view('Andy\CountryAccessCheck:Index', 'andy_country_access_check', $viewParams);
	}
}