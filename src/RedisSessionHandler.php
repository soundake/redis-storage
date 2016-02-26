<?php
/**
 * Created by PhpStorm.
 * User: soundake
 * Date: 25/02/16
 * Time: 18:25
 */

namespace soundake;

use Nette\Object;
use Predis\Client;

class RedisSessionHandler extends Object implements \SessionHandlerInterface
{

	private $redis;

	private $ttl;

	public function __construct(Client $redis)
	{
		$this->redis = $redis;
		$this->redis->connect();
	}
	/**
	 * Close the session
	 * @link http://php.net/manual/en/sessionhandlerinterface.close.php
	 * @return bool
	 * @since 5.4.0
	 */
	public function close()
	{
		return TRUE;
	}

	/**
	 * Destroy a session
	 * @link http://php.net/manual/en/sessionhandlerinterface.destroy.php
	 * @param string $session_id The session ID being destroyed.
	 * @return bool
	 * @since 5.4.0
	 */
	public function destroy($session_id)
	{
		return $this->redis->del($session_id);
	}

	/**
	 * Cleanup old sessions
	 * @link http://php.net/manual/en/sessionhandlerinterface.gc.php
	 * @param int $maxlifetime
	 * @return bool
	 * @since 5.4.0
	 */
	public function gc($maxlifetime)
	{
		// TODO: Implement gc() method.
	}

	/**
	 * Initialize session
	 * @link http://php.net/manual/en/sessionhandlerinterface.open.php
	 * @param string $save_path The path where to store/retrieve the session.
	 * @param string $session_id The session id.
	 * @return bool
	 * @since 5.4.0
	 */
	public function open($save_path, $session_id)
	{
		return TRUE;
	}

	/**
	 * Read session data
	 * @link http://php.net/manual/en/sessionhandlerinterface.read.php
	 * @param string $session_id The session id to read data for.
	 * @return string
	 * @since 5.4.0
	 */
	public function read($session_id)
	{
		return $this->redis->get($session_id);
	}

	/**
	 * Write session data
	 * @link http://php.net/manual/en/sessionhandlerinterface.write.php
	 * @param string $session_id The session id.
	 * @param string $session_data
	 * @return bool
	 * @since 5.4.0
	 */
	public function write($session_id, $session_data)
	{
		return $this->redis->setex($session_id, $this->ttl, $session_data);
	}

	/**
	 * @param int $expiration
	 * @return void
	 */
	public function setExpiration($expiration = 0)
	{
		if(is_string($expiration)) {
			$now = new \DateTime;
			$exp = clone $now;
			$exp->modify($expiration);
			$this->ttl = abs($now->getTimestamp() - $exp->getTimestamp());
		} elseif($expiration === NULL) {
			$this->ttl = 0;
		}
	}
}