<p align="center">
    <a href="https://github.com/matthillman/swgoh-help" target="_blank">
    <h1>API wrapper for swgoh.help</h1>
    </a>
</p>
## Introduction

Laravel wrapper for the [swgoh.help](https://api.swgoh.help) API.

Requires Laravel 5.4 or greater

## Installation

```bash
# require via composer
composer require matthillman/swgoh-help
```

## Setup

#### Credentials

The wrapper looks for credentials stored in Laravel's "services" configuration under the "swgoh_help" key. You must include your username and password; you can optionally include the client_id and client_secret, but given how swogh.help is set up, this is not needed.

**Example configuration**:

config/services.php:
```php
<?php

return [

    # ... all the other config blocks

    'swgoh_help' => [
        'user' => env('SWGOH_HELP_USER'),
        'password' => env('SWGOH_HELP_PASSWORD'),
    ],

];
```

.env file:
```bash
SWGOH_HELP_USER=<api username>
SWGOH_HELP_PASSWORD=<api password>
```

## Usage

The wrapper provides four shortcut calls that wrap common projections around the actual API call as well as a generic function that can be used to craft any possible API query.

### Quick start

Calling the API is as simple as invoking the helper function followed by the query you want to make:

```php
// Retrieve all mods for a user
$mods = swgoh()->getMods('552325555');
```

### Convenience queries

Note that all queries return an instance of `Illuminate\Support\Collection`

**getPlayer($allyCode, $projection = [])**

Used to get a player's profile information (by default without their roster, though this can be overridden). Calls the `/player` endpoint with the following payload:
```php
[
    "allycode" => $allyCode,
    "language" => $this->lang,
    "enums" => $this->enums,
    "project" => array_merge([
        "allyCode" => 1,
        "name" => 1,
        "level" => 1,
        "guildName" => 1,
        "stats" => 1,
        "roster" => 0,
        "arena" => 1,
        "updated" => 1,
    ], $projection),
]
```

**getUnits($allyCode, $mods = false, $projection = [])**

Used to retrieve a player's unit list, optionally including mods. This calls the `/units` endpoint with with following payload:
```php
[
    "allycode" => $allyCode,
    "mods" => $mods,
    "language" => $this->lang,
    "enums" => $this->enums,
    "project" => array_merge([
        "player" => 0,
        "allyCode" => 0,
        "starLevel" => 1,
        "level" => 1,
        "gearLevel" => 1,
        "gear" => 1,
        "zetas" => 1,
        "type" => 1,
        "mods" => $mods ? 1 : 0,
        "gp" => 1,
        "updated" => 0,
    ], $projection),
]
```

**getMods($allyCode)**

Used to retrieve a collection of player's mods, keyed by unit. This calls the `/units` endpoint and is equivalent to calling this endpoint with `projection: { mods: 1 }`. This is a shortcut for calling `getUnits()` with the following projection override:
```php
$data = [
    "player" => 0,
    "allyCode" => 0,
    "starLevel" => 0,
    "level" => 0,
    "gearLevel" => 0,
    "gear" => 0,
    "zetas" => 0,
    "type" => 0,
    "mods" => 1,
    "gp" => 0,
    "updated" => 0,
];
```

**getGuild($allyCode, Callable $memberCallback, $fullRoster = false, $mods = false, $projection = [])**

Used to retrieve information about a guild and calls into the `/guild` endpoint. As this is the most complicated helper, let's expand upon it.

If you are including rosters, it is very likely that this call would produce a return that puts PHP's json decoder over the default PHP memory limit. To avoid this, this call will process

  * *$allyCode*: The ally code of any member in the guild
  * *$memberCallback*: A closure or other PHP Callable. This callback will be invoked with each completed object that is returned under the top level `roster` key—if you pass `SwgohHelp\API::FULL_ROSTER`, then the callback will be invoked with each member's data, if you pass `SwgohHelp\API::FULL_UNITS` then it will be called with each unit collection. **NOTE**, to take advantage of the streaming parser you must provide a callback.
  Since each item in `roster` is being processed as they are read, the final return from the function will not include a roster key.
  * *$fullRoster*: Should be either `SwgohHelp\API::FULL_ROSTER`, `SwgohHelp\API::FULL_UNITS`, or `false`, depending on how you want the `roster` key projected.
  * *$mods*: `true` if you want to include mods in your roster return, `false` otherwise.
  * *$projection*: projection overrides

The full payload passed to the endpoint is the following:
```php
$rosterInner = [
    "defId" => 1,
    "rarity" => 1,
    "level" => 1,
    "gear" => 1,
    "combatType" => 1,
    "gp" => 1,
    "skills" => 1,
    "mods" => $mods ? 1 : 0,
];
$data = [
    "allycode" => $allyCode,
    "roster" => $fullRoster == static::FULL_ROSTER,
    "units" => $fullRoster == static::FULL_UNITS,
    "mods" => $mods,
    "language" => $this->lang,
    "enums" => $this->enums,
    "project" => array_merge([
        "name" => 1,
        "desc" => 1,
        "members" => 1,
        "status" => 1,
        "required" => 0,
        "bannerColor" => 1,
        "bannerLogo" => 1,
        "message" => 1,
        "gp" => 1,
        "raid" => 0,
        "roster" => $fullRoster == static::FULL_UNITS ? $rosterInner : $fullRoster == static::FULL_UNITS ? [
            "allyCode" => 1,
            "name" => 1,
            "level" => 1,
            "stats" => 1,
            "roster" => $rosterInner,
            "arena" => 1,
            "updated" => 1,
        ] : 0,
        "updated" => 0,
    ], $projection),
];
```

**getUnitData($match = [], $projection = [])**

Retrieves the unit list by calling the `/data` endpoint. Uses the following projection:
```php
$data = [
    "collection" => "unitsList",
    "language" => $this->lang,
    "enums" => $this->enums,
    "match" => array_merge([ "rarity" => 7 ], $match),
    "project" => array_merge([
        "baseId" => 1,
        "nameKey" => 1,
        "thumbnailName" => 1,
        "basePower" => 1,
        "descKey" => 1,
        "combatType" => 1,
        "forceAlignment" => 1,
        "combatType" => 1,
        "skillReferenceList" => [ "skillId" => 1 ],
    ], $projection),
];
```

**function getZetaData()**

This function makes two calls to the `/data` endpoint to get a list of all zetas in the the game. The query correlates a call to the `skillList` with `abilityList`. The return will be in the following structure:

```php
[
    [
        'name' => <human readable ability name>,
        'id' => <maps to a skill_id, eg: 'uniqueskill_LOGRAY01'>,
        'class' => <Unique|Special|Leader>
       ]
    ]
]
```

### Main query

**function callAPI($api, $payload, Callable $memberCallback = null)**

This is the main function of the wrapper. You can use this to craft any payload needed for any API endpoint.

  * *$api*: The endpoint to query. The API class contains some convenience constants that are outlined below. Note that you can pass strings directly if you don't want to use the constants, and you can pass either `/player` or `player`.
  * *$payload*: The payload to send to the endpoint, should be an associative array that maps to the correct json for the payload.
  * *$memberCallback*: See the documentation for `getGuild()`. In this instance, if the callback exists then the incremental guild parser will be used, if not the regular in-memory parser is used.

```php
    const API_PLAYER = 'player';
    const API_UNITS = 'units';
    const API_GUILD = 'guild';
    const API_DATA = 'data';
```

### Creation and options

There are two options to get an API wrapper to work with, either by using the helper function or just creating a new instance.

```php
# helper function
swgoh();
# construction
$swogh = new SwgohHelp\API;
```

The language key and enum are properties on the `API` class itself. They default to `eng_us` and `true`, respectively. If you are keeping a reference to the API wrapper then you can set them before querying, If you are using the helper function (or just want to), then there are two helper calls that can be chained before the query calls. (Since enums are true by defualt, the chaining helper is only for diabling them)

The following are equivalent:
```php
# object
$swgoh->lang = 'eng_us';
$swogh->enums = false;
$swogh->callAPI();

# helpers
swgoh()->lang('eng_us')->withoutEnums()->callAPI();
```

### Parser classes

In case an API wrapper wasn't enough, also included are three *additional* wrappers! 🎉

Each parser class takes a constructor argument so that it knows what to work on, but does not do any work until you call `scrape()`.

#### ProfileParser

Parser the results of the `getPlayer()` call. It maps the player stats into a collection and transforms the updated timestamp into a proper Carbon date.

Example
```php
$parser = new SwoghHelp\Parsers\ProfileParser('552325555');
$parser->scrape();
# The user data
$parser->getUser();
# The updated date
$parser->updated();
```

#### ModsParser

Parses the results of the `getMods()` call. It maps the player mod data into an array of items (mods) with the following format:

```php
[
    [
        'uid' => $mod['id'],
        'slot' => (new ModSlot($index))->getKey(),
        'set' => (new ModSet(+$mod['set']))->getKey(),
        'pips' => $mod['pips'],
        'level' => $mod['level'],
        'tier' => $mod['tier'],
        'location' => $char,

        'primary' => ['type' => <type enum>, 'value' => <value>],
        'secondaries' => [
            'type' => ['value' => <value>, 'roll' => <roll>],
            ...
        ],
    ],
    ...
]
```

Note the use of the `ModSet` and `ModSlot` enums.

Example

```php
$parser = new SwoghHelp\Parsers\ModsParser('552325555');
$parser->scrape();
# The mod data
$parser->getMods();
```

### GuildParser

This class wraps the `getGuild()` call. However, instead of taking an ally code, this class takes the ID number from the guild's url on swogh.gg.

<p align="center"><img src="https://media.giphy.com/media/h9hoPqk7Db5fi/giphy.gif"></p>

Yes, that is correct, the ID from the guild's swogh.gg url, which is completely unrelated from swgoh.help.

**But why?**

Because, I wanted to make something that would query information for any guild. The swgoh.help API requires an ally code for any member in the guild. Imagine this scenario:

  1. System: Hey, input the ally code from any user in the guild!
  2. User: Hmmm, let me go search swgoh.gg and find one
  3. User: google "guild name swgoh"
  4. User: click swgoh.gg link
  5. Smart user: click any member, note ally code in the url
  6. less smart user: click each member to find one with an ally code listed
  7. All users: return, enter the ally code they found

swgoh.gg *is* the public directory for this information; people will have an easier time find the ID from the guild url rather than an ally code from a random guild member.

**Ok but how are you mapping this to swogh.help?**

Easy, `GuildParser` does steps 2-5 for you (minus the googling, as you provided the guild ID). Once it finds an ally code, it will use that as the input to the swgoh.help call.

**Ok, ok… but just to be sure, what number are you talking about?

    https://swgoh.gg/g/3577/return-of-the-schwartz/

In the above URL, the number you want is `3577`.

**This is unnecessary, I will always have an ally code**

Great! `GuildParser` is not for you. You can call `getGuild()` or build your own payload for `callAPI()` directly. All this wrapper is doing is feting an ally code for you and then calling `getGuild()` with the `FULL_ROSTER` option and returning the first item in the result set.

Also note the same rules for the `getGuild()` callback apply to the argument to `scrape()`, and if you do not provide a callback then the in memory decoder will be used (and will likely hit PHP's memory limit, unless you have raised it).

Example

```php
$parser = new SwoghHelp\Parsers\GuildParser('3577');
$parser->scrape(function($member_data) {
    # Do something with the member data
});
# The guild data (minus the roster key if you provided a callback)
$parser->data;
# Guild name
$parser->name();
# Guild GP
$parser->gp();
# Guild roster (if you did not provide a callback, if you did this is null)
$parser->members();
# Guild's swogh.gg url
$parser->url();
```

## Questions

If you have questions or run into problems, I am available on Discord and hang out on the official api.swgoh.help discord server. Just tag Frax!

## License

This wrapper is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).