<?php
/**
 * websocket demo 
 * 
 */
class WebSocket
{

	/**
	 * 事件
	 *
	 * @var string
	 */
	const EVENT_SOCKET_CONNECT = 'socket_connect';
	const EVENT_SOCKET_DISCONNECT = 'socket_disconnect';
	const EVENT_CLIENT_DATA = 'client_data';

	/**
	 * Socket
	 *
	 * @var unknown
	 */
	private $master;

	/**
	 * Socket连接池
	 *
	 * @var array
	 */
	private $sockets = [ ];

	/**
	 * Socket用户池
	 *
	 * @var array
	 */
	private $clients = [ ];

	/**
	 * 所有的注册事件。
	 *
	 * @var array
	 */
	public $events = [ ];

	/**
	 * 构造方法
	 */
	public function __construct()
	{
		set_time_limit ( 0 );
		ob_implicit_flush ();
	}

	/**
	 * 创建Socket监听并绑定到接口
	 *
	 * @param string $address 绑定的IP
	 * @param int $port 绑定的端口
	 */
	public function bind($address, $port)
	{
		$this->master = socket_create ( AF_INET, SOCK_STREAM, SOL_TCP );
		socket_set_option ( $this->master, SOL_SOCKET, SO_REUSEADDR, 1 );
		$this->log ( 'Server Started : ' . date ( 'Y-m-d H:i:s' ) );
		socket_bind ( $this->master, $address, $port );
		socket_listen ( $this->master );
		$this->log ( 'Listening on : ' . $address . ' port ' . $port );
		socket_set_nonblock ( $this->master );
		$this->sockets = [
				's' => $this->master
		];
	}

	/**
	 * 发送握手包
	 *
	 * @param int $k
	 * @param string $buffer
	 * @return boolean
	 */
	private function handshake($clientID, $buffer)
	{
		$buf = substr ( $buffer, strpos ( $buffer, 'Sec-WebSocket-Key:' ) + 18 );
		$key = trim ( substr ( $buf, 0, strpos ( $buf, "\r\n" ) ) );
		$newKey = base64_encode ( sha1 ( $key . "258EAFA5-E914-47DA-95CA-C5AB0DC85B11", true ) );
		$newMessage = "HTTP/1.1 101 Switching Protocols\r\n";
		$newMessage .= "Upgrade: websocket\r\n";
		$newMessage .= "Sec-WebSocket-Version: 13\r\n";
		$newMessage .= "Connection: Upgrade\r\n";
		$newMessage .= "Sec-WebSocket-Accept: " . $newKey . "\r\n\r\n";
		socket_write ( $this->clients [$clientID] ['socket'], $newMessage, strlen ( $newMessage ) );
		$this->clients [$clientID] ['handshake'] = true;
		return true;
	}

	/**
	 * 注册一个事件处理器
	 *
	 * @param string $event 事件名称
	 * @param mixed $callback 回调
	 * @return void
	 */
	public function listenEvent($event, $callback)
	{
		$this->events [$event] [] = $callback;
	}

	/**
	 * 打印日志
	 *
	 * @param string $message 消息类型
	 */
	public function log($message)
	{
		echo $message . "\r\n";
	}

	/**
	 * 执行守护进程
	 */
	public function run()
	{
		$this->log ( 'runing...' );
		while ( true ) {
			$sockets = $this->sockets;
			socket_select ( $sockets, $write = NULL, $except = NULL, NULL );
			foreach ( $sockets as $socket ) {
				if ($socket == $this->master) { // 连接主机的 client
					$client = socket_accept ( $this->master );
					if ($client < 0) {
						$this->log ( "socket_accept() failed." );
						continue;
					} else {
						array_push ( $this->sockets, $client );
						array_push ( $this->clients, [
								'socket' => $client,
								'handshake' => false
						] );
						// 触发事件EVENT_SOCKET_CONNECT
						$this->trigger ( static::EVENT_SOCKET_CONNECT, [
								'socket' => $client
						] );
					}
				} else {
					$bytes = @socket_recv ( $socket, $buffer, 2048, 0 );
					$clientID = $this->search ( $socket );
					if ($bytes < 7) {
						$this->close ( $socket );
						// 触发事件
						$this->trigger ( static::EVENT_SOCKET_DISCONNECT, [
								'socket' => $socket
						] );
						continue;
					}
					if (! $this->clients [$clientID] ['handshake']) { // 没有握手进行握手
						$this->handshake ( $clientID, $buffer );
					} else {
						$buffer = $this->decode ( $buffer );
						// 触发事件
						$response = $this->trigger ( static::EVENT_CLIENT_DATA, [
								'msg' => $buffer
						], true );
						$this->send ( $socket, $response );
					}
				}
			}
		}
	}

	/**
	 * 发送消息到客户端
	 */
	public function send($socket, $msg)
	{
		$msg = $this->encode ( $msg );
		return socket_write ( $socket, $msg, strlen ( $msg ) );
	}
