<?php

namespace SwgohHelp\Enums;

use MyCLabs\Enum\Enum;

class ModStat extends Enum {
    const speed = 'UNITSTATSPEED';

    const offense = 'UNITSTATOFFENSE';
    const offense_percent = 'UNITSTATOFFENSEPERCENTADDITIVE';
    const defense = 'UNITSTATDEFENSE';
    const defense_percent = 'UNITSTATDEFENSEPERCENTADDITIVE';

    const protection = 'UNITSTATMAXSHIELD';
    const protection_percent = 'UNITSTATMAXSHIELDPERCENTADDITIVE';
    const health = 'UNITSTATMAXHEALTH';
    const health_percent = 'UNITSTATMAXHEALTHPERCENTADDITIVE';

    const potency = 'UNITSTATACCURACY';
    const tenacity = 'UNITSTATRESISTANCE';

    const crit_chance = 'UNITSTATCRITICALCHANCEPERCENTADDITIVE';
    const crit_damage = 'UNITSTATCRITICALDAMAGE';
    const crit_avoidance = 'UNITSTATCRITICALNEGATECHANCEPERCENTADDITIVE';

    const accuracy = 'UNITSTATEVASIONNEGATEPERCENTADDITIVE';

    static function convert($value, $primary = false) {
        if ($primary && in_array($value, ['protection', 'health', 'defense', 'offense'])) {
            $value .= ' %';
        }

        $value = str_replace(' %', '_percent', $value);
        $value = str_replace('critical ', 'crit_', $value);

        if (in_array($value, static::keys())) {
            return static::$value()->getKey();
        }

        throw new \Exception("Can't find key $value");
    }

    static function convertBack(ModStat $value, $primary = false) {
        $key = $value->getKey();

        $key = str_replace('_percent', $primary ? '' : ' %', $key);
        $key = str_replace('crit_', 'critical ', $key);

        return $key;
    }
}