<?php
session_start();
error_reporting(0);
include('includes/config.php');
echo '<meta charset="UTF-8">';
if(strlen($_SESSION['emplogin'])<>'')
    {   
header('location:index.php');
}
else{
if(isset($_POST['apply']))
{
$empid=$_SESSION['eid'];
$occupation=$_GET['job'];
$_SESSION["job"] = $_GET['job'];
$leavetype= $_POST['leavetype'];
$fromdate= Date('Y-m-d', strtotime($_POST['fromdate']));
$todate= Date('Y-m-d', strtotime($_POST['todate']));
$description=$_POST['description']; 
$postingDate=date('Y-m-d G:i:s ', strtotime("now")); 
$status=0;
$isread=0;


// EXTRAE EL NOMBRE DEL DIA
$daysfrom=date('l', strtotime($fromdate));
$daysto=date('l', strtotime($todate));

if($fromdate > $todate){
    $error= "El rango de fecha es incorrecto";
}
else
{
    //SI DIA ES DIFERENTE A SABADO Y DOMINGO
    if ($daysfrom <> 'Saturday' AND $daysfrom <> 'Sunday' and $daysto <> 'Saturday' and $daysto <> 'Sunday')
    {
        //RECORRE EL RANGO DE FECHAS
        for ( $i = $fromdate; $i <= $todate; $i ) 
        {
            $i = date('Y-m-d', strtotime($i));
            $nombre = date('l',strtotime($i));
            //SI EL DIA ES SABADO O DOMINGO LOS SALTA Y LE SUMA DOS DIAS
            if ( $nombre == 'Saturday' OR $nombre == 'Sunday')
            {
                $i = date('Y-m-d', strtotime($i));
                $i = date('Y-m-d', strtotime($i.'+ 2 days')); 
                // echo $i.",";
            }
            else 
            {
                // echo $i.","; 
                $sql= "INSERT INTO days (Day,Empid,Rol, LeaveType, postingDate)
                VALUES ('$i','$empid','$occupation', '$leavetype', '$postingDate')"; 
                $i = date('Y-m-d', strtotime($i.'+ 1 days')); 
                mysqli_query($conn, $sql);
               

                //ELIMINAR DIAS ASUETOS
                $asuetos= "UPDATE days SET status=3 WHERE EXISTS(SELECT * FROM asuetos WHERE dias=days.day and LeaveType <>'Asueto')";
                mysqli_query($conn, $asuetos);

                $eliminarasuetos= "DELETE FROM days WHERE status=3";
                mysqli_query($conn, $eliminarasuetos);


            }
        }


        $dias="SELECT count(*) as agendados FROM days WHERE (Day >= $fromdate OR Day<= $todate) and  Empid = $empid and Status<>2";
        $agendados=mysqli_query($conn, $dias);

        while($row = mysqli_fetch_array($agendados)) {
            /*Imprimir campo por nombre*/
            $dias_agendados=$row['agendados'];
            // echo $dias_agendados;
        }

        $vacaciones="SELECT Pending_days FROM tblemployees WHERE EmpId=$empid";
        $totalVacaciones=mysqli_query($conn, $vacaciones);

        while($row = mysqli_fetch_array($totalVacaciones)) {
            /*Imprimir campo por nombre*/
            $totalVacaciones=$row['Pending_days'];
            // echo $totalVacaciones;
        }

        if ($dias_agendados <= $totalVacaciones)
        {
            // VALIDACION PARA SABER SI LOS DIAS SELECCIONADOS POR EL PROGRAMADOR ESTAN OCUPADOS O DISPONIBLES
            if ($occupation=='Programador')
            {
                $validacion="SELECT Day, Rol, Status, count(*) AS contador FROM days WHERE Day BETWEEN '$fromdate' AND '$todate' AND (Rol='Programador' AND Status=1)
                group by 1,2,3 having count(*) >= 2;";
                $resultado=mysqli_query($conn,$validacion) or die ('Error en el query favor de contactarse con isaac dominguez');
                //Valida que la consulta esté bien hecha
                if( $resultado )
                {
                //Ahora valida que la consuta haya traido registros
                if( mysqli_num_rows( $resultado ) > 0){
                    //Mientras mysqli_fetch_array traiga algo, lo agregamos a una variable temporal
                    while($fila = mysqli_fetch_array( $resultado ) )
                    { 
                    //Ahora $fila tiene la primera fila de la consulta, pongamos que tienes
                    //un campo en tu DB llamado contador, así accederías
                    if ($fila['contador'] >= 2)
                    {
                        $eliminardias="DELETE FROM days WHERE  (Day >= '$fromdate' OR Day <= '$todate') and empid='$empid' 
                        and  Status=0 and postingDate='$postingDate'";
                        mysqli_query($conn, $eliminardias);
                        $error = "Las fechas solicitadas se encuentra ocupadas";
                        // echo $fila['contador'];
                    }
                    }
                }
                else 
                    {
                        $insert="INSERT INTO tblleaves(LeaveType,FromDate,ToDate,Description,PostingDate,Status,IsRead,empid, Occupation) 
                        VALUES('$leavetype','$fromdate','$todate','$description','$postingDate', $status, $isread, $empid, '$occupation')";
                        if(mysqli_query($conn, $insert))
                        {
                            $msg="Ausencia solicitada exitósamente";
                        }
                        else
                        {
                            $error="Algo salió mal. Inténtalo de nuevo";
                        } 

                    }
                }
            }

            // VALIDACION PARA SABER SI LOS DIAS SELECCIONADOS POR EL ARQUITECTO ESTAN OCUPADOS O DISPONIBLES
            if ($occupation=='Arquitecto')
            {
                $validacion="SELECT Day, Rol, Status, count(*) AS contador FROM days WHERE Day BETWEEN '$fromdate' AND '$todate' AND ( Rol='Arquitecto' AND Status=1)
                group by 1,2,3 having count(*) >= 1;";
                $resultado=mysqli_query($conn,$validacion) or die ('Error en el query favor de contactarse con isaac dominguez');
                //Valida que la consulta esté bien hecha
                if($resultado)
                {
                //Ahora valida que la consuta haya traido registros
                if( mysqli_num_rows( $resultado ) > 0){
                    //Mientras mysqli_fetch_array traiga algo, lo agregamos a una variable temporal
                    while($fila = mysqli_fetch_array( $resultado ) )
                    { 
                    //Ahora $fila tiene la primera fila de la consulta, pongamos que tienes
                    //un campo en tu DB llamado contador, así accederías
                        if ($fila['contador'] >= 1)
                        {
                            $eliminardias="DELETE FROM days WHERE  (Day >= '$fromdate' OR Day <= '$todate') and empid='$empid' 
                            and  Status=0 and postingDate='$postingDate'";
                            mysqli_query($conn, $eliminardias);
                            $error = "Las fechas solicitadas se encuentra ocupadas";
                            // echo $fila['contador'];
                        }
                    }
                }
                else 
                    {
                        $insert="INSERT INTO tblleaves(LeaveType,FromDate,ToDate,Description,PostingDate,Status,IsRead,empid, Occupation) 
                        VALUES('$leavetype','$fromdate','$todate','$description','$postingDate', $status, $isread, $empid, '$occupation')";
                        if(mysqli_query($conn, $insert))
                        {
                            $msg="Ausencia solicitada exitósamente";
                        }
                        else
                        {
                            $error="Algo salió mal. Inténtalo de nuevo";
                        } 
                    }
                }
            }
        }
        else 
        {
            $eliminardias="DELETE FROM days WHERE  (Day >= '$fromdate' OR Day <= '$todate') and empid='$empid' 
            and  Status=0 and postingDate='$postingDate'";
            mysqli_query($conn, $eliminardias);
            $error = "No cuenta con días suficientes"; 

        }
    }
    else
        {
            $error = "Seleccione una fecha de inicio/fin distinta a sábado o domingo";
        }
    }

}

    ?>

<!DOCTYPE html>
<html lang="es">
    <head>
        
        <!-- Title -->
        <title>Empleado | Solicitar Permiso de Ausencia</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        
        
        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet"> 
        <link href="assets/css/alpha.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/custom.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="assets/css/jquery-ui.css" type="text/css"/>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">

  <style>
        span.ui-state-default{
            background-color: red !important;
            color: teal;
            font-weight:900!important;
        }

        .errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #dd3d36;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #5cb85c;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
        </style>
 


    </head>
    <body>
  <?php include('includes/header.php');?>
            
       <?php include('includes/sidebar.php');?>
   <main class="mn-inner">
                <div class="row">
                    <div class="col s12">
                        <div class="page-title">Solicitar Permiso para Ausencia</div>
                    </div>
                    <div class="col s12 m12 l8">
                        <div class="card">
                            <div class="card-content">
                                <form id="example-form" method="post" name="addemp">
                                    <div>
                                        <h3>Solicitar Ausencia</h3>
                                        <section>
                                            <div class="wizard-content">
                                                <div class="row">
                                                    <div class="col m12">
                                                        <div class="row">
     <?php if($error){?><div class="errorWrap"><strong>ERROR </strong>:<?php echo ($error); ?> </div><?php } 
                else if($msg){?><div class="succWrap"><strong>Proceso Exitoso</strong>:<?php echo ($msg); ?> </div><?php }?>


<div class="input-field col  s12">
<select  id="leavetype" name="leavetype" autocomplete="off">
<option value="">Selecciona el tipo de Ausencia...</option>
<?php $sql = "SELECT  LeaveType from tblleavetype";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{   ?>                                            
<option value="<?php echo ($result->LeaveType);?>"><?php echo ($result->LeaveType);?></option>
<?php }} ?>
</select>
</div>


<div class="input-field col m6 s12">
<!-- <label for="fromdate">Desde</label> -->
<!-- <input id="mask1" name="fromdate" type="date" class="datepicker" autocomplete="off" placeholder="" required> -->
<!-- <input placeholder="" id="mask1" name="fromdate" class="masked" type="date" data-inputmask="'alias': 'date'"  required min=<?php $today=date("Y-m-d"); echo $today;?>> -->
<p>Fecha Inicio: <input type="text" id="datepicker" name="fromdate" ></p>

</div>
<div class="input-field col m6 s12">
<!-- <label for="todate">Hasta</label> -->
<!-- <input  name="todate" type="date" class="datepicker" autocomplete="off" placeholder="" required> -->
<!-- <input placeholder="" id="mask1" name="todate" class="masked" type="date" data-inputmask="'alias': 'date'"  required min=<?php $today=date("Y-m-d"); echo $today;?>> -->
<p>Fecha Fin: <input type="text" id="datepicker2" name="todate" required min=<?php $today=date("Y-m-d"); echo $today;?>></p>

</div>
<div class="input-field col m12 s12">
<label for="birthdate">Descripción</label>    

<textarea id="textarea1" name="description" class="materialize-textarea" length="500"></textarea>
</div>
</div>
      <button type="submit" name="apply" id="apply" class="waves-effect waves-light btn indigo m-b-xs">Solicitar</button>                                             

                                                </div>
                                            </div>
                                        </section>
                                     
                                    
                                        </section>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div class="left-sidebar-hover"></div>
        
        <!-- Javascripts -->

        <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
        <script src="assets/plugins/jquery-datepicker/jquery-1.12.4.js"></script>

        <script src="assets/plugins/materialize/js/materialize.min.js"></script>
        <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
        <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="assets/js/alpha.min.js"></script>
        <script src="assets/js/pages/form_elements.js"></script>
        <script src="assets/js/pages/form-input-mask.js"></script>
        <script src="assets/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>
        
        <script src="assets/plugins/jquery-datepicker/jquery-ui.js"></script>   
        <script src="assets/js/calendar.js"></script>        
     
    </body>
</html>
<?php } ?> 