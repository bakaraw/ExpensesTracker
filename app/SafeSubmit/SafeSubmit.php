<?php

namespace App\SafeSubmit;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SafeSubmit
{
    protected $tokenKey = "_safe_submit_token";
    protected $intendedKey = '_safe_submit_intended';

    public function tokenKey()
    {
        return $this->tokenKey; // This should be $this->tokenKey
    }

    public function token()
    {
        return session()->get($this->tokenKey()); // Correct usage of $this->tokenKey
    }

    public function intended($intended){
        session()->put( $this->intendedKey , $intended);

        return redirect($intended);
    }

    public function getIntended(){
        return session()->get($this->intendedKey);
    }

    public function forgetIntended(){
        return session()->forget($this->intendedKey);
    }

    public function regenerateToken()
    {
        $token = $this->generateTokenId();
        session()->put($this->tokenKey(), $token);
        Log::debug("Token Generated Successfully: " . $token);
    }

    protected function generateTokenId()
    {
        return Str::random(40);
    }
}
