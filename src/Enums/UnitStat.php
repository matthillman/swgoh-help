<?php

namespace SwgohHelp\Enums;

use MyCLabs\Enum\Enum;

class UnitStat extends Enum {
    const NOUNITSTAT = 0;
    const UNITSTATMAXHEALTH = 1;
    const UNITSTATSTRENGTH = 2;
    const UNITSTATAGILITY = 3;
    const UNITSTATINTELLIGENCE = 4;
    const UNITSTATSPEED = 5;
    const UNITSTATATTACKDAMAGE = 6;
    const UNITSTATABILITYPOWER = 7;
    const UNITSTATARMOR = 8;
    const UNITSTATSUPPRESSION = 9;
    const UNITSTATARMORPENETRATION = 10;
    const UNITSTATSUPPRESSIONPENETRATION = 11;
    const UNITSTATDODGERATING = 12;
    const UNITSTATDEFLECTIONRATING = 13;
    const UNITSTATATTACKCRITICALRATING = 14;
    const UNITSTATABILITYCRITICALRATING = 15;
    const UNITSTATCRITICALDAMAGE = 16;
    const UNITSTATACCURACY = 17;
    const UNITSTATRESISTANCE = 18;
    const UNITSTATDODGEPERCENTADDITIVE = 19;
    const UNITSTATDEFLECTIONPERCENTADDITIVE = 20;
    const UNITSTATATTACKCRITICALPERCENTADDITIVE = 21;
    const UNITSTATABILITYCRITICALPERCENTADDITIVE = 22;
    const UNITSTATARMORPERCENTADDITIVE = 23;
    const UNITSTATSUPPRESSIONPERCENTADDITIVE = 24;
    const UNITSTATARMORPENETRATIONPERCENTADDITIVE = 25;
    const UNITSTATSUPPRESSIONPENETRATIONPERCENTADDITIVE = 26;
    const UNITSTATHEALTHSTEAL = 27;
    const UNITSTATMAXSHIELD = 28;
    const UNITSTATSHIELDPENETRATION = 29;
    const UNITSTATHEALTHREGEN = 30;
    const UNITSTATATTACKDAMAGEPERCENTADDITIVE = 31;
    const UNITSTATABILITYPOWERPERCENTADDITIVE = 32;
    const UNITSTATDODGENEGATEPERCENTADDITIVE = 33;
    const UNITSTATDEFLECTIONNEGATEPERCENTADDITIVE = 34;
    const UNITSTATATTACKCRITICALNEGATEPERCENTADDITIVE = 35;
    const UNITSTATABILITYCRITICALNEGATEPERCENTADDITIVE = 36;
    const UNITSTATDODGENEGATERATING = 37;
    const UNITSTATDEFLECTIONNEGATERATING = 38;
    const UNITSTATATTACKCRITICALNEGATERATING = 39;
    const UNITSTATABILITYCRITICALNEGATERATING = 40;
    const UNITSTATOFFENSE = 41;
    const UNITSTATDEFENSE = 42;
    const UNITSTATDEFENSEPENETRATION = 43;
    const UNITSTATEVASIONRATING = 44;
    const UNITSTATCRITICALRATING = 45;
    const UNITSTATEVASIONNEGATERATING = 46;
    const UNITSTATCRITICALNEGATERATING = 47;
    const UNITSTATOFFENSEPERCENTADDITIVE = 48;
    const UNITSTATDEFENSEPERCENTADDITIVE = 49;
    const UNITSTATDEFENSEPENETRATIONPERCENTADDITIVE = 50;
    const UNITSTATEVASIONPERCENTADDITIVE = 51;
    const UNITSTATEVASIONNEGATEPERCENTADDITIVE = 52;
    const UNITSTATCRITICALCHANCEPERCENTADDITIVE = 53;
    const UNITSTATCRITICALNEGATECHANCEPERCENTADDITIVE = 54;
    const UNITSTATMAXHEALTHPERCENTADDITIVE = 55;
    const UNITSTATMAXSHIELDPERCENTADDITIVE = 56;
    const UNITSTATSPEEDPERCENTADDITIVE = 57;
    const UNITSTATCOUNTERATTACKRATING = 58;
    const UNITSTATTAUNT = 59;
    const UNITSTATMASTERY = 61;

    public function displayString() {
        switch ($this->getKey()) {
            case 'UNITSTATSPEED': return 'speed'; break;
            case 'UNITSTATATTACKDAMAGE': return 'offense'; break;
            case 'UNITSTATABILITYPOWER': return 'offense'; break;
            case 'UNITSTATCRITICALDAMAGE': return 'critdamage'; break;
            case 'UNITSTATATTACKCRITICALRATING': return 'critchance'; break;
            case 'UNITSTATABILITYCRITICALRATING': return 'critchance'; break;
            case 'UNITSTATMAXHEALTH': return 'health'; break;
            case 'UNITSTATRESISTANCE': return 'tenacity'; break;
            case 'UNITSTATACCURACY': return 'potency'; break;

            default: return ''; break;
        }
    }

    public function shortString() {
        switch ($this->getKey()) {
            case 'UNITSTATSPEED': return 'speed';
            case 'UNITSTATOFFENSE': return 'offense';
            case 'UNITSTATOFFENSEPERCENTADDITIVE': return '% offense';
            case 'UNITSTATDEFENSE': return 'defense';
            case 'UNITSTATDEFENSEPERCENTADDITIVE': return '% defense';
            case 'UNITSTATMAXSHIELD': return 'protection';
            case 'UNITSTATMAXSHIELDPERCENTADDITIVE': return '% protection';
            case 'UNITSTATMAXHEALTH': return 'health';
            case 'UNITSTATMAXHEALTHPERCENTADDITIVE': return '% health';
            case 'UNITSTATACCURACY': return 'potency';
            case 'UNITSTATRESISTANCE': return 'tenacity';
            case 'UNITSTATCRITICALDAMAGE': return 'crit damage';
            case 'UNITSTATCRITICALCHANCEPERCENTADDITIVE': return 'crit chance';
            case 'UNITSTATCRITICALNEGATECHANCEPERCENTADDITIVE': return 'crit avoidance';
            case 'UNITSTATEVASIONNEGATEPERCENTADDITIVE': return 'accuracy';

            case 'UNITSTATCRITICALCHANCE': return 'crit chance';
            case 'UNITSTATCRITICALNEGATECHANCE': return 'crit avoidance';
            case 'UNITSTATEVASIONNEGATE': return 'accuracy';

            default: return ''; break;
        }
    }
}
