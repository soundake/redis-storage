<?php
/**
 * Created by PhpStorm.
 * User: soundake
 * Date: 25/02/16
 * Time: 17:35
 */

namespace soundake;


use Nette\Caching\conditions;
use Nette\Caching\data;
use Nette\Caching\dependencies;
use Nette\Caching\IStorage;
use Nette\Caching\key;
use Predis\Client;

class RedisStorage implements IStorage
{
    use \Nette\SmartObject;

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->client->connect();
    }

    /**
     * Read from cache.
     * @param  string key
     * @return mixed|NULL
     */
    function read($key)
    {
        $data = $this->client->get($key);
        return $data;
    }

    /**
     * Prevents item reading and writing. Lock is released by write() or remove().
     * @param  string key
     * @return void
     */
    function lock($key)
    {
        return false;
    }

    /**
     * Writes item into the cache.
     * @param  string  key
     * @param  integer seconds
     * @param  mixed   data
     * @param  array   dependencies
     * @return void
     */
    function write($key, $data, array $dependencies)
    {
        $this->client->set($key, $data);
    }

    /**
     * Removes item from the cache.
     * @param  string key
     * @return void
     */
    function remove($key)
    {
        $this->client->del([$key]);
    }

    /**
     * Removes items from the cache by conditions.
     * @param  array  conditions
     * @return void
     */
    function clean(array $conditions)
    {
        $this->client->del($conditions);
    }
}