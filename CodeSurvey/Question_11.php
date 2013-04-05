<?php
/*
    Question 10:
    What are the best methods to secure a password in a database, and ensure the
    password value cannot be seen by anyone, including the developing team? 
    
    ----------------------------------------------------------------------------
    What ends up stored in the database should be the salt-encrypted value of 
    the password.
    
    In this example, the value returned by cryptPass() is the SHA-512 hash of 
    the password.  (The salt is generated using the md5sum of the current time.)
    That’s the value that’s inserted into the database.
    To verify a session or login, you would query the database for that hash, 
    and then check it using crypt($userinput,$hashFromDatabase).
    
    The following example illustrates this (requires SHA-512).
*/

$linebreak = PHP_SAPI=='cli'?"\n":"<br/>";  
function cryptPass( $plaintextPassword ) {
    $salt = '$6$rounds=5000$'.substr(base64_encode(md5(time(),false)),0,16)."$";
    return crypt( $plaintextPassword, $salt );
}

function verifyPass($plaintextPassword,$passwordHash) {
    return crypt($plaintextPassword,$passwordHash)==$passwordHash;
}

$password = "Some User Password with high entropy, like \"Correct, a Horse Battery Staple.\"";

echo "Password to encrypt:".$linebreak."\$password= ".$password.$linebreak.$linebreak;

$encrypted = cryptPass($password);

echo "Encrypted password to insert into a database:".$linebreak.
    "\$encrypted= ".$encrypted.$linebreak.$linebreak;

echo "Result of verifyPass(\$password, \$encrypted):".$linebreak;
echo (verifyPass($password,$encrypted)?"HASHES MATCH":"hashes do not match").$linebreak;