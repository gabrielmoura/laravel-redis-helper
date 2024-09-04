<?php

namespace Gabrielmoura\RedisHelper;

class LuaScript
{
    /**
     * @description Incrementa um contador e verifica se atingiu o limite
     */
    public const COUNTER_CHECK = <<<'LUA'
-- Incrementa um contador e verifica se atingiu o limite
redis.call('INCR', KEYS[1])
local count = tonumber(redis.call('GET', KEYS[1]))
return count <= tonumber(ARGV[1])
LUA;

    /**
     * @description Busca e deleta as chaves conforme o padrão
     * @description Se $returnKeys for true, retorna as chaves deletadas
     * @description Se $returnKeys for false, retorna true
     */
    public const KEYS_DEL = <<<'LUA'
local cursor = "0"
local deletedKeys = {}
local keys

repeat
    cursor, keys = unpack(redis.call("SCAN", cursor, "MATCH", ARGV[1], "COUNT", 1000))

    if #keys > 0 then
        redis.call("DEL", unpack(keys))
        if ARGV[2] == "1" then
            for i, key in ipairs(keys) do
                table.insert(deletedKeys, key)
            end
        end
    end

until cursor == "0"

if ARGV[2] == "1" then
    return deletedKeys
end

return 1
LUA;
}
