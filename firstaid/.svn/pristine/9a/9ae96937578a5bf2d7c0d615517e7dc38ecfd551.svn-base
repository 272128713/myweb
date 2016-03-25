<?php
class TcpConnection2{
    private $socket;
    private $connected;

    public function __construct($serverAddr, $port){
        $this->connected = false;       
        $this->socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($this->socket){   
            $count = 1;
            while($count <= 3){
               // echo $serverAddr,$port,$this->socket;
                $conn = @socket_connect($this->socket, $serverAddr, $port);
                if ($conn){                
                    $this->connected = true;
                    break;
                }
                $count++;
            }
        }
    }

    public function tcpSend($cmd){
        $ret = false;
        $sent = @socket_write($this->socket, $cmd);

        if ($sent === false){            
            return $ret;
        } else{
            while($buffer = @socket_read($this->socket, 1024, PHP_BINARY_READ)){
					$ret .= $buffer;
            }
        }
        // echo socket_strerror(socket_last_error($this->socket));
        return $ret;
    }

	/**
	 * Socket 是否连接
	 */
    public function isConnected(){
        return $this->connected;
    }

	/**
	 * 关闭 Socket
	 */
    public function close(){
        socket_close($this->socket);
    }

	/*
	 * 得到 socket 的最后一次的错误代码和错误字符串，格式：
	 * (ErrorNo:$errornumber)$ErrorString
	 */
    public function getSocketErrorString() {
		$err = socket_last_error($this->socket);
        return "(ErrorNo:$err)" . socket_strerror($err);
    }
    
    public function setReadTimeout($seconds, $useconds){
		$timeout = socket_get_option($this->socket, SOL_SOCKET, SO_RCVTIMEO);
		$timeout["sec"] = $seconds;
		$timeout["usec"] = $useconds;
		socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, $timeout);
	}
    
    
}
?>
