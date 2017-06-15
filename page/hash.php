<?php

$string = '8753754';
for ($x=0;$x<10;$x++)
{
 $hash = password_hash($string,PASSWORD_DEFAULT);
    echo $hash . '<br>';
    if (password_verify($string,$hash))
    {
        echo 'Hasło jest poprawne <br>';
    }
echo '<br>';
echo '<br>';
}




?>