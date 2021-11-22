<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>OLP-DWES - Añadir departamento</title>
        <link href="../webroot/css/estilos.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php
            /*
            * Ejercicio 09 - Mantenimiento de Departamentos - Añadir departamento
            * @author Óscar Llamas Parra - oscar.llapar@educa.jcyl.es - https://github.com/OscarLlaPar
            * Última modificación: 09/11/2021
            */
            //Incluir el archivo de configuración
            if(!empty($_REQUEST['cancelar'])){
                header('Location: MtoDepartamentos.php');
            }
            include '../config/confDB.php';
            //Incluir las funciones de validación
            include "../core/210322ValidacionFormularios.php";
            
            //Inicialización de variables
            $entradaOK = true; //Inicialización de la variable que nos indica que todo va bien
            //Inicialización del array que contiene los mensajes de error en caso de ser necesarios
            $aErrores = [
              'codigo' => null,
              'descripcion' => null,
              'volumenNegocio' => null
            ];
            //Inicialización del array que almacenará las respuestas cuando sean válidas
            $aRespuestas = [
              'codigo' => null,
              'descripcion' => null,
              'volumenNegocio' => null
            ];
            // Si ya se ha pulsado el boton "Enviar"
            if(!empty($_REQUEST['aceptar'])){
                //Uso de las funciones de validación, que devuelven el mensaje de error cuando corresponde.
                $aErrores['codigo']= validacionFormularios::comprobarAlfabetico($_REQUEST['codigo'],3,3,1);
                //Validación de clave primaria (solo en caso de que la función la confirme como válida)
                if($aErrores['codigo'] == null){
                    if(strtoupper($_REQUEST['codigo'])!=$_REQUEST['codigo']){
                        $aErrores['codigo']= "El código debe estar en mayúsculas."; 
                    }
                    else{
                        try{
                            //Establecimiento de la conexión
                            $miDB = new PDO(HOST, USER, PASSWORD);

                            $miDB -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            //Elaboración y preparación de la consulta
                            $consulta = 'SELECT * FROM Departamento WHERE CodDepartamento = '.$_REQUEST['codigo']."'";
                            $resultadoConsulta = $miDB->prepare($consulta);
                            //Ejecución de la consulta
                            $resultadoConsulta->execute();
                            //Carga de una fila del resultado en una variable
                            $registroConsulta = $resultadoConsulta->fetchObject();
                            if(!is_null($registroConsulta)){ 
                                $aErrores['codigo']= "Código duplicado."; 
                            }
                        //Muestra de posibles errores    
                        }catch(PDOException $miExceptionPDO){
                            echo "Error: ".$miExceptionPDO->getMessage();
                             echo "<br>";
                            echo "Código de error: ".$miExceptionPDO->getCode();
                        }finally{
                            unset($miDB);
                        }
                    }
                }
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
            }
            //Si no se ha pulsado el botón "Enviar" (es la primera vez)
            else{
                $entradaOK=false;
            }
            //Si todo está bien
            if($entradaOK){
                //Tratamiento de los datos
                //Almacenamiento de las respuestas válidas en el array de respuestas
                $aRespuestas['codigo'] = $_REQUEST['codigo'];
                $aRespuestas['descripcion'] = $_REQUEST['descripcion'];
                $aRespuestas['volumenNegocio'] = $_REQUEST['volumenNegocio'];
                
                
                try{
                    //Establecimiento de la conexión 
                    $miDB = new PDO(HOST, USER, PASSWORD);
                    
                    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    //Preparación de la consulta
                    $oConsulta = $miDB->prepare(<<<QUERY
                            INSERT INTO Departamento
                            VALUES (:codDepartamento, :descDepartamento, null, :volumenNegocio)
                    QUERY);
                    //Asignación de las respuestas en los parámetros de las consultas preparadas
                    $aColumnas = [
                        ':codDepartamento' => $aRespuestas['codigo'],
                        ':descDepartamento' => $aRespuestas['descripcion'],
                        ':volumenNegocio' => $aRespuestas['volumenNegocio']
                    ];
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
              ?>
        <header>
            <h1>Añadir departamento</h1>
        </header> 
        <div>
            <form action="DepartamentosNuevo.php" method="post">
                <fieldset>
                    <table class="formularioVentana">
                        <tr>
                            <td><label for="codigo">Código:</label></td>
                            <td><input id="codigo" type="text" name="codigo" placeholder="Tres letras mayúsculas (AAA)" value="<?php echo (isset($_REQUEST['codigo']))?$_REQUEST['codigo']:"";?>"></td>
        <?php
                echo (!is_null($aErrores['codigo']))?"<td>$aErrores[codigo]</td>":"";
        ?>           
                        </tr>
                        <tr>
                            <td><label for="descripcion">Descripción:</label></td>
                            <td><input id="descripcion" type="text" name="descripcion" placeholder="Descripción del departamento" value="<?php echo (isset($_REQUEST['descripcion']))?$_REQUEST['descripcion']:"";?>" ></td>
        <?php
                echo (!is_null($aErrores['descripcion']))?"<td>$aErrores[descripcion]</td>":"";
        ?>            
                        </tr>
                        <tr>
                            <td><label for="volumenNegocio">Volumen de negocio:</label></td>
                            <td><input id="volumenNegocio" type="text" name="volumenNegocio" placeholder="Volumen del negocio (€)" value="<?php echo (isset($_REQUEST['volumenNegocio']))?$_REQUEST['volumenNegocio']:"";?>" ></td>
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
