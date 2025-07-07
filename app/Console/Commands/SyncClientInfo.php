<?php

namespace App\Console\Commands;

use App\Models\Client;
use Illuminate\Console\Command;
use Jenssegers\Agent\Agent;

class SyncClientInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tempmail:sync-client-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize client info';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Synchronize client info');

        $clients = Client::query()->get();
        $bar = $this->output->createProgressBar(count($clients));

        $bar->start();

        foreach ($clients as $client) {
            /**
             * @var Client $client
             */
            $agent = new Agent(userAgent: $client->user_agent);
            $geoip = geoip($client->ip_address);

            $client->update([
                'country' => $geoip->country,
                'city' => $geoip->city,
                'state' => $geoip->state,
                'browser' => $agent->browser() ?: 'Unknown',
                'device' => $agent->device() ?: 'Unknown',
                'platform' => $agent->platform() ?: 'Unknown',
            ]);
            $bar->advance();
        }

        //        $bar->finish();
    }
}
