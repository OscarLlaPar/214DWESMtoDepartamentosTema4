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
            include '../config/confDBPDO.php';
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
            if(!empty($_REQUEST['nuevo'])){
                //Uso de las funciones de validación, que devuelven el mensaje de error cuando corresponde.
                $aErrores['codigo']= validacionFormularios::comprobarAlfabetico($_REQUEST['codigo'],3,3,1);
                //Validación de clave primaria (solo en caso de que la función la confirme como válida)
                if($aErrores['codigo'] == null){
                    try{
                        //Establecimiento de la conexión
                        $miDB = new PDO(HOST, USER, PASSWORD);
                        
                        $miDB -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        //Elaboración y preparación de la consulta
                        $consulta = ('SELECT * FROM Departamento');
                        $resultadoConsulta = $miDB->prepare($consulta);
                        //Ejecución de la consulta
                        $resultadoConsulta->execute();
                        //Carga de una fila del resultado en una variable
                        $registroConsulta = $resultadoConsulta->fetchObject();
                        //Recorrido de todos los registros (filas)
                        while($registroConsulta){ 
                            //Si se detecta una clave que coincida con la respuesta, se crea un mensaje de error
                            if($registroConsulta->CodDepartamento == strtoupper($_REQUEST['codigo'])){ 
                                $aErrores['codigo']= "Código duplicado."; 
                            }
                            //Carga de nueva fila
                            $registroConsulta = $resultadoConsulta->fetchObject();  
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
            if(!$entradaOK){
              ?>
        <div>
            <form action="MtoDepartamentos.php" method="post">
                <fieldset>
                    <table class="formularioVentana">
                        <tr>
                            <td><label for="codigo">Código:</label></td>
                            <td><input id="codigo" type="text" name="codigo" placeholder="(Vacío)" value="<?php echo $aValores['CodDepartamento'];?>" disabled></td>
        <?php
                echo (!is_null($aErrores['codigo']))?"<td>$aErrores[codigo]</td>":"";
        ?>           
                        </tr>
                        <tr>
                            <td><label for="descripcion">Descripción:</label></td>
                            <td><input id="descripcion" type="text" name="descripcion" placeholder="(Vacío)" value="<?php echo $aValores['DescDepartamento'];?>" ></td>
        <?php
                echo (!is_null($aErrores['descripcion']))?"<span>$aErrores[descripcion]</span>":"";
        ?>            
                        </tr>
                        <tr>
                            <td><label for="volumenNegocio">Volumen de negocio:</label></td>
                            <td><input id="volumenNegocio" type="text" name="volumenNegocio" placeholder="(Vacío)" value="<?php echo $aValores['VolumenNegocio'];?>" ></td>
        <?php
                echo (!is_null($aErrores['volumenNegocio']))?"<span >$aErrores[volumenNegocio]</span>":"";
        ?>
                        </tr>
                        
                    </table>
                    <input id="aceptar" type="submit" name="nuevo" value="Aceptar">
                        <a id="cancelar" href="MtoDepartamentos.php">Cancelar</a>
                </fieldset>
            </form>
        </div>
        <?php    
            }
            
        ?>
    </body>
</html>
