<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])<>'')
    {   
header('location:index.php');
}
else{
    if(isset($_GET['del']))
    {
    $numemp=$_POST['id'];
    $id=$_GET['del'];
    
    //TABLA EN DONDE SE REGISTRAN EL RANGO DE DIAS AGENDADOS
    $update="UPDATE days JOIN tblleaves ON days.Empid=tblleaves.empid
    SET days.LeaveType= 'Borrar' WHERE days.day >= tblleaves.FromDate and days.day<=  tblleaves.ToDate and 
    tblleaves.id=$id and days.PostingDate = tblleaves.PostingDate;";
    mysqli_query($conn, $update);
    
    $delete="DELETE FROM days WHERE LeaveType= 'Borrar';";
    mysqli_query($conn, $delete);
    
    //TABLA EN DONDE SE AGENDAN LOS DIAS
    $sql = "DELETE FROM  tblleaves  WHERE id=$id";
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
        <title>Admin | Total de Ausencias</title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta charset="UTF-8">
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        
        
        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
        <link href="../assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

                <link href="../assets/plugins/google-code-prettify/prettify.css" rel="stylesheet" type="text/css"/>  
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
                        <div class="page-title">Historial de Ausencias</div>
                    </div>
                   
                    <div class="col s12 m12 l12">
                        <div class="card">
                            <div class="card-content">
                                <span class="card-title">Listado de Ausencias</span>
                                <?php if($msg){?><div class="succWrap"><strong>Proceso Exitoso</strong> : <?php echo ($msg); ?> </div><?php }?>
                                <table id="example" class="display responsive-table ">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th width="200">Nombre de Empleado</th>
                                            <th width="120">Tipo de Ausencia</th>

                                             <th width="180">Fecha de Publicación</th>                 
                                            <th>Status</th>
                                            <th align="center">Acción</th>
                                        </tr>
                                    </thead>
                                 
                                    <tbody>
<?php $sql = "SELECT tblleaves.empid
as emp, tblleaves.id as id,tblemployees.FirstName,tblemployees.LastName,tblemployees.EmpId,tblleaves.LeaveType,tblleaves.PostingDate,tblleaves.Status 
from tblleaves join tblemployees on tblleaves.empid=tblemployees.empid  order by PostingDate desc";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{         
      ?>  

                                        <tr>
                                            <td> <b><?php echo ($cnt);?></b></td>
                                              <td><a href="editemployee.php?empid=<?php echo ($result->id);?>" target="_blank"><?php echo ($result->FirstName." ".$result->LastName);?>(<?php echo htmlentities($result->EmpId);?>)</a></td>
                                            <td><?php echo ($result->LeaveType);?></td>
                                            <td><?php echo ($result->PostingDate);?></td>
                                                                       <td><?php $stats=$result->Status;
if($stats==1){
                                             ?>
                                                 <span style="color: green">Aprobado</span>
                                                 <?php } if($stats==2)  { ?>
                                                <span style="color: red">No aprobado</span>
                                                 <?php } if($stats==0)  { ?>
 <span style="color: blue">Esperando Aprobación</span>
 <?php } ?>


                                             </td>

          <td>
           <td><a href="leave-details.php?numemp=<?php echo ($result->emp);?>&id=<?php echo ($result->id);?>" class="waves-effect waves-light btn blue m-b-xs"  > Ver detalles</a></td>
           <td><a href="leaves.php?numemp=<?php echo ($result->emp);?>&del=<?php echo ($result->id);?>" onclick="return confirm('Solicite al administrador eliminar la solictud');"> <i class="material-icons">delete_forever</i></a> </td>
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
         <script src="assets/js/pages/ui-modals.js"></script>
        <script src="assets/plugins/google-code-prettify/prettify.js"></script>
        
    </body>
</html>
<?php } ?>