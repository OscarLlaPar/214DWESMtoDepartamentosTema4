<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>OLP-DWES - Mantenimiento de Departamentos Tema 4</title>
        <link href="../webroot/css/estilos.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <header>
            <h1>Mantenimiento de Departamentos Tema 4</h1>
            <a href="../index.php"><div class="cuadro" id="arriba">&#60;</div></a>
        </header>    
            <main>
                    
                    
        <?php
            /*
            * Ejercicio 09 - Aplicación de Mantenimiento de Departamentos
            * @author Óscar Llamas Parra - oscar.llapar@educa.jcyl.es - https://github.com/OscarLlaPar
            * Última modificación: 16/11/2021
            */
            //Incluir el archivo de configuración
            include '../config/confDB.php';
            //Incluir las funciones de validación
            include "../core/210322ValidacionFormularios.php";
            
            //Inicialización del array que contiene los mensajes de error en caso de ser necesarios
            $aErrores = [
                'busqueda' => null
            ];
            
            
            // Si ya se ha pulsado el boton "Enviar"
            if(!empty($_REQUEST['enviar'])){
                //Uso de las funciones de validación, que devuelven el mensaje de error cuando corresponde.
                $aErrores['busqueda']=validacionFormularios::comprobarAlfanumerico($_REQUEST['busqueda'],50,0,0);
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
        ?>
                <form name="ejercicio03" action="MtoDepartamentos.php" method="post">
                        <fieldset>
                            <fieldset>
                                <legend>Gestión</legend>
                                
                            </fieldset>
                            <fieldset>
                                <legend>Búsqueda</legend>
                                    <label for="busqueda">Buscar por descripción:</label>
                                    <input id="busqueda" type="text" name="busqueda" placeholder="Buscar..." value="<?php echo (isset($_REQUEST['busqueda']))?$_REQUEST['busqueda']:"";?>" >
        <?php
                echo (!is_null($aErrores['busqueda']))?"<span>$aErrores[busqueda]</span>":"";
        ?>           
                                       <input id="buscar" type="submit" value="Buscar" name="enviar"/>        
                            </fieldset>
                        <div class="contenedorTabla">
                                       <table>
                                           <tr>
                                               <th>Código</th>
                                               <th>Descripción</th>
                                               <th>Fecha de Baja</th>
                                               <th>Volumen de Negocio</th>
                                           </tr>
                <?php
            
                //Tratamiento de los datos
                try{
                    if(isset($_REQUEST['busqueda'])){
                        $busqueda=$_REQUEST['busqueda'];
                    }
                    else{
                        $busqueda=null;
                    }
                    //Establecimiento de la conexión 
                    $miDB = new PDO(HOST, USER, PASSWORD);
                    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    //Si se ha introduzido algo en el campo
                    if(!is_null($busqueda)){
                        $consultaSQLDeSeleccion = "select * from DB214DWESProyectoTema4.Departamento where DescDepartamento like '%".$busqueda."%'";
                    }
                    //Si se ha dejado el cmapo vacío
                    else{
                        //Mostrado de todas la filas
                        $consultaSQLDeSeleccion = "select * from DB214DWESProyectoTema4.Departamento";
                    }
                    //Preparación y ejecución de las consultas creadas en la condición
                    $resultadoConsulta = $miDB->prepare($consultaSQLDeSeleccion);
                    $resultadoConsulta->execute();
                    //Carga del registro en una variable
                    $registroObjeto = $resultadoConsulta->fetch(PDO::FETCH_OBJ);
                    
                    $aValores=[];
                    //Recorrido de todos los registros
                    while($registroObjeto!=null){
            ?>
                        <tr>
            <?php
                        //Recorrido del registro
                        foreach ($registroObjeto as $clave => $valor) {
                            echo "<td>$valor</td>";
                            $aValores[$clave]=$valor;
                            var_dump($clave);
                        }
            ?>
                            <td class="celdaIcono"><a href="vMtoDepartamentosEditar.php?codigo=<?php echo urlencode($aValores[CodDepartamento]);?>&descripcion=<?php echo urlencode($aValores[DescDepartamento]);?>&fechabaja=<?php echo urlencode($aValores[FechaBaja]);?>&volumennegocio=<?php echo urlencode($aValores[VolumenNegocio]);?>&"><img src="../webroot/img/editar.png"></a></td>
                            <td class="celdaIcono"><a href="vMtoDepartamentosEliminar.php?codigo=<?php echo urlencode($aValores[CodDepartamento]);?>"><img src="../webroot/img/eliminar.png"></a></td>
                            <td class="celdaIcono"><a href="#"><img src="../webroot/img/ver.png"></a></td>
                        </tr>
            <?php
                        //Carga de una nueva fila
                        $registroObjeto = $resultadoConsulta->fetch(PDO::FETCH_OBJ);
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
                            </table>
                        </div>
                    </fieldset>
                </form>
            </main>
        
        <footer>
            <p>
                Óscar Llamas Parra &nbsp;
                <a href="https://github.com/OscarLlaPar/" target="__blank"><img src="../webroot/img/github.png" alt="Github"></img></a>
            </p>
            <p>
                DAW 2
            </p>
            <p>
                IES Los Sauces, Benavente 2021-2022
            </p>
            <div class="cuadro" id="abajo"></div>
        </footer>
    </body>
</html>
