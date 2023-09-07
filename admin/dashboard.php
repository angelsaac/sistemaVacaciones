<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])<>'')
    {   
header('location:index.php');
}
else{
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        
        <!-- Title -->
        <title>Admin | Dashboard</title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta charset="UTF-8">
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        
        
        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">    
        <link href="../assets/plugins/metrojs/MetroJs.min.css" rel="stylesheet">
        <link href="../assets/plugins/weather-icons-master/css/weather-icons.min.css" rel="stylesheet">

        	
        <!-- Theme Styles -->
        <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css"/>
        <link href="../assets/css/custom.css" rel="stylesheet" type="text/css"/>
        
    </head>
    <body>
           <?php include('includes/header.php');?>
            
       <?php include('includes/sidebar.php');?>

            <main class="mn-inner">
                <div class="middle-content">
                    <div class="row no-m-t no-m-b">
                    <div class="col s12 m12 l4">
                        <div class="card stats-card">
                            <div class="card-content">
                            
                                <span class="card-title">TOTAL DÍAS VACACIONES</span>
                                <span class="stats-counter">
<?php
$sql = "SELECT SUM(TotalVacations) as Total FROM tblemployees";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if ($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>
                                    <span class="counter"><?php echo ($result->Total);?></span></span>
                                    <?php }} ?>
                            </div>
                            <div id="sparkline-bar"></div>
                        </div>
                    </div>
                        <div class="col s12 m12 l4">
                        <div class="card stats-card">
                            <div class="card-content">
                            
                                <span class="card-title">TOTAL DÍAS AGENDADOS</span>
    <?php
$sql = "SELECT status FROM days WHERE Status=1 AND (LeaveType = 'Vacaciones' OR LeaveType='Dias a favor')";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$dptcount=$query->rowCount();
?>                            
                                <span class="stats-counter"><span class="counter"><?php echo ($dptcount);?></span></span>
                            </div>
                            <div id="sparkline-line"></div>
                        </div>
                    </div>
                    <div class="col s12 m12 l4">
                        <div class="card stats-card">
                            <div class="card-content">
                                <span class="card-title">TOTAL DÍAS SIN AGENDAR</span>
                                    <?php
$sql = "SELECT SUM(TotalVacations) - (SELECT SUM(status) FROM days WHERE Status=1 AND (LeaveType = 'Vacaciones' OR LeaveType='Dias a favor')) as Sinagendar
FROM tblemployees 
";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if ($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>
                                <span class="stats-counter"><span class="counter"><?php echo ($result->Sinagendar);?></span></span>
                                <?php }} ?>
                      
                            </div>
                            <div class="progress stats-card-progress">
                                <div class="determinate" style="width: 70%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                 
                    <div class="row no-m-t no-m-b">
                        <div class="col s12 m12 l12">
                            <div class="card invoices-card">
                                <div class="card-content">
                                 
                                    <span class="card-title">Últimas Solicitudes de Ausencias</span>
                             <table id="example" class="display responsive-table ">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th width="200">Nombre de Empleado</th>
                                            <th width="120">Tipo de Ausencia</th>

                                             <th width="180">Fecha de Solicitud</th>                 
                                            <th>Estatus</th>
                                            <th align="center">Acción</th>
                                        </tr>
                                    </thead>
                                 
                                    <tbody>
<?php $sql = "SELECT tblleaves.empid
as emp, tblleaves.id as id,tblemployees.FirstName,tblemployees.LastName,tblemployees.EmpId,tblleaves.LeaveType,tblleaves.PostingDate,tblleaves.Status, 
tblleaves.FromDate, tblleaves.ToDate from tblleaves join tblemployees on tblleaves.empid=tblemployees.empid order by tblleaves.PostingDate desc limit 6";
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
                                              <td><a href="editemployee.php?empid=<?php echo ($result->emp);?>" target="_blank"><?php echo ($result->FirstName." ".$result->LastName);?>(<?php echo htmlentities($result->EmpId);?>)</a></td>
                                            <td><?php echo ($result->LeaveType);?></td>
                                            <td><?php echo ($result->PostingDate);?></td>
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

          <td>
           <td><a href="leave-details.php?numemp=<?php echo ($result->emp);?>&id=<?php echo ($result->id);?>&Desde=<?php echo ($result->FromDate);?>&Hasta=<?php echo ($result->ToDate);?>" class="waves-effect waves-light btn blue m-b-xs"  > Ver Detalles</a></td>
                                    </tr>
                                         <?php $cnt++;} }?>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              
            </main>
          
        </div>

        
        
        <!-- Javascripts -->
        <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
        <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
        <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
        <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="../assets/plugins/waypoints/jquery.waypoints.min.js"></script>
        <script src="../assets/plugins/counter-up-master/jquery.counterup.min.js"></script>
        <script src="../assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
        <script src="../assets/plugins/chart.js/chart.min.js"></script>
        <script src="../assets/plugins/flot/jquery.flot.min.js"></script>
        <script src="../assets/plugins/flot/jquery.flot.time.min.js"></script>
        <script src="../assets/plugins/flot/jquery.flot.symbol.min.js"></script>
        <script src="../assets/plugins/flot/jquery.flot.resize.min.js"></script>
        <script src="../assets/plugins/flot/jquery.flot.tooltip.min.js"></script>
        <script src="../assets/plugins/curvedlines/curvedLines.js"></script>
        <script src="../assets/plugins/peity/jquery.peity.min.js"></script>
        <script src="../assets/js/alpha.min.js"></script>
        <script src="../assets/js/pages/dashboard.js"></script>
        
    </body>
</html>
<?php } ?>