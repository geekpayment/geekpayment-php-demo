<?php

/**
 *
 * GeekPay支付API异常类
 * @author Leijid
 *
 */
class GeekPayException extends Exception
{
    public function errorMessage()
    {
        return $this->getMessage();
    }
}
