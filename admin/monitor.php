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
            <div class="row">
            <div class="col s12">
                <div class="page-title">TOTAL DE DIAS AGENDADOS POR MES</div>
            </div>
                <div class="middle-content">
                    <div class="row no-m-t no-m-b">
                    <div class="col s12 m12 l4">
                        <div class="card stats-card">
                            <div class="card-content">
                                <span class="card-title">ENERO</span>
                                
<?php
$sql = "SELECT SUM(Status) as Total FROM Days WHERE (Rol='Programador' OR Rol='Arquitecto') 
AND (LeaveType='Vacaciones' OR LeaveType='Dias a favor') 
AND MONTH(Day) = 01 AND Status=1 ";
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
                            
                                <span class="card-title">FEBRERO</span>
<?php
$sql = "SELECT SUM(Status) as Total FROM Days WHERE (Rol='Programador' OR Rol='Arquitecto') 
AND (LeaveType='Vacaciones' OR LeaveType='Dias a favor') 
AND MONTH(Day) = 02 AND Status=1 ";
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
                                <span class="card-title">MARZO</span>
                                <?php
$sql = "SELECT SUM(Status) as Total FROM Days WHERE (Rol='Programador' OR Rol='Arquitecto') 
AND (LeaveType='Vacaciones' OR LeaveType='Dias a favor') 
AND MONTH(Day) = 03 AND Status=1 ";
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
                                <span class="card-title">ABRIL</span>
                                <?php
$sql = "SELECT SUM(Status) as Total FROM Days WHERE (Rol='Programador' OR Rol='Arquitecto') 
AND (LeaveType='Vacaciones' OR LeaveType='Dias a favor') 
AND MONTH(Day) = 04 AND Status=1 ";
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
                                <span class="card-title">MAYO</span>
                                <?php
$sql = "SELECT SUM(Status) as Total FROM Days WHERE (Rol='Programador' OR Rol='Arquitecto') 
AND (LeaveType='Vacaciones' OR LeaveType='Dias a favor') 
AND MONTH(Day) = 05 AND Status=1 ";
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
                                <span class="card-title">JUNIO</span>
                                <?php
$sql = "SELECT SUM(Status) as Total FROM Days WHERE (Rol='Programador' OR Rol='Arquitecto') 
AND (LeaveType='Vacaciones' OR LeaveType='Dias a favor') 
AND MONTH(Day) = 06 AND Status=1 ";
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
                                <span class="card-title">JULIO</span>
                                <?php
$sql = "SELECT SUM(Status) as Total FROM Days WHERE (Rol='Programador' OR Rol='Arquitecto') 
AND (LeaveType='Vacaciones' OR LeaveType='Dias a favor') 
AND MONTH(Day) = 07 AND Status=1 ";
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
                                <span class="card-title">AGOSTO</span>
                                <?php
$sql = "SELECT SUM(Status) as Total FROM Days WHERE (Rol='Programador' OR Rol='Arquitecto') 
AND (LeaveType='Vacaciones' OR LeaveType='Dias a favor') 
AND MONTH(Day) = 08 AND Status=1 ";
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
                                <span class="card-title">SEPTIEMBRE</span>
                                <?php
$sql = "SELECT SUM(Status) as Total FROM Days WHERE (Rol='Programador' OR Rol='Arquitecto') 
AND (LeaveType='Vacaciones' OR LeaveType='Dias a favor') 
AND MONTH(Day) = 09 AND Status=1 ";
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
                                <span class="card-title">OCTUBRE</span>
                                <?php
$sql = "SELECT SUM(Status) as Total FROM Days WHERE (Rol='Programador' OR Rol='Arquitecto') 
AND (LeaveType='Vacaciones' OR LeaveType='Dias a favor') 
AND MONTH(Day) = 10 AND Status=1 ";
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
                                <span class="card-title">NOVIEMBRE</span>
                                <?php
$sql = "SELECT SUM(Status) as Total FROM Days WHERE (Rol='Programador' OR Rol='Arquitecto') 
AND (LeaveType='Vacaciones' OR LeaveType='Dias a favor') 
AND MONTH(Day) = 11 AND Status=1 ";
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
                                <span class="card-title">DICIEMBRE</span>
                                <?php
$sql = "SELECT SUM(Status) as Total FROM Days WHERE (Rol='Programador' OR Rol='Arquitecto') 
AND (LeaveType='Vacaciones' OR LeaveType='Dias a favor') 
AND MONTH(Day) = 12 AND Status=1 ";
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

                </div>
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