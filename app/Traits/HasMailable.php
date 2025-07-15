<?php

namespace App\Traits;

use App\Exceptions\DomainNotFoundException;
use App\Models\Client;
use App\Models\Domain;
use App\Models\Mail;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

trait HasMailable
{
    /**
     */
    public function getUserClient(): Client|User
    {
        // user logged in
        // dont assign logged in user to client
//        if (auth()->check()) {
//            return auth()->user();
//        }

        $sessionId = request()->cookie('session_id', Str::uuid()->toString());
        $client = Client::where('session_id', $sessionId)->first();
        if (! $client) {
            cookie()->queue('session_id', $sessionId, 60 * 24 * 30);

            $ua = request()->userAgent();
            $agent = new Agent(userAgent: $ua);
            $ip = request()->ip();
            $geoip = geoip($ip);
            //            $geoip = Location::get($ip);
            $client = Client::create([
                'session_id' => $sessionId,
                'ip_address' => $ip,
                'user_agent' => request()->userAgent(),
                'country' => $geoip->country,
                'city' => $geoip->city,
                'state' => $geoip->state,
                'additional_info' => $geoip->toArray(),
                'browser' => $agent->browser() ?: 'Unknown',
                'device' => $agent->device() ?: 'Unknown',
                'platform' => $agent->platform() ?: 'Unknown',
            ]);
        }

        return $client;
    }

    public function allMails(): BelongsToMany|HasMany
    {
        return $this->getUserClient()->mails();
    }

    /**
     * @return Mail
     * @throws Exception
     */
    public function newRandomMail(): Mail
    {
        $domain = Domain::accessible()->inRandomOrder()->first();
        if (is_null($domain)) {
            throw new Exception('Domain not config');
        }
        $randomMail = trim(sprintf('%s@%s', strtolower(Str::random(8)), $domain->name));

        // if mail exist
        $find = Mail::query()->withTrashed()->where('email', $randomMail)->exists();
        if ($find) {
            return $this->newRandomMail(); // create another
        }

        $saveData = [
            'email' => $randomMail,
            'domain_id' => $domain->id,
        ];
        $client = $this->getUserClient();
        $mail = $client->mails()->create($saveData);
        $this->setCurrentMail($mail);

        return $mail;
    }

    /**
     * @throws DomainNotFoundException
     * @throws Exception
     */
    public function newCustomMail(string $username, string $domainName): Mail
    {
        $domain = Domain::accessible()->where('name', $domainName)->first();
        if (!$domain) {
            throw new DomainNotFoundException(__('Domain not found.'));
        }

        $newMail = trim(sprintf('%s@%s', strtolower($username), $domainName));
        $validator = Validator::make([
            'email' => $newMail,
        ], [
            'email' => ['required', 'email'],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            throw new Exception($errors->first());
        }

        // if mail has owner by other user -> create other
        $email = Mail::query()->withTrashed()->with('user')->where('email', '=', $newMail)->first();
        if (!$email) {
            // create new
            $email = $this->getUserClient()->mails()->create([
                'email' => $newMail,
                'domain_id' => $domain->id,
            ]);
        } else {
            $user = auth()->user();
            if (isset($email->user) && $email->user->id !== $user?->id) {
                // mail have owner
                throw new Exception(__('Email has owner by another user.'));
            }

            // attach to user
//            if ($email->user_id == null && $this->getUserClient() instanceof User) {
//                $email->user_id = $user->id;
//            }

            // attach to client
            if ($this->getUserClient() instanceof Client) {
                $this->allMails()->syncWithoutDetaching($email->id);
            }
            $email->updated_at = now();
            $email->deleted_at = null; // restore
            $email->save();
        }
        $this->setCurrentMail($email);

        return $email;
    }

    public function detachMail(?Mail $mail): void
    {
        if ($this->allMails() instanceof BelongsToMany) {
            $this->allMails()->detach($mail);
        }
        if ($this->allMails() instanceof HasMany) {
            $this->allMails()->where('id', $mail->id)->update(['user_id' => null]);
        }
    }

    public function setCurrentMail(Mail $mail): void
    {
        if ($this->allMails()->find($mail->id) != null) {
            session(['selected_mail_id' => $mail->id]);
        }
    }

    public function getCurrentMail(): ?Mail
    {
        $mailId = session('selected_mail_id');

        return $this->allMails()->find($mailId) ?? $this->allMails()->first();
    }
}
