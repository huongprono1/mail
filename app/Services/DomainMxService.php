<?php

namespace App\Services;

class DomainMxService
{
    public function check(string $domain, array $expectedMxHosts = []): bool|string
    {
        $records = dns_get_record($domain, DNS_MX);

        //        dd($expectedMxHosts, $records);

        if (empty($expectedMxHosts)) {
            return __('No email server(s) config.');
        }

        if (empty($records)) {
            return __('No MX record found');
        }

        $mxHosts = array_map(fn($record) => $record['target'], $records);

        foreach ($expectedMxHosts as $expectedHost) {
            if (!in_array($expectedHost, $mxHosts)) {
                return __('MX record does not point to:') . ' ' . implode(',', $expectedMxHosts);
            }
        }

        return true; // OK: MX match
    }
}
