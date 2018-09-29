<?php

namespace SwgohHelp;

use App\ModUser;
use Carbon\Carbon;

class ProfileParser {

    protected $user;
    protected $allyCode;

    public function __construct($allyCode) {
        $this->allyCode = $allyCode;
    }

    public function scrape() {
        $result = swgoh()->getPlayer($this->allyCode);

        $this->user = $result->map(function($json) {
            $stats = [];
            collect($json['stats'])->sortBy('index')->each(function($stat) use (&$stats) {
                $stats[$stat['nameKey']] = $stat['value'];
            });

            $json['stats'] = $stats;
            $json['updated'] = Carbon::createFromTimestamp($json['updated']);

            return $json;
        })
        ->first();

        return $this->user['updated'];
    }

    public function getUser() { return $this->user; }
    public function updated() { return $this->user['updated']; }
}