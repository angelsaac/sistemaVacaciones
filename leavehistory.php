<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['emplogin'])<>'')
    {   
header('location:index.php');
}
else{
 if(isset($_GET['del']))
{
$id=$_GET['del'];

$fechasolicitud="SELECT PostingDate FROM tblleaves WHERE Status='0' AND id='$id'";
$solicitud=mysqli_query($conn,$fechasolicitud);

while($row = mysqli_fetch_array($solicitud)) 
{
        /*Imprimir campo por nombre*/
        $fechasolicitud=$row['PostingDate'];
        $sqlday="DELETE FROM days WHERE PostingDate= '$fechasolicitud' AND Status='0';";
        mysqli_query($conn,$sqlday);
    
}

//TABLA EN DONDE SE AGENDAN LOS DIAS
$sql = "DELETE FROM  tblleaves  WHERE id=$id AND Status='0';";
    if(mysqli_query($conn, $sql))
    {
        $msg="Registro de Tipo de Ausencia Eliminado";
    }
    else
    {
        $error="Algo salio mal. Intentalo de nuevo";
    } 
}

 ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        
        <!-- Title -->
        <title>Empleado | Historial de Ausencias</title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta charset="UTF-8">
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        
        
        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
        <link href="assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

            
        <!-- Theme Styles -->
        <link href="assets/css/alpha.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/custom.css" rel="stylesheet" type="text/css"/>
<style>
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
                        <div class="page-title">Historial de Ausencias</div>
                    </div>
                   
                    <div class="col s12 m12 l12">
                        <div class="card">
                            <div class="card-content">
                                <span class="card-title">Historial de Ausencias</span>
                                <?php if($msg){?><div class="succWrap"><strong>Proceso Exitoso</strong> : <?php echo ($msg); ?> </div><?php }?>
                                <table id="example" class="display responsive-table ">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th width="120">Tipo de Ausencia</th>
                                            <th>Desde</th>
                                            <th>Hasta</th>
                                             <th>Descripción</th>
                                             <th width="120">Fecha Solicitud</th>
                                            <th width="200">Observación Administrador</th>
                                            <th>Status</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                 
                                    <tbody>
<?php 
$eid=$_GET['empid'];
$_SESSION['eid']=$eid;
$sql = "SELECT id, LeaveType,FromDate,ToDate,Description,PostingDate,AdminRemarkDate,AdminRemark,Status from tblleaves where empid=:eid";
$query = $dbh -> prepare($sql);
$query->bindParam(':eid',$eid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>  
                                        <tr>
                                            <td> <?php echo ($cnt);?></td>
                                            <td><?php echo ($result->LeaveType);?></td>
                                            <td><?php echo ($result->FromDate);?></td>
                                            <td><?php echo ($result->ToDate);?></td>
                                           <td><?php echo ($result->Description);?></td>
                                            <td><?php echo ($result->PostingDate);?></td>
                                            <td><?php if($result->AdminRemark=="")
                                            {
echo ('Esperando Aprobación');
                                            } else
{

 echo (($result->AdminRemark)." "."at"." ".$result->AdminRemarkDate);
}

                                            ?></td>
                                                                                 <td><?php $stats=$result->Status;
if($stats==1){
                                             ?>
                                                 <span style="color: green">Aprobado</span>
                                                 <?php } if($stats==2)  { ?>
                                                <span style="color: red">No Aprobado</span>
                                                 <?php } if($stats==0)  { ?>
 <span style="color: blue">Esperando Aprobación</span>
 <?php } ?>

                                            </td>
                                            <td><a href="leavehistory.php?empid=<?php echo urlencode($_SESSION['eid']);?>&del=<?php echo ($result->id);?>" onclick="return confirm('Se eliminara si el estatus se encuentra en espera');"> <i class="material-icons">delete_forever</i></a> </td>
                                        </tr>
                                         <?php $cnt++;} }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
         
        </div>
        <div class="left-sidebar-hover"></div>
        
        <!-- Javascripts -->
        <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
        <script src="assets/plugins/materialize/js/materialize.min.js"></script>
        <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
        <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
        <script src="assets/js/alpha.min.js"></script>
        <script src="assets/js/pages/table-data.js"></script>
        
    </body>
</html>
<?php } ?>