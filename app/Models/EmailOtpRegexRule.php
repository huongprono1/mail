<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtpRegexRule extends Model
{
    protected $fillable = [
        'sender_domain',
        'regex_pattern',
    ];
}
