<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\open_api;

class DockingForm
{
    const RSA_PUBLIC_KEY = "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA2ZItE+82IiMY8Q6QpJsx
f4vTTqi0DMkvSB1oXqep/11hKfekLL9F2NQGaJyxq9cXynDelqca3prYqjMyhgfa
a9lThgnUoDFAR+JWQS61Jj3K1cVTzNvraN7D0I0W0sWfKo29BMGyZMSMJ3ehRqo0
pth5gRg9sQqifeOQeI/r2Ml1TWUJmV3CZW+qkAHOKoSSL9mcavN4LEQxiGlvFx7I
UnP8n9N76X+sTjkXhs13GJamxSxPgMSKKHdffiQJmJJrg3kGSoqshD1KyqnTpZix
dz/aTzPNUFs/odk04kP+xbJmATvRYGa5g11B95esekCazZafxVsiH1DAdo6y9c8p
zwIDAQAB
-----END PUBLIC KEY-----
";  // 公钥

    const RSA_PRIVATE_KEY = "-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDZki0T7zYiIxjx
DpCkmzF/i9NOqLQMyS9IHWhep6n/XWEp96Qsv0XY1AZonLGr1xfKcN6Wpxremtiq
MzKGB9pr2VOGCdSgMUBH4lZBLrUmPcrVxVPM2+to3sPQjRbSxZ8qjb0EwbJkxIwn
d6FGqjSm2HmBGD2xCqJ945B4j+vYyXVNZQmZXcJlb6qQAc4qhJIv2Zxq83gsRDGI
aW8XHshSc/yf03vpf6xOOReGzXcYlqbFLE+AxIood19+JAmYkmuDeQZKiqyEPUrK
qdOlmLF3P9pPM81QWz+h2TTiQ/7FsmYBO9FgZrmDXUH3l6x6QJrNlp/FWyIfUMB2
jrL1zynPAgMBAAECggEBAM95tcLsupKTJZW6bfSKigk3Lao09n263HvIj160REhg
o+eBX+3L+K9sgTABPOzBkM5cE+dWMylUQIkNiYCGvKNb+2o2tayNSt9B1z8Ra22s
P2Dba65PiY3X4KNf6APWsJHD2BxRGe0+AOkiC4l3hf4VMMkKdMn/ejTSbVKK7D0t
HZsE771IKXTfSqsZAD3mwRD7aoWCgEPfUDBRNUtWhlz+UOEb7yeKFy0oNc6CRPBE
USmL6gJDBB2QBBmH3rhrlmUSNC0nXWIBUOrKMdvVF4ubZiqMi+AcpqIsPlb7BpS7
x8IlG5+BzHrUNyC115R4oitk+DSkqNivOW0jhUzR6QECgYEA8MWcFDnnETBQyGGC
zMhNNwceXAJkNl1ekInGsOWzhR4KLmCe8oILpWg4dMo5fYG22yl2X7E4MYq1hTiP
01/Ck8RZqwcNlWUMFzRD/Mv7DBW4nVjScey00hcOKowlBr1lGaF92reDmp63L32Y
4gYzURZCRYYnmC/5FZFA1S+Km6MCgYEA51TqVwTKCCA+Nq+YY3gT9/cQ/yBpe3Ub
0GG6DWE3BR+u9ZLIMp1a3WG+sKtO0ODljplc+/Fx13/rZfbhtklJKZqEDcw5R1Iw
E0rI0ZUIRB3QLVbo7u8VsAE2ZKM5MefIylnKbOAVJO/Jpwk2iBWbjGtnm0n2dzOJ
Z2d6wFh1W+UCgYBxINhiJIaua945GKAFqkOljGG2Z6VAagSJs0K5UWRVMrUj72Af
n/zq3hpQvcffcbhCJ6wn8DPwCzWY/+eMMJ1TItni4zB9tnnE2VjsicdOeVJCFD7l
KowXfp+4XBr1nL3JGjjxMHLUjqiR1tijsrgf7G59DjjCaCAIAzTMNkdRMwKBgQCT
qHgMI1px5WLQtTSoCTV1yZZnwuCRlSaz1C1V7P+ZnaenyJFQ/W/Kb2Gwkygz29mr
M+lOR8dKrrQq2XMpT44LqD0yMXE+PQ/CwLF+VYp5MC55Qkhceu908NEvW6BeYnyf
7MBwznewo/4rSI4uPtySvNvdG1DetV63Si3oKI6AoQKBgAGSq/c1x45PmQ+mEXN7
6SlLDNRfJ5gYWXsReaq5vNfEtXazmz+b6TiBt2LN3ATR+LOiGIphXPya7iUe4HLD
ktqeChUQWcDMrsxU69bgy0w5zdps5Rz8A5FP9A+WoNlrYirIgtijPA4W7em0cvJm
0Pcz34WRjV7MJcbV7YZYqJ2P
-----END PRIVATE KEY-----
";  // 私钥

    /**
     * 生成签名
     * @return string
     */
    public static function getSign($params){
        unset($params['sign']);
        ksort($params);
        reset($params);
        $params = is_array($params) ? json_encode($params) : $params;
        $priKeyId = openssl_pkey_get_private(self::RSA_PRIVATE_KEY);
        $signature = '';
        openssl_sign($params, $signature, $priKeyId);
        openssl_free_key($priKeyId);
        return base64_encode($signature);
    }

    /**
     * 校验签名
     * @return bool
     */
    public static function checkSign($params, $sign){
        unset($params['sign']);
        ksort($params);
        reset($params);
        $params = is_array($params) ? json_encode($params) : $params;
        $pkey = openssl_pkey_get_public(self::RSA_PUBLIC_KEY);
        $verify = openssl_verify($params, base64_decode($sign),$pkey);
        openssl_free_key($pkey);
        if ($verify){
            return true;
        }else{
            return false;
        }
    }

}