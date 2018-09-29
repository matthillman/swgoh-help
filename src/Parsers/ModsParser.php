<?php

namespace SwgohHelp\Parsers;

use SwgohHelp\Enums\ModSet;
use SwgohHelp\Enums\ModSlot;

class ModsParser {

    protected $mods;
    protected $allyCode;

    public function __construct($allyCode) {
        $this->allyCode = $allyCode;
    }

    public function scrape() {
        $this->mods = swgoh()->getMods($this->allyCode)
            ->flatMap(function($mods, $char) {
                return collect($mods)
                    ->pluck('mods')
                    ->flatMap(function($charMods) use ($char) {
                        return collect($charMods)->map(function($mod, $index) use ($char) {
                            if (!isset($mod['id'])) { return []; }

                            $pRaw = array_shift($mod['stat']);
                            $primary = [
                                'type' => $pRaw[0],
                                'value' => $pRaw[1],
                            ];

                            $secondaries = collect($mod['stat'])
                                ->mapWithKeys(function($stat) {
                                    return [$stat[0] => $stat[1]];
                                });

                            unset($mod['stat']);

                            return [
                                'uid' => $mod['id'],
                                'slot' => (new ModSlot($index))->getKey(),
                                'set' => (new ModSet(+$mod['set']))->getKey(),
                                'pips' => $mod['pips'],
                                'level' => $mod['level'],
                                'name' => '',
                                'tier' => $mod['tier'],
                                'location' => $char,

                                'primary' => $primary,
                                'secondaries' => $secondaries,
                            ];
                        });
                    })
                    ->filter(function($mod) { return count($mod) > 0; });
            });

        return $this;
    }

    public function getMods() { return $this->mods; }
}