<?php
/**
 * Created by PhpStorm.
 * User: AGC-PC
 * Date: 5/28/2018
 * Time: 4:23 PM
 */

namespace App\Functions;


class FiletypeClass
{

    public static function identify($content)
    {
        if (preg_match_all('/transaction types masterlist/i', $content, $matches) > 0)
        {
            return "transaction types";
        }
        if (preg_match_all('/consolidated.+prooflist/i', $content, $matches) > 0)
        {
            return "proof list";
        }
        if (preg_match_all('/general ledger/i', $content, $matches) > 0)
        {
            return "general ledger";
        }
        if (preg_match_all('/\n\s*\* \* \* s\/l code\:/i', $content, $matches) > 0)
        {
            return "subsidiary ledger";
        }
        if (preg_match_all('/chart of accounts/i', $content, $matches) > 0)
        {
            return "accounts";
        }
        if (preg_match_all('/subsidiary ledger codes masterlist/i', $content, $matches) > 0)
        {
            return "ledgers";
        }
        return "unknown";
    }
}