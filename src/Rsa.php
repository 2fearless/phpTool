<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/24
 * Time: 15:28
 */

namespace fearless\Tools;

/**
 * demo
 *  $password = new Rsa($pk);
    $token = $password->encrypt('123');
 */

class Rsa
{

    public $head = "-----BEGIN PUBLIC KEY-----\n";
    public $foot = "-----END PUBLIC KEY-----\n";
    public $public_key = '';
    public function __construct($key)
    {
        $this->public_key =$this->head.$key."\n".$this->foot;;
    }


    /**
     * 公钥加密数据
     * public key 加密
     * @param string $data 密码
     * @param string $code
     * @param int $padding
     * @return bool|string
     */
    public function encrypt($data, $code = 'base64', $padding = OPENSSL_PKCS1_PADDING)
    {
        $ret = false;
        if (openssl_public_encrypt($data, $result, $this->public_key, $padding)) {
            $ret = $this->_encode($result, $code);
        }
        return $ret;
    }

    private function _encode($data, $code)
    {
        switch (strtolower($code)) {
            case 'base64':
                $data = base64_encode('' . $data);
                break;
            case 'hex':
                $data = bin2hex($data);
                break;
            case 'bin':
            default:
        }
        return $data;
    }

}