<?php

namespace App\Console\Commands;

use App\Models\Mail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PolicyServer extends Command
{
    protected $signature = 'mail:policy-server {--socket=/tmp/policy-check}';

    protected $description = 'Start Postfix policy check server on a UNIX socket';

    public function handle()
    {
        $socketPath = $this->option('socket');

        // Remove old socket if exists
        if (file_exists($socketPath)) {
            unlink($socketPath);
        }

        // Create UNIX domain socket server
        $server = @stream_socket_server("unix://{$socketPath}", $errno, $errstr);
        if ($server === false) {
            $this->error("Unable to create socket: {$errstr} ({$errno})");
            Log::channel('loki')->error("Policy server socket error: {$errstr} ({$errno})");

            return 1;
        }

        // Set socket permissions to allow Postfix
        chmod($socketPath, 0666);
        $this->info("Policy server listening on {$socketPath}");
        Log::channel('loki')->info("Policy server started on {$socketPath}");

        // Main accept loop
        while ($conn = @stream_socket_accept($server, -1)) {
            $params = [];
            // Read until blank line
            while (! feof($conn)) {
                $line = trim(fgets($conn));
                if ($line === '') {
                    break;
                }
                if (str_contains($line, '=')) {
                    [$key, $value] = explode('=', $line, 2);
                    $params[$key] = $value;
                }
            }

            $recipient = strtolower($params['recipient'] ?? '');

            // Check in database
            $find = Mail::getMailWithDomainActive($recipient);

            if ($find) {
                if ($find->trashed()) {
                    $action = 'REJECT Recipient deleted.';
                } else {
                    $action = 'DUNNO';
                }
            } else {
                $action = 'REJECT No such recipient.';
            }
            Log::channel('loki')->info("Policy request for recipient {$recipient}: {$action}");
            $this->info("Policy request for recipient {$recipient}: {$action}");

            fwrite($conn, "action={$action}\n\n");
            fclose($conn);
        }

        return 0;
    }
}
