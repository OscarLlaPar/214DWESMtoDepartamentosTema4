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
        <?php
            /*
            * Ejercicio 09 - Mantenimiento de Departamentos - Eliminar departamento
            * @author Óscar Llamas Parra - oscar.llapar@educa.jcyl.es - https://github.com/OscarLlaPar
            * Última modificación: 18/11/2021
            */
            //Si se pulsa cancelar se vuelve a la otra página
            if(!empty($_REQUEST['cancelar'])){
                header('Location: MtoDepartamentos.php');
            }
            //Incluir configuración de la base de datos
            include "../config/confDB.php";
            try{
                //Establecimiento de la conexión 
                $miDB = new PDO(HOST, USER, PASSWORD);
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
               //Preparación y ejecución de las consultas creadas en la condición
                if(!empty($_REQUEST['aceptar'])){
                    $oConsulta = $miDB->prepare(<<<QUERY
                                DELETE FROM Departamento
                                WHERE CodDepartamento = :codDepartamento
                        QUERY);
                        //Asignación de las respuestas en los parámetros de las consultas preparadas
                        $aColumnas = [
                            ':codDepartamento' => $_REQUEST['codigo']
                        ];
                        //Ejecución de la consulta de actualización
                        if($oConsulta->execute($aColumnas)){
                            header('Location: MtoDepartamentos.php');
                        }
                        
                }
                $oConsulta = $miDB->prepare(<<<QUERY
                            SELECT * FROM Departamento
                            WHERE CodDepartamento = :codDepartamento
                    QUERY);
                $aColumnas = [
                        ':codDepartamento' => $_REQUEST['codDepartamentoEnCurso']
                ];
                $oConsulta->execute($aColumnas);
                //Carga del registro en una variable
                $registroObjeto = $oConsulta->fetch(PDO::FETCH_OBJ);

                $aValores=[];
                //Recorrido del registro
                foreach ($registroObjeto as $clave => $valor) {
                    $aValores[$clave]=$valor;
                }
            }
            //Gestión de errores relacionados con la base de datos
            catch(PDOException $miExceptionPDO){
                echo "Error: ".$miExceptionPDO->getMessage();
                echo "<br>";
                echo "Código de error: ".$miExceptionPDO->getCode();
            }
            finally{
             //Cerrar la conexión
             unset($miDB);
            }
        ?>
        <header>
            <h1>Eliminar departamento</h1>
        </header> 
        <div>
            <form action="DepartamentosEliminar.php" method="post">
                <fieldset>
                    <table class="formularioVentana">
                        <tr>
                            <td><label for="codigo">Código:</label></td>
                            <td><input id="codigo" type="text" name="codigo" placeholder="(Vacío)" value="<?php echo $aValores['CodDepartamento'];?>" readonly="readonly"></td>
                        </tr>
                        <tr>
                            <td><label for="descripcion">Descripción:</label></td>
                            <td><input id="descripcion" type="text" name="descripcion" placeholder="(Vacío)" value="<?php echo $aValores['DescDepartamento'];?>" readonly="readonly"></td>
                        </tr>
                        <tr>
                            <td><label for="fechaBaja">Fecha de baja:</label></td>
                            <td><input id="fechaBaja" type="text" name="fechaBaja" placeholder="(Vacío)" value="<?php echo $aValores['FechaBaja'];?>" readonly="readonly"></td>
                        </tr>
                        <tr>
                            <td><label for="volumenNegocio">Volumen de negocio:</label></td>
                            <td><input id="volumenNegocio" type="text" name="volumenNegocio" placeholder="(Vacío)" value="<?php echo $aValores['VolumenNegocio'];?>" readonly="readonly"></td>
                        </tr>
                        
                    </table>
                    <p>¿Estás seguro?</p>
                    <input id="aceptar"  class="boton" type="submit" name="aceptar" value="Aceptar">
                    <input id="cancelar"  class="boton" type="submit" name="cancelar" value="Cancelar">
                </fieldset>
            </form>
        </div>
    </body>
</html>
