<?php

namespace Andy\CountryAccessCheck\XF\Pub\Controller;

class Register extends XFCP_Register
{
	public function actionIndex()
	{
		// get parent		
		$parent = parent::actionIndex();	
		
		// get options
		$options = \XF::options();
		
		// get options from Admin CP -> Options -> Country access check -> Blocked country codes
		$blockedCountryCodes = $options->countryAccessCheckBlockedCountryCodes;
        
		// get options from Admin CP -> Options -> Country access check -> Allowed country codes
		$allowedCountryCodes = $options->countryAccessCheckAllowedCountryCodes;
        
		//########################################
		// blocked country codes
		//########################################

		// check condition
		if (!empty($blockedCountryCodes) AND empty($allowedCountryCodes))
		{
			// remove spaces
			$blockedCountryCodes = str_replace(' ', '', $blockedCountryCodes);

			// remove trailing comma
			$blockedCountryCodes = rtrim($blockedCountryCodes, ',');

			// convert to uppercase
			$blockedCountryCodes = strtoupper($blockedCountryCodes);

			// get blockedCountryCodesArray
			$blockedCountryCodesArray = explode(',', $blockedCountryCodes);							

			// get ip address
			$ipAddress = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 0);

			// check condition
			if (!filter_var($ipAddress, FILTER_VALIDATE_IP)) 
			{
				return $this->error(\XF::phrase('countryaccesscheck_error'));
			}
			
			// check condition
			if (empty($geoIp2AutoloadPath))
			{
				//########################################
				// using ipapi
				//########################################

				// get url
				$url = 'https://ipapi.co/' . $ipAddress . '/json/';

				// define variables
				$bypass = '';
				$countryCode = '';

				// get response
				try
				{
					$client = \XF::app()->http()->client();
					$response = $client->get($url);
				}
				catch (\GuzzleHttp\Exception\ConnectException $e)
				{
					$bypass = 'yes';
				}
				catch (\GuzzleHttp\Exception\RequestException $e)
				{
					$bypass = 'yes';
				}

				// check condition
				if ($bypass != 'yes')
				{
					$array = \GuzzleHttp\json_decode($response->getBody(), true);
					$countryCode = @$array['country_code'];
				}

				// check condition
				if (in_array($countryCode, $blockedCountryCodesArray))
				{
					// save countryAccessCheck
					$countryAccessCheck = \XF::em()->create('Andy\CountryAccessCheck:CountryAccessCheck');
					$countryAccessCheck->ip = $ipAddress;
					$countryAccessCheck->country = $countryCode;
					$countryAccessCheck->dateline = time();
					$countryAccessCheck->access = 'Register';
					$countryAccessCheck->save();

					return $this->error(\XF::phrase('countryaccesscheck_error'));	
				}
			}
			
			// check condition
			if (!empty($geoIp2AutoloadPath))
			{
				//########################################
				// using geoip2
				//########################################

				// get autoload.php path
				require_once $geoIp2AutoloadPath;

				// check condition
				if (empty($reader))
				{
					// get reader
					$reader = new Reader($geoIp2DatabasePath);
				}

				// get response
				try
				{
					// get record
					$record = $reader->city($ipAddress);
				}
				catch (\GeoIp2\Exception\AddressNotFoundException $e)
				{
					$bypass = 'yes';
				}
				
				// check condition
				if ($bypass != 'yes')
				{
					// get location
					$countryCode = @$record->country->isoCode;
				}
				
				// check condition
				if (in_array($countryCode, $blockedCountryCodesArray))
				{
					// save countryAccessCheck
					$countryAccessCheck = \XF::em()->create('Andy\CountryAccessCheck:CountryAccessCheck');
					$countryAccessCheck->ip = $ipAddress;
					$countryAccessCheck->country = $countryCode;
					$countryAccessCheck->dateline = time();
					$countryAccessCheck->access = 'Register';
					$countryAccessCheck->save();

					return $this->error(\XF::phrase('countryaccesscheck_error'));	
				}
			}
		}

		//########################################
		// allowed country codes
		//########################################

		// check condition
		if (!empty($allowedCountryCodes) AND empty($blockedCountryCodes))
		{
			// remove spaces
			$allowedCountryCodes = str_replace(' ', '', $allowedCountryCodes);

			// remove trailing comma
			$allowedCountryCodes = rtrim($allowedCountryCodes, ',');

			// convert to uppercase
			$allowedCountryCodes = strtoupper($allowedCountryCodes);

			// get allowedCountryCodesArray
			$allowedCountryCodesArray = explode(',', $allowedCountryCodes);						

			// get ip address
			$ipAddress = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 0);

			// check condition
			if (!filter_var($ipAddress, FILTER_VALIDATE_IP)) 
			{
				return $this->error(\XF::phrase('countryaccesscheck_error'));
			}
			
			// check condition
			if (empty($geoIp2AutoloadPath))
			{
				//########################################
				// using ipapi
				//########################################

				// get url
				$url = 'https://ipapi.co/' . $ipAddress . '/json/';

				// define variables
				$bypass = '';
				$countryCode = '';

				// get response
				try
				{
					$client = \XF::app()->http()->client();
					$response = $client->get($url);
				}
				catch (\GuzzleHttp\Exception\RequestException $e)
				{
					$bypass = 'yes';
				}

				// check condition
				if ($bypass != 'yes')
				{
					// check condition
					if (!empty($response->getBody()))
					{
						$array = @\GuzzleHttp\json_decode($response->getBody(), true);
						$countryCode = @$array['country_code'];
					}
				}

				// check condition
				if (!empty($countryCode))
				{
					// check condition
					if (!in_array($countryCode, $allowedCountryCodesArray))
					{
						// save countryAccessCheck
						$countryAccessCheck = \XF::em()->create('Andy\CountryAccessCheck:CountryAccessCheck');
						$countryAccessCheck->ip = $ipAddress;
						$countryAccessCheck->country = $countryCode;
						$countryAccessCheck->dateline = time();
						$countryAccessCheck->access = 'Register';
						$countryAccessCheck->save();

						return $this->error(\XF::phrase('countryaccesscheck_error'));	
					}
				}
			}
			
			// check condition
			if (!empty($geoIp2AutoloadPath))
			{
				//########################################
				// using geoip2
				//########################################

				// get autoload.php path
				require_once $geoIp2AutoloadPath;

				// check condition
				if (empty($reader))
				{
					// get reader
					$reader = new Reader($geoIp2DatabasePath);
				}

				// get response
				try
				{
					// get record
					$record = $reader->city($ipAddress);
				}
				catch (\GeoIp2\Exception\AddressNotFoundException $e)
				{
					$bypass = 'yes';
				}
				
				// check condition
				if ($bypass != 'yes')
				{
					// get location
					$countryCode = @$record->country->isoCode;
				}
				
				// check condition
				if (!in_array($countryCode, $allowedCountryCodesArray))
				{
					// save countryAccessCheck
					$countryAccessCheck = \XF::em()->create('Andy\CountryAccessCheck:CountryAccessCheck');
					$countryAccessCheck->ip = $ipAddress;
					$countryAccessCheck->country = $countryCode;
					$countryAccessCheck->dateline = time();
					$countryAccessCheck->access = 'Register';
					$countryAccessCheck->save();

					return $this->error(\XF::phrase('countryaccesscheck_error'));	
				}
			}
		}

		// return parent
		return $parent;	
	}
}