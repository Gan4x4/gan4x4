<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HtpasswdBasicAuth
{
    public function handle(Request $request, Closure $next)
    {
        $path = env('HTPASSWD_PATH', storage_path('app/htpasswd'));
        $realm = env('HTPASSWD_REALM', 'Admin');

        if (!is_readable($path)) {
            return response('Admin auth is not configured.', 503);
        }

        [$username, $password] = $this->getCredentials($request);

        if (!$username || !$password) {
            return $this->unauthorized($realm);
        }

        $hash = $this->getHashForUser($path, $username);
        if (!$hash || !$this->verifyPassword($password, $hash)) {
            return $this->unauthorized($realm);
        }

        return $next($request);
    }

    private function unauthorized(string $realm)
    {
        return response('Unauthorized.', 401, [
            'WWW-Authenticate' => 'Basic realm="'.$realm.'", charset="UTF-8"',
        ]);
    }

    private function getCredentials(Request $request): array
    {
        $username = $request->getUser();
        $password = $request->getPassword();
        if ($username && $password) {
            return [$username, $password];
        }

        $header = $request->header('Authorization');
        if (!$header) {
            return [null, null];
        }

        if (stripos($header, 'Basic ') !== 0) {
            return [null, null];
        }

        $decoded = base64_decode(substr($header, 6), true);
        if (!$decoded || strpos($decoded, ':') === false) {
            return [null, null];
        }

        [$username, $password] = explode(':', $decoded, 2);
        return [$username, $password];
    }

    private function getHashForUser(string $path, string $username): ?string
    {
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return null;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, '#') === 0) {
                continue;
            }

            $parts = explode(':', $line, 2);
            if (count($parts) !== 2) {
                continue;
            }

            if (hash_equals($parts[0], $username)) {
                return trim($parts[1]);
            }
        }

        return null;
    }

    private function verifyPassword(string $password, string $hash): bool
    {
        if (strpos($hash, '$2y$') === 0 || strpos($hash, '$2a$') === 0 || strpos($hash, '$2b$') === 0) {
            return password_verify($password, $hash);
        }

        if (strpos($hash, '$apr1$') === 0) {
            return hash_equals($hash, $this->apr1Md5($password, $hash));
        }

        $crypt = crypt($password, $hash);
        return is_string($crypt) && hash_equals($crypt, $hash);
    }

    private function apr1Md5(string $password, string $hash): string
    {
        $parts = explode('$', $hash);
        $salt = $parts[2] ?? '';
        $salt = substr($salt, 0, 8);

        $len = strlen($password);
        $text = $password . '$apr1$' . $salt;
        $bin = pack('H32', md5($password . $salt . $password));

        for ($i = $len; $i > 0; $i -= 16) {
            $text .= substr($bin, 0, min(16, $i));
        }

        for ($i = $len; $i > 0; $i >>= 1) {
            $text .= ($i & 1) ? chr(0) : $password[0];
        }

        $bin = pack('H32', md5($text));

        for ($i = 0; $i < 1000; $i++) {
            $new = ($i & 1) ? $password : $bin;
            if ($i % 3) {
                $new .= $salt;
            }
            if ($i % 7) {
                $new .= $password;
            }
            $new .= ($i & 1) ? $bin : $password;
            $bin = pack('H32', md5($new));
        }

        $hash = '';
        $hash .= $this->to64((ord($bin[0]) << 16) | (ord($bin[6]) << 8) | ord($bin[12]), 4);
        $hash .= $this->to64((ord($bin[1]) << 16) | (ord($bin[7]) << 8) | ord($bin[13]), 4);
        $hash .= $this->to64((ord($bin[2]) << 16) | (ord($bin[8]) << 8) | ord($bin[14]), 4);
        $hash .= $this->to64((ord($bin[3]) << 16) | (ord($bin[9]) << 8) | ord($bin[15]), 4);
        $hash .= $this->to64((ord($bin[4]) << 16) | (ord($bin[10]) << 8) | ord($bin[5]), 4);
        $hash .= $this->to64(ord($bin[11]), 2);

        return '$apr1$' . $salt . '$' . $hash;
    }

    private function to64(int $value, int $length): string
    {
        $map = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $out = '';
        while ($length-- > 0) {
            $out .= $map[$value & 0x3f];
            $value >>= 6;
        }
        return $out;
    }
}
