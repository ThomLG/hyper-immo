<?php

namespace App\Service;

use DateTimeImmutable;

class JWTService
{

    // On génère le token

    public function generate(array $header, array $payload, string $secret, int $validity = 10800 /* 3 heures*/): string
    {
        if ($validity > 0) {
            $now = new DateTimeImmutable(); // je prends l'heure actuelle
            $exp = $now->getTimestamp() + $validity; // j'obtiens la date d'expiration dans 3 heures près now.

            $payload['iat'] = $now->getTimestamp(); // iat = issued at 
            $payload['exp'] = $exp;
        }

        // On encode en base 64 car le JWT sont encodés de la sorte
        $base64Header = base64_encode(json_encode($header));
        $base64Payload = base64_encode(json_encode($payload));

        // On nettoie les valeurs encodées (on retire les +, / et =)
        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header);
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);

        // On génère la signature.
        $secret = base64_encode($secret);
        
        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true);

        $base64Signature = base64_encode($signature);

        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64Signature);

        // On crée le token

        $jwt = $base64Header . '.' . $base64Payload . '.' . $base64Signature;

        return $jwt;
    }

    // On va vérifier que le token est valide
    
    // A t-il la bonne forme ?
    public function isValid(string $token): bool
    {
        return preg_match('/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/', $token) === 1; // Vérifie sur le token correspond
    }

    // On récupère le payload
    public function getPayload(string $token): array
    {
        // on démonte le token
        $array = explode('.', $token);

        // On divise le payload
        $payload = json_decode(base64_decode($array[1]), true); // [1] car le payload est la 2e colonne de l'array $token

        return $payload;
    }

    // On récupère le header
    public function getHeader(string $token): array
    {
        // on démonte le token
        $array = explode('.', $token);

        // On décode le header
        $header = json_decode(base64_decode($array[0]), true); // [0] car le header est la 1ere colonne de l'array $token

        return $header;
    }

    // Le token a t-il expiré ?

    public function isExpired(string $token): bool
    {
        $payload = $this->getPayload($token);

        $now = new DateTimeImmutable(); // l'heure actuelle

        return $payload['exp'] < $now->getTimestamp(); // si la date actuelle est supérieur à la date d'expiration, le token a expiré
    }

    // On vérifie la signature du token

    public function check(string $token, string $secret)
    {
        // on récupère le header et le payload

        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);

        // On régénère un token

        $verifToken = $this->generate($header, $payload, $secret, 0);

        return $token === $verifToken;
    }
}
