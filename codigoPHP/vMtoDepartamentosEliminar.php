<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>OLP-DWES - Editar departamento</title>
        <link href="../webroot/css/estilos.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div>
            <form action="MtoDepartamentos.php" method="post">
                <fieldset>
                    <table class="formularioVentana">
                        <tr>
                            <td><label for="codigo">Código:</label></td>
                            <td><input id="codigo" type="text" name="codigo" placeholder="(Vacío)" value="<?php echo (isset($_REQUEST['codigo']))?$_REQUEST['codigo']:"";?>" disabled></td>
                        </tr>
                        <tr>
                            <td><label for="descripcion">Descripción:</label></td>
                            <td><input id="descripcion" type="text" name="descripcion" placeholder="(Vacío)" value="<?php echo (isset($_REQUEST['descripcion']))?$_REQUEST['descripcion']:"";?>" disabled></td>
                        </tr>
                        <tr>
                            <td><label for="fechaBaja">Fecha de baja:</label></td>
                            <td><input id="fechaBaja" type="text" name="fechaBaja" placeholder="(Vacío)" value="<?php echo (isset($_REQUEST['fechabaja']))?$_REQUEST['fechabaja']:"";?>" disabled></td>
                        </tr>
                        <tr>
                            <td><label for="volumenNegocio">Volumen de negocio:</label></td>
                            <td><input id="volumenNegocio" type="text" name="volumenNegocio" placeholder="(Vacío)" value="<?php echo (isset($_REQUEST['volumennegocio']))?$_REQUEST['volumennegocio']:"";?>" disabled></td>
                        </tr>
                        
                    </table>
                    <input id="aceptar" type="button" name="eliminar" value="Aceptar">
                        <a id="cancelar" href="MtoDepartamentos.php">Cancelar</a>
                </fieldset>
            </form>
        </div>
    </body>
</html>
