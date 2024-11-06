<?php
Class Autocargador
{

public static function autocargar()
{
    spl_autoload_register('self::autocarga');
}
private static function autocarga($name)
{
  //require_once './ruta
}
}

//BUCLE PARA CARGAR CLASES