<?php
namespace App\Helpers;


use Vinkla\Hashids\Facades\Hashids;

/**
 * Helper para realizar validaciones como: validar que un email cumple con el formato adecuado, generar códigos y decodificar un hash_id
 * Created by PhpStorm.
 * User: Ing Kevin Cifuentes
 * Date: 26/03/2018
 * Time: 04:46 PM
 */
class Helpers
{

    public static function genCode($long)
    {
        $key = '';
        $pattern = '1234567890';
        $max = strlen($pattern) - 1;
        for ($i = 0; $i < $long; $i++) $key .= $pattern{mt_rand(0, $max)};
        return $key;
    }

    public static function genJson($data, $message)
    {
        return [
            'message' => $message,
            'data'    => $data,
        ];
    }

    public static function errorsJson($message, $errors = [])
    {
        if (empty($message))
            $message = 'The given data was invalid.';

        return [
            'message' => $message,
            'errors'  => $errors,
        ];
    }

    public static function decHashId($value)
    {
        $id = Hashids::decode($value);
        if (count($id) <= 0)
            return abort('404', trans('app.validates.hash_id'));

        return $id[0];
    }

    public static function decHumanDiffDate($date)
    {
        $resp = intval(preg_replace('/[^0-9]+/', '', $date->diffForHumans(null, true, false, true)), 10) .
            $date->diffForHumans(null, true, true, false);

        return $resp;
    }

    public static function isValidEmail($str)
    {
        $result = (false !== filter_var($str, FILTER_VALIDATE_EMAIL));
        if ($result)
        {
            list($user, $domain) = explode('@', $str);
            $result = checkdnsrr($domain, 'MX');
        }

        return $result;
    }
}
