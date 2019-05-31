<?php
/**
 * Created by PhpStorm.
 * User: moren
 * Date: 02.06.2018
 * Time: 11:02
 */

namespace Exceptions;


use Exception;

class WalletException extends Exception
{
    /**
     * NoRouteFoundException constructor.
     * @param $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}