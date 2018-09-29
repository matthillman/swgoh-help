<?php

namespace SwgohHelp\Parsers;

use SwgohHelp\API as SWGOH;

class GuildParser {

    public $data;
    protected $guild;
    protected $gp;
    protected $gpMap;
    protected $zetaMap;
    protected $name;
    protected $url = '';

    public function __construct($guild) {
        $this->url = "https://swgoh.gg/g/${guild}/guild/";
        $this->guild = $guild;
        $this->gpMap = [];
        $this->zetaMap = [];
    }

    public function scrape(Callable $memberCallback) {
        $response = guzzle()->get($this->url, ['allow_redirects' => [ 'track_redirects' => true ]]);
        $this->url = head($response->getHeader(config('redirect.history.header')));
        $anAllyCode = $this->getAnAllyCode();

        $this->data = swgoh()->getGuild($anAllyCode, $memberCallback, SWGOH::FULL_ROSTER);

        return $this;
    }

    protected function getAnAllyCode() {
        $page = goutte()->request('GET', $this->url);
        $slug = $page->filter('table tbody tr td:first-child a')->attr('href');
        return (preg_match('/\/(\d+)\/$/', $slug, $matches)) ? trim($matches[1]) : null;
    }

    public function name() {
        return $this->data['name'];
    }
    public function gp() {
        return $this->data['gp'];
    }
    public function members() {
        return collect($this->data['roster']);
    }
    public function url() {
        return $this->url;
    }
}