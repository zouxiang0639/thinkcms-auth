<?php
/**
 * @param array  $path
 * @return bool
 */
function checkPath($path){
    $a =  \thinkcms\auth\Auth::checkPath($path);
    return $a;
}
?>