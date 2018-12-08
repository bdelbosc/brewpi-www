<?php
/* Copyright 2012 BrewPi/Elco Jacobs.
 * This file is part of BrewPi.

 * BrewPi is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * BrewPi is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with BrewPi.  If not, see <http://www.gnu.org/licenses/>.
 */
require_once('socket_open.php');
require_once('config.php');

function readFromSocket($sock)
{
    $msg = socket_read($sock, 65536);
    if (!$msg) {
        $errorcode = socket_last_error();
        $errormsg = socket_strerror($errorcode);
        die("[\"Error\", \"$errorcode\", \"$errormsg\", \"\"]");
    }
    return $msg;
}

function writeToSocket($sock, $msg)
{
    $bytesWritten = socket_write($sock, $msg, 65536);
    if (!$bytesWritten) {
        $errorcode = socket_last_error();
        $errormsg = socket_strerror($errorcode);
        die("[\"Error\", \"$errorcode\", \"$errormsg\", \"\"]");
    }
}

header("Access-Control-Allow-Origin: *");
error_reporting(E_ALL ^ E_WARNING);
$sock = open_socket();
if ($sock !== false) {
    writeToSocket($sock, "lcd");
    $lcdText = readFromSocket($sock);
    echo str_replace("&deg", "°", $lcdText);
    socket_close($sock);
} else {
    die("[\"Error\", \"Cannot open socket to script\",\"\" , \"\"]");
}
