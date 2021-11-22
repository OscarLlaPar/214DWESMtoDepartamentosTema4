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
            * Ejercicio 09 - Mantenimiento de Departamentos - Editar departamento
            * @author Óscar Llamas Parra - oscar.llapar@educa.jcyl.es - https://github.com/OscarLlaPar
            * Última modificación: 18/11/2021
            */
            //Si se pulsa cancelar se vuelve a la otra página
            if(!empty($_REQUEST['cancelar'])){
                header('Location: MtoDepartamentos.php');
            }
            //Incluir configuración de la base de datos
            include "../config/confDB.php";
            //Incluir las funciones de validación
            include "../core/210322ValidacionFormularios.php";
            
            //Inicialización de variables
            $entradaOK = true; //Inicialización de la variable que nos indica que todo va bien
            //Inicialización del array que contiene los mensajes de error en caso de ser necesarios
            $aErrores = [
              'descripcion' => null,
              'volumenNegocio' => null
            ];
            //Inicialización del array que almacenará las respuestas cuando sean válidas
            $aRespuestas = [
              'descripcion' => null,
              'volumenNegocio' => null
            ];
            
            
            
           
                // Si ya se ha pulsado el boton "Enviar"
                if(!empty($_REQUEST['aceptar'])){
                    //Uso de las funciones de validación, que devuelven el mensaje de error cuando corresponde.
                    $aErrores['descripcion']= validacionFormularios::comprobarAlfanumerico($_REQUEST['descripcion'],50,3,1);
                    $aErrores['volumenNegocio']= validacionFormularios::comprobarFloat($_REQUEST['volumenNegocio'],PHP_FLOAT_MAX,0,1);
                    //acciones correspondientes en caso de que haya algún error
                    foreach($aErrores as $categoria => $error){
                        //condición de que hay un error
                        if(($error)!=null){
                            //limpieza del campo para cuando vuelva a aparecer el formulario
                            $_REQUEST[$categoria]="";
                            $entradaOK=false;
                        }
                    }
                    
                    $codDepartamentoParametro = $_REQUEST['codigo'];
                    
                    if($entradaOK){
                        try{
                
                            //Establecimiento de la conexión 
                            $miDB = new PDO(HOST, USER, PASSWORD);
                            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            //Preparación de la consulta
                            $oConsulta = $miDB->prepare(<<<QUERY
                                    UPDATE Departamento
                                    SET DescDepartamento = :descDepartamento, VolumenNegocio = :volumenNegocio
                                    WHERE CodDepartamento = :codDepartamento
                            QUERY);

                            //Asignación de las respuestas en los parámetros de las consultas preparadas
                            $aColumnas = [
                                ':codDepartamento' => $codDepartamentoParametro,
                                ':descDepartamento' => $_REQUEST['descripcion'],
                                ':volumenNegocio' => $_REQUEST['volumenNegocio']
                            ];
                            var_dump($aColumnas);
                            //Ejecución de la consulta de actualización
                            if($oConsulta->execute($aColumnas)){
                                header('Location: MtoDepartamentos.php');
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

                    }
                }
                else{
                    
                    $entradaOK=false;
                    $codDepartamentoParametro =$_REQUEST['codDepartamentoEnCurso'];
                    
                    
                    
                }
                try{
                
                //Establecimiento de la conexión 
                $miDB = new PDO(HOST, USER, PASSWORD);
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    //Preparación y ejecución de las consultas creadas en la condición
                    $oConsulta = $miDB->prepare(<<<QUERY
                                SELECT * FROM Departamento
                                WHERE CodDepartamento = :codDepartamento
                        QUERY);
                    
                    $aColumnas = [
                            ':codDepartamento' => $codDepartamentoParametro
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
            <h1>Editar departamento</h1>
        </header> 
        <div>
            <form action="vMtoDepartamentosEditar.php" method="post">
                <fieldset>
                    <table class="formularioVentana">
                        <tr>
                            <td><label for="codigo">Código:</label></td>
                            <td><input id="codigo" type="text" name="codigo" placeholder="(Vacío)" value="<?php echo $aValores['CodDepartamento'];?>" readonly="readonly"></td>
        
                        </tr>
                        <tr>
                            <td><label for="descripcion">Descripción:</label></td>
                            <td><input id="descripcion" type="text" name="descripcion" placeholder="(Vacío)" value="<?php echo $aValores['DescDepartamento'];?>" ></td>
        <?php
                echo (!is_null($aErrores['descripcion']))?"<td>$aErrores[descripcion]</td>":"";
        ?>   
                        </tr>
                        <tr>
                            <td><label for="fechaBaja">Fecha de baja:</label></td>
                            <td><input id="fechaBaja" type="text" name="fechaBaja" placeholder="(Vacío)" value="<?php echo $aValores['FechaBaja'];?>" readonly="readonly"></td>
                        </tr>
                        <tr>
                            <td><label for="volumenNegocio">Volumen de negocio:</label></td>
                            <td><input id="volumenNegocio" type="text" name="volumenNegocio" placeholder="(Vacío)" value="<?php echo $aValores['VolumenNegocio'];?>" ></td>
        <?php
                echo (!is_null($aErrores['volumenNegocio']))?"<td>$aErrores[volumenNegocio]</td>":"";
        ?>
                        </tr>
                        
                    </table>
                    <input id="aceptar"  class="boton" type="submit" name="aceptar" value="Aceptar">
                    <input id="cancelar"  class="boton" type="submit" name="cancelar" value="Cancelar">
                </fieldset>
            </form>
        </div>
    </body>
</html>
