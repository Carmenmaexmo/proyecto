<?php
require_once './cargadores/Autocargador.php';

class Principal
{
    public static function main()
    {
        Autocargador::autocargar();

        $route= $_GET['route'] ?? null;
        switch ($route) {
            case 'ingredientes':
                require_once './Api/ApiIngredientes.php';
                break;
            case 'kebab':
                require_once './Api/ApiKebab.php';
                break;
            case 'lineaDePedido':
                require_once './Api/ApiLineaDePedido.php';
                break;
            case 'pedidos':
                require_once './Api/ApiPedidos.php';
                break;
            default:
                require_once './Vistas/Principal/layout.php';
        }
        
    }
}
Principal::main();
?>
