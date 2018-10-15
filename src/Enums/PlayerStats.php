<?php

namespace SwgohHelp\Enums;

use MyCLabs\Enum\Enum;

class PlayerStats extends Enum {
    const gp = 'STAT_GALACTIC_POWER_ACQUIRED_NAME';
    const charGP = 'STAT_CHARACTER_GALACTIC_POWER_ACQUIRED_NAME';
    const shipGP = 'STAT_SHIP_GALACTIC_POWER_ACQUIRED_NAME';
    const fleetWon = 'STAT_PVP_SHIP_BATTLES_WIN_NAME';
    const arenaWon = 'STAT_PVP_BATTLES_WIN_NAME_TU07_2';
    const pveWon = 'STAT_PVE_BATTLES_WIN_NAME_TU07_2';
    const hardWon = 'STAT_PVE_HARD_BATTLES_WIN_NAME_TU07_2';
    const gwWon = 'STAT_TOTAL_GALACTIC_WON_NAME_TU07_2';
    const raidsWon = 'STAT_GUILD_RAID_WON_NAME_TU07_2';
    const guildTokens = 'STAT_TOTAL_GUILD_CONTRIBUTION_NAME_TU07_2';
    const donated = 'STAT_TOTAL_GUILD_EXCHANGE_DONATIONS_TU07_2';
}