<?php
/**
 * Created by PhpStorm.
 * User: moren
 * Date: 20.05.2018
 * Time: 01:21
 */

namespace Middleware;


use Symfony\Component\HttpFoundation\Request;

class Authenticate
{
    public static function authenticate(Request $request) : bool
    {
        $auth = $request->headers->get("Authenticate");
        if (isset($auth) && $auth == "dfhgfH$26y3rhfgh%%^4tyrthrfdgf") {
            return true;
        }

        return false;
    }
}