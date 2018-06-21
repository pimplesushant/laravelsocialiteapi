<?php

namespace Pimplesushant\Laravelsocialiteapi;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    const SERVICE_FACEBOOK = 'facebook';
    const SERVICE_GOOGLE = 'google';

    public function user() {
        return $this->belongsTo(User::class);
    }
}
