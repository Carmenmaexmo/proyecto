<?php
Class Autocargador
{

public static function autocargar()
{
    spl_autoload_register([self::class, 'autocarga']); 
}
private static function autocarga($name)
{
  $carpetas =[
  './Clases/',
  './Repositorios/',
  './Vistas/',
  './Api/',
  './Helper/',
  ];

  foreach ($carpetas as $carpeta)
  {
    $archivo = $carpeta . $name . '.php';
    if (file_exists($archivo))
    {
      require_once $archivo;
      return;
    }
  }

}
}


