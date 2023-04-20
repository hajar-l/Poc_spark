<?php

namespace App\Service;

use InvalidArgumentException;

class RequestService
{

    public function is_valid_domain_name(string $domain): bool
    {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain)
            && preg_match("/^.{1,253}$/", $domain)
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain)   );
    }
    public function run(string $domain): string
    {
        if (!$this->is_valid_domain_name($domain))
            throw new InvalidArgumentException("Domain '$domain' is invalid.");
        $output = shell_exec("echo '$domain' | subfinder -silent | dnsx -silent | httpx -silent | nuclei -silent -s medium,high,critical -json");
        if (!isset($output))
            return "error.";
        return $output;
    }

}