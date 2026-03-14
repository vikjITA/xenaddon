<?php

namespace Vendor\CountryAccessCheck\Service;

use XF\Service\AbstractService;

class CountryAccessService extends AbstractService
{
    protected array $blockedCountries = ['CN', 'RU', 'KP']; // esempio
    protected array $blockedIps = []; // opzionale

    public function resolveCountry(string $ip): ?string
    {
        $cacheKey = 'country_' . md5($ip);
        $cache = $this->app->cache();

        if ($country = $cache->fetch($cacheKey)) {
            return $country;
        }

        $country = $this->lookupCountry($ip);
        $cache->save($cacheKey, $country, 3600);

        return $country;
    }

    protected function lookupCountry(string $ip): ?string
    {
        $url = "https://ipapi.co/{$ip}/country/";

        try {
            $country = trim(@file_get_contents($url));
            if (strlen($country) === 2) {
                return strtoupper($country);
            }
        } catch (\Throwable $e) {}

        return null;
    }

    public function isBlockedCountry(?string $country): bool
    {
        if (!$country) return false;
        return in_array($country, $this->blockedCountries, true);
    }

    public function logVisit(string $ip, ?string $country, bool $blocked): void
    {
        $ipHash = hash_hmac('sha256', $ip, $this->app->config('globalSalt'));

        $db = $this->db();
        $now = \XF::time();

        $db->query("
            INSERT INTO xf_country_access_log
                (ip_hash, country_code, first_seen, last_seen, count, blocked)
            VALUES
                (?, ?, FROM_UNIXTIME(?), FROM_UNIXTIME(?), 1, ?)
            ON DUPLICATE KEY UPDATE
                last_seen = FROM_UNIXTIME(?),
                count = count + 1,
                blocked = VALUES(blocked)
        ", [
            $ipHash,
            $country ?? '',
            $now,
            $now,
            $blocked ? 1 : 0,
            $now
        ]);
    }
}