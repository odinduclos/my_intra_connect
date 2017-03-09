# INTRA CONNECT

## Structure du dépôt
Le dossier est composé de:
* classic-connect.php
* microsoft-connect.php
* CurlWrapper.php

# Classic connect
Le fichier `classic-connect.php` contient :
* Une fonction `classic_connect ($cookie, $login, $password)` qui créé un cookie d'authentification via l'API de connexion l'intranet 
    * `$cookie` est l'url où créer le cookie d'authetification
    * `$login` est le login fourni par l'utilisateur sous la forme login\_x
    * `$password` est le password UNIX fourne par l'utilisateur

__Attention__, la fonction `classic_connect` n'est à utiliser que pour les authentifications via le login. Elle est donc __deprecated__.

# Microsoft connect
Le fichier `microsoft-connect.php` contient :
* Une fonction `microsoft_connect ($cookie, $login, $password)` qui créé un cookie d'authentification via l'API de connexion de microsoft
    * `$cookie` est l'url où créer le cookie d'authetification
    * `$login` est le login fourni par l'utilisateur sous la forme login\_x
    * `$password` est le password UNIX fourne par l'utilisateur

__Attention__, la fonction `microsoft_connect` inclut le fichier `CurlWrapper.php`.

__Attention__, la fonction `microsoft_connect` n'utilise pas l'API microsoft mais scrappe son comportement.

# Le curl wrapper
Le fichier `CurlWrapper.php` contient :
* Le constructeur `__construct ($_cookie)`
    * `$cookie` est l'URL du cookie à utiliser pour les transactions
* Une fonction `get ($url, $cookie = false)` qui opère une requète GET
    * `$url` est l'url de l'API à GET du type `https://intra.epitech.eu/admin/promo/list?school=webacademie&scolaryear=2015&course=webacademie&semester=W1&location=FR/LYN&format=json`
    * `$cookie` est boolean optionnel forçant le wrapper à utiliser le cookie enregistré
* Une fonction `post ($url, $params)` qui opère une requète POST
    * `$url` est l'url de l'API à GET du type `https://intra.epitech.eu/admin/promo/list`
    * `$params` permet de passer des paramètres à la requête sous la forme d'un tableau associatif $key => $value
* Une fonction `toString` qui retourne le résultat de la dernière requête
* Une fonction `getAllResponseHeaders ()` qui retourne le header de la dernière requête
* Une fonction `getHttpResponseCode ()` qui retourne le code http de la dernière requête
