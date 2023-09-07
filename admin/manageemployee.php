<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])<>'')
    {   
header('location:index.php');
}
else{
// code for Inactive  employee    
if(isset($_GET['inid']))
{
$id=$_GET['inid'];
$status=0;
$sql = "update tblemployees set Status=$status  WHERE id=$id";
if(mysqli_query($conn, $sql))
    {
        header('location:manageemployee.php');
    }
    else
    {
        $error="Algo salio mal. Intentalo de nuevo";
    } 
}

//code for active employee
if(isset($_GET['id']))
{
$id=$_GET['id'];
$status=1;
$sql = "update tblemployees set Status=$status  WHERE id=$id";
if(mysqli_query($conn, $sql))
    {
        header('location:manageemployee.php');
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
        <title>Admin | Gestionar Empleados</title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta charset="UTF-8">
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        
        
        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
        <link href="../assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

            
        <!-- Theme Styles -->
        <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css"/>
        <link href="../assets/css/custom.css" rel="stylesheet" type="text/css"/>
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
                        <div class="page-title">Gestionar Empleados</div>
                    </div>
                   
                    <div class="col s12 m12 l12">
                        <div class="card">
                            <div class="card-content">
                                <span class="card-title">Información de Empleados</span>
                                <?php if($msg){?><div class="succWrap"><strong>Proceso Exitoso</strong> : <?php echo ($msg); ?> </div><?php }?>
                                <table id="example" class="display responsive-table ">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID Empleado</th>
                                            <th>Nombre Completo</th>
                                            <th>Antiguedad</th>
                                            <th>Dias extra</th>
                                            <th>Total</th>
                                            <th>Agendados</th>
                                            <th>Pendientes</th>
                                            <th>Estatus</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                 
                                    <tbody>
<?php $sql = "SELECT EmpId,FirstName,LastName,Experience,Days,TotalVacations,Days_taken,Pending_days,Status,id from  tblemployees";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>  
                                        <tr>
                                            <td> <?php echo ($cnt);?></td>
                                            <td><?php echo ($result->EmpId);?></td>
                                            <td><?php echo ($result->FirstName);?>&nbsp;<?php echo ($result->LastName);?></td>
                                            <td><?php echo ($result->Experience);?></td>
                                            <td><?php echo ($result->Days);?></td>
                                            <td><?php echo ($result->TotalVacations);?></td>
                                            <td><?php echo ($result->Days_taken);?></td>
                                            <td><?php echo ($result->Pending_days);?></td>
                                             <td><?php $stats=$result->Status;
if($stats){
                                             ?>
                                                 <a class="waves-effect waves-green btn-flat m-b-xs">Activo</a>
                                                 <?php } else { ?>
                                                 <a class="waves-effect waves-red btn-flat m-b-xs">Inactivo</a>
                                                 <?php } ?>


                                             </td>
                                            
                                            <td><a href="editemployee.php?empid=<?php echo ($result->id);?>"><i class="material-icons">mode_edit</i></a>
                                        <?php if($result->Status==1)
 {?>
<a href="manageemployee.php?inid=<?php echo ($result->id);?>" onclick="return confirm('¿Estás seguro de que quieres inactivar este Empleado?');"" > <i class="material-icons" title="Inactive">clear</i>
<?php } else {?>

                                            <a href="manageemployee.php?id=<?php echo ($result->id);?>" onclick="return confirm('¿Está seguro de que desea activar a este empleado?');""><i class="material-icons" title="Active">done</i>
                                            <?php } ?> </td>
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
        <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
        <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
        <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
        <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="../assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
        <script src="../assets/js/alpha.min.js"></script>
        <script src="../assets/js/pages/table-data.js"></script>
        
    </body>
</html>
<?php } ?>