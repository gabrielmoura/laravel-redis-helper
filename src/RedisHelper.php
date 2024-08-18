<?php

namespace Gabrielmoura\RedisHelper;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static bool setArray(string $key, array $data)
 * @method static bool setCollection(string $key, Collection $data)
 * @method static bool setArrayField(string $key, string $field, mixed $data)
 * @method static array getArray(string $key)
 * @method static mixed getArrayField(string $key, string $field)
 * @method static array rememberArray(string $key, \Closure $callback, int $ttl = null)
 * @method static bool set(string $key, string $field, string $value)
 * @method static bool deleteArrayField(string $key, string $field)
 * @method static bool deleteArray(string $key)
 * @method static bool setList(string $key, array $data, $direction = Direction::DESC)
 * @method static array getList(string $key, int $start = 0, int $stop = -1)
 * @method static string getListIndex(string $key, int $index)
 * @method static bool deleteList(string $key)
 * @method static bool deleteListElement(string $key, string $element)
 * @method static bool setSet(string $key, string $member)
 * @method static array getSet(string $key)
 * @method static bool getSetMember(string $key, string $member)
 * @method static bool deleteSetMember(string $key, string $member)
 * @method static bool deleteSet(string $key)
 * @method static bool setSortedSet(string $key, string $member, int $score)
 * @method static array getSortedSet(string $key, int $start = 0, int $stop = -1, $direction = Direction::DESC)
 * @method static int getSortedSetScore(string $key, string $member)
 * @method static bool deleteSortedSetMember(string $key, string $member)
 * @method static bool deleteSortedSet(string $key)
 * @method static bool setBit(string $key, int $offset, int $value)
 * @method static int getBit(string $key, int $offset)
 * @method static int getBitCount(string $key)
 * @method static int getBitOp(string $operation, string $destKey, string $key1, string $key2)
 * @method static bool deleteBit(string $key)
 * @method static bool setGeo(string $key, float $longitude, float $latitude, string $member)
 * @method static array getGeo(string $key, string $member)
 * @method static float getGeoDistance(string $key, string $member1, string $member2, string $unit = 'm')
 * @method static array getGeoRadius(string $key, float $longitude, float $latitude, float $radius, string $unit = 'm', int $count = 0, $direction = Direction::ASC)
 * @method static bool deleteGeo(string $key)
 * @method static bool setHyperLogLog(string $key, string $element)
 * @method static int getHyperLogLog(string $key)
 * @method static bool deleteHyperLogLog(string $key)
 * @method static float getPing()
 * @method static array getKeys(string $pattern)
 * @method static bool getExists(string $key)
 * @method static bool setExpire(string $key, int $seconds)
 * @method static bool setPExpire(string $key, int $milliseconds)
 * @method static bool setExpireAt(string $key, string $timestamp)
 * @method static int getMemory(string $key)
 * @method static array getMemoryStats()
 */
class RedisHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'redis-helper';
    }
}
