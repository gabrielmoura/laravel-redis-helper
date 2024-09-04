<?php

namespace Gabrielmoura\RedisHelper;

use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Collection;

class RedisHelperClass
{
    private Connection $redis;

    public function __construct(Connection $connection)
    {
        $this->redis = $connection;
    }

    /**
     * @description Define um array associativo a um hash
     */
    public function setArray(string $key, array $data): bool
    {
        return $this->redis->command('HMSET', [$key, $data]);
    }

    public function setCollection(string $key, Collection $data): bool
    {
        return $this->redis->command('HMSET', [$key, $data->toArray()]);
    }

    /**
     * @description Define um campo de um hash
     *
     * @param  array  $data
     */
    public function setArrayField(string $key, string $field, mixed $data): bool
    {
        return $this->redis->command('HSET', [$key, $field, $data]);
    }

    /**
     * @description Retorna um array associativo de um hash
     */
    public function getArray(string $key): array
    {
        return $this->redis->command('HGETALL', [$key]);
    }

    /**
     * @description Retorna um valor de um campo de um hash caso exista
     */
    public function getArrayField(string $key, string $field): mixed
    {
        return $this->redis->command('HGET', [$key, $field]);
    }

    /**
     * @description Retorna um valor de um campo de um hash caso exista
     */
    public function rememberArray(string $key, \Closure $callback, ?int $ttl = null): array
    {
        $value = $this->getArray($key);
        if (empty($value)) {
            $data = $callback();
            $this->setArray($key, $data);
            if ($ttl !== null) {
                $this->setExpire($key, $ttl);
            }

            return $data;
        }

        return $value;
    }

    /**
     * @description Define um valor a um campo de um hash
     */
    public function set(string $key, string $field, string $value): bool
    {
        return $this->redis->command('HSET', [$key, $field, $value]);
    }

    /**
     * @description Deleta um campo de um hash
     */
    public function deleteArrayField(string $key, string $field): bool
    {
        return $this->redis->command('HDEL', [$key, $field]);
    }

    /**
     * @description Deleta um  hash
     */
    public function deleteArray(string $key): bool
    {
        return $this->delete($key);
    }

    /**
     * @description Define uma lista
     */
    public function setList(string $key, array $data, $direction = Direction::DESC): bool
    {
        $command = ($direction === 'ASC') ? 'LPUSH' : 'RPUSH';

        return $this->redis->command($command, [$key, $data]);
    }

    /**
     * @description Retorna uma lista
     */
    public function getList(string $key, int $start = 0, int $stop = -1): array
    {
        return $this->redis->command('LRANGE', [$key, $start, $stop]);
    }

    /**
     * @description Retorna um elemento da lista
     */
    public function getListIndex(string $key, int $index): string
    {
        return $this->redis->command('LINDEX', [$key, $index]);
    }

    /**
     * @description Deleta a lista
     */
    public function deleteList(string $key): bool
    {
        return $this->delete($key);
    }

    /**
     * @description Deleta um elemento da lista
     */
    public function deleteListElement(string $key, string $element): bool
    {
        return $this->redis->command('LREM', [$key, 0, $element]);
    }

    /**
     * @description Define um conjunto
     */
    public function setSet(string $key, string $member): bool
    {
        return $this->redis->command('SADD', [$key, $member]);
    }

    /**
     * @description Retorna um conjunto
     */
    public function getSet(string $key): array
    {
        return $this->redis->command('SMEMBERS', [$key]);
    }

    /**
     * @description Retorna um elemento do conjunto
     */
    public function getSetMember(string $key, string $member): bool
    {
        return $this->redis->command('SISMEMBER', [$key, $member]);
    }

    /**
     * @description Deleta um elemento do conjunto
     */
    public function deleteSetMember(string $key, string $member): bool
    {
        return $this->redis->command('SREM', [$key, $member]);
    }

    /**
     * @description Deleta o conjunto
     */
    public function deleteSet(string $key): bool
    {
        return $this->delete($key);
    }

    /**
     * @description Define um conjunto ordenado
     */
    public function setSortedSet(string $key, string $member, int $score): bool
    {
        return $this->redis->command('ZADD', [$key, $score, $member]);
    }

    /**
     * @description Retorna um conjunto ordenado
     */
    public function getSortedSet(string $key, int $start = 0, int $stop = -1, $direction = Direction::DESC): array
    {
        $command = ($direction === 'ASC') ? 'ZRANGE' : 'ZREVRANGE';

        return $this->redis->command($command, [$key, $start, $stop]);
    }

    /**
     * @description Retorna um elemento do conjunto ordenado
     */
    public function getSortedSetScore(string $key, string $member): int
    {
        return $this->redis->command('ZSCORE', [$key, $member]);
    }

    /**
     * @description Deleta um elemento do conjunto ordenado
     */
    public function deleteSortedSetMember(string $key, string $member): bool
    {
        return $this->redis->command('ZREM', [$key, $member]);
    }

    /**
     * @description Deleta o conjunto ordenado
     */
    public function deleteSortedSet(string $key): bool
    {
        return $this->delete($key);
    }

    private function delete(string $key): bool
    {
        return $this->redis->command('DEL', [$key]);
    }

    // bit

    /**
     * @description Define um bit
     */
    public function setBit(string $key, int $offset, int $value): bool
    {
        return $this->redis->command('SETBIT', [$key, $offset, $value]);
    }

    /**
     * @description Retorna um bit
     */
    public function getBit(string $key, int $offset): int
    {
        return $this->redis->command('GETBIT', [$key, $offset]);
    }

    /**
     * @description Retorna a quantidade de bits
     */
    public function getBitCount(string $key): int
    {
        return $this->redis->command('BITCOUNT', [$key]);
    }

    /**
     * @description Retorna a quantidade de bits com valor 1
     */
    public function getBitOp(string $operation, string $destKey, string $key1, string $key2): int
    {
        return $this->redis->command('BITOP', [$operation, $destKey, $key1, $key2]);
    }

    /**
     * @description Deleta o bit
     */
    public function deleteBit(string $key): bool
    {
        return $this->delete($key);
    }

    // geo

    /**
     * @description Define um geo
     */
    public function setGeo(string $key, float $longitude, float $latitude, string $member): bool
    {
        return $this->redis->command('GEOADD', [$key, $longitude, $latitude, $member]);
    }

    /**
     * @description Retorna um geo
     */
    public function getGeo(string $key, string $member): array
    {
        return $this->redis->command('GEOPOS', [$key, $member]);
    }

    /**
     * @description Retorna a distância entre dois membros
     */
    public function getGeoDistance(string $key, string $member1, string $member2, string $unit = 'm'): float
    {
        return $this->redis->command('GEODIST', [$key, $member1, $member2, $unit]);
    }

    /**
     * @description Retorna os membros num raio
     */
    public function getGeoRadius(string $key, float $longitude, float $latitude, float $radius, string $unit = 'm', int $count = 0, $direction = Direction::ASC): array
    {
        $command = ($direction === 'ASC') ? 'GEORADIUS' : 'GEORADIUSBYMEMBER';

        return $this->redis->command($command, [$key, $longitude, $latitude, $radius, $unit, 'COUNT', $count]);
    }

    /**
     * @description Deleta o geo
     */
    public function deleteGeo(string $key): bool
    {
        return $this->delete($key);
    }

    /** HyperLog */

    /**
     * @description Define um HyperLogLog
     */
    public function setHyperLogLog(string $key, string $element): bool
    {
        return $this->redis->command('PFADD', [$key, $element]);
    }

    /**
     * @description Retorna um HyperLogLog
     */
    public function getHyperLogLog(string $key): int
    {
        return $this->redis->command('PFCOUNT', [$key]);
    }

    /**
     * @description Deleta o HyperLogLog
     */
    public function deleteHyperLogLog(string $key): bool
    {
        return $this->delete($key);
    }

    /**
     * @description Retorna o tempo de execução do comando PING em milissegundos
     */
    public function getPing(): float
    {
        $atual = microtime(true);
        $this->redis->command('PING');
        $final = microtime(true);

        return ($final - $atual) * 1000;
    }

    /**
     * @description Retorna a chave conforme o padrão
     */
    public function getKeys(string $pattern): array
    {
        return $this->redis->command('KEYS', [$pattern]);
    }

    /**
     * @description Verifica se a chave existe
     */
    public function getExists(string $key): bool
    {
        return $this->redis->command('EXISTS', [$key]);
    }

    /**
     * @description Define o tempo de vida da chave em segundos
     */
    public function setExpire(string $key, int $seconds): bool
    {
        return $this->redis->command('EXPIRE', [$key, $seconds]);
    }

    /**
     * @description Define o tempo de vida da chave em milissegundos
     */
    public function setPExpire(string $key, int $milliseconds): bool
    {
        return $this->redis->command('PEXPIRE', [$key, $milliseconds]);
    }

    /**
     * @description Define o tempo de vida da chave em uma data-hora
     */
    public function setExpireAt(string $key, string $timestamp): bool
    {
        $timestampOk = strtotime($timestamp);

        return $this->redis->command('EXPIREAT', [$key, $timestampOk]);
    }

    /**
     * @description Retorna o uso de memória da chave
     */
    public function getMemory(string $key): int
    {
        return $this->redis->command('MEMORY', ['USAGE', $key]);
    }

    /**
     * @description Retorna o uso de memória do servidor
     */
    public function getMemoryStats(): array
    {
        return $this->redis->command('MEMORY', ['STATS']);
    }

    /**
     * @description Busca e deleta as chaves conforme o padrão
     * @description Se $returnKeys for true, retorna as chaves deletadas
     * @description Se $returnKeys for false, retorna true
     */
    public function keysDel(string $pattern, bool $returnKeys = false): bool|array
    {
        return $this->redis->eval(LuaScript::KEYS_DEL, 0, $pattern, $returnKeys);
    }
}
