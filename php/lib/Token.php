<?php 

function getTokenTime(){
	return sha1(time());
}

function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 0) return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
}

function getToken($length){
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    for($i=0;$i<$length;$i++){
        $token .= $codeAlphabet[crypto_rand_secure(0,strlen($codeAlphabet))];
    }
    return $token;
}



function encrypt($pwd="",$data,$case=""){	 
		$data=$case == 'de' ? $data = urldecode($data) : $data;
		$v[] = "";$b[] = "";$s = "";$t = ""; $k = "";$f = "";$z = "";$l = 0;$x = 0;$a = 0;$j = 0;$l = strlen($pwd);$d=strlen($data);
		for ($i = 0; $i <= 255; $i++){$v[$i] = ord(substr($pwd, ($i % $l), 1)); $b[$i] = $i;}
		for ($i = 0; $i <= 255; $i++){$x = ($x + $b[$i] + $v[$i]) % 256; $s = $b[$i]; $b[$i] = $b[$x]; $b[$x] = $s; }
		for ($i = 0; $i < $d; $i++)  {$a = ($a + 1) % 256; $j = ($j + $b[$a]) % 256;$t = $b[$a]; $b[$a] = $b[$j];$b[$j] = $t;$k = $b[(($b[$a] + $b[$j]) % 256)]; $f = ord(substr($data, $i, 1)) ^ $k;$z .= chr($f);}
		$z=$case =="de" ? urldecode(urlencode($z)): $z; 
		return $z;
	}
	
function decrypt($pwd,$data){
		return encrypt($pwd,urlencode($data),"de");
	} 	

?>