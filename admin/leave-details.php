<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])<>'')
    {   
header('location:index.php');
}
else{

// code for update the read notification status
$isread=1;
$numemp=intval($_GET['numemp']);  
$id=intval($_GET['id']);
date_default_timezone_set('America/Mazatlan');
$admremarkdate=date('Y-m-d G:i:s ', strtotime("now"));
$sql="update tblleaves set IsRead=:isread where empid=:numemp and id=:id";
$query = $dbh->prepare($sql);
$query->bindParam(':isread',$isread,PDO::PARAM_STR);
$query->bindParam(':numemp',$numemp,PDO::PARAM_STR);
$query->bindParam(':id',$id,PDO::PARAM_STR);
$query->execute();

// code for action taken on leave
if(isset($_POST['update']))
{ 
$numemp=intval($_GET['numemp']);
$id=intval($_GET['id']);
$description=$_POST['description'];
$status=$_POST['status'];   
date_default_timezone_set('America/Mazatlan');
$admremarkdate=date('Y-m-d G:i:s ', strtotime("now"));
$sql="update tblleaves set AdminRemark=:description,Status=:status,AdminRemarkDate=:admremarkdate
where empid=:numemp and id=:id";
$query = $dbh->prepare($sql);
$query->bindParam(':description',$description,PDO::PARAM_STR);
$query->bindParam(':status',$status,PDO::PARAM_STR);
$query->bindParam(':admremarkdate',$admremarkdate,PDO::PARAM_STR);
$query->bindParam(':numemp',$numemp,PDO::PARAM_STR);
$query->bindParam(':id',$id,PDO::PARAM_STR);
$query->execute();
$msg="Ausencia Actualizada Exitósamente";

$FromDate = $_GET['Desde'];
$ToDate = $_GET['Hasta'];

$fechasolicitud="SELECT PostingDate FROM tblleaves WHERE AdminRemark='$description' 
AND Status='$status' AND AdminRemarkDate='$admremarkdate'
AND empid='$numemp' AND id='$id'";
$solicitud=mysqli_query($conn,$fechasolicitud);

while($row = mysqli_fetch_array($solicitud)) {
    /*Imprimir campo por nombre*/
    $fechasolicitud=$row['PostingDate'];
    $sqlday="UPDATE days JOIN tblleaves ON days.EmpId=tblleaves.empid
    SET days.Status='$status' WHERE (Day >= '$FromDate' OR Day <= '$ToDate') and days.EmpId='$numemp' and days.PostingDate = '$fechasolicitud'";
    mysqli_query($conn,$sqlday);
}

//CALCULOS PARA SACAR LOS DIAS AGENDADOS Y LOS DIAS POR AGENDAR
$diastomados="SELECT count(*) as taked FROM days WHERE EmpId='$numemp' AND Status=1";
$resultado=mysqli_query($conn,$diastomados);

while($row = mysqli_fetch_array($resultado)) {
    /*Imprimir campo por nombre*/
    $dias=$row['taked'];
    $actualizacion="UPDATE tblemployees SET days_taken=$dias WHERE EmpId='$numemp'";
    mysqli_query($conn,$actualizacion);
}

$diaspendientes="UPDATE tblemployees SET pending_days=TotalVacations-$dias WHERE EmpId='$numemp'";
mysqli_query($conn,$diaspendientes);
}

 ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        
        <!-- Title -->
        <title>Detalles de Ausencia | Admin </title>
        
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
                        <div class="page-title" style="font-size:24px;">Detalles de Ausencia</div>
                    </div>
                   
                    <div class="col s12 m12 l12">
                        <div class="card">
                            <div class="card-content">
                                <span class="card-title">Detalles de Ausencia</span>
                                <?php if($msg){?><div class="succWrap"><strong>Proceso Exitoso</strong> : <?php echo ($msg); ?> </div><?php }?>
                                <table id="example" class="display responsive-table ">
                               
                                 
                                    <tbody>
<?php 
$numemp=intval($_GET['numemp']);
$id=intval($_GET['id']);
// echo "<script>alert(".$_GET['id'].")</script>";
$sql = "SELECT tblleaves.empid as 
numemp,tblleaves.Id, tblemployees.FirstName,tblemployees.LastName,tblemployees.EmpId,tblemployees.id,tblemployees.Phonenumber,tblemployees.EmailId,tblleaves.LeaveType,tblleaves.ToDate,tblleaves.FromDate,tblleaves.Description,tblleaves.PostingDate,tblleaves.Status,tblleaves.AdminRemark,tblleaves.AdminRemarkDate 
from tblleaves join tblemployees on tblleaves.empid=tblemployees.empid where tblleaves.empid=:numemp and tblleaves.id=:id ";
$query = $dbh -> prepare($sql);
$query->bindParam(':numemp',$numemp,PDO::PARAM_STR);
$query->bindParam(':id',$id,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{         
      ?>  

                                        <tr>
                                            <td style="font-size:16px;"> <b>Nombre Empleado:</b></td>
                                              <td><a href="editemployee.php?empid=<?php echo ($result->id);?>" target="_blank">
                                                <?php echo ($result->FirstName." ".$result->LastName);?></a></td>
                                              <td style="font-size:16px;"><b>ID:</b></td>
                                              <td><?php echo ($result->Id);?></td>
                                          </tr>

                                          <tr>
                                             <td style="font-size:16px;"><b>Correo :</b></td>
                                            <td><?php echo ($result->EmailId);?></td>
                                             <td style="font-size:16px;"><b> No Contacto:</b></td>
                                            <td><?php echo ($result->Phonenumber);?></td>
                                            <td>&nbsp;</td>
                                             <td>&nbsp;</td>
                                        </tr>

  <tr>
                                             <td style="font-size:16px;"><b>Tipo de Ausencia:</b></td>
                                            <td><?php echo ($result->LeaveType);?></td>
                                             <td style="font-size:16px;"><b>Fecha de Ausencia:</b></td>
                                            <td>Desde <?php echo ($result->FromDate);?> Hasta <?php echo ($result->ToDate);?></td>
                                            <td style="font-size:16px;"><b>Fecha Solicitud</b></td>
                                           <td><?php echo ($result->PostingDate);?></td>
                                        </tr>

<tr>
                                             <td style="font-size:16px;"><b>Descripción Solicitud: </b></td>
                                            <td colspan="5"><?php echo ($result->Description);?></td>
                                          
                                        </tr>

<tr>
<td style="font-size:16px;"><b>Estado Solicitud:</b></td>
<td colspan="5"><?php $stats=$result->Status;
if($stats==1){
?>
<span style="color: green">Aprobado</span>
 <?php } if($stats==2)  { ?>
<span style="color: red">No Aprobado</span>
<?php } if($stats==0)  { ?>
 <span style="color: blue">Esperando Aprobación</span>
 <?php } ?>
</td>
</tr>

<tr>
<td style="font-size:16px;"><b>Observaciones Administrador: </b></td>
<td colspan="5"><?php
if($result->AdminRemark==""){
  echo "Esperando Aprobación";  
}
else{
echo ($result->AdminRemark);
}
?></td>
 </tr>

 <tr>
<td style="font-size:16px;"><b>Fecha última acción admin: </b></td>
<td colspan="5"><?php
if($result->AdminRemarkDate==""){
  echo "NA";  
}
else{
echo ($result->AdminRemarkDate);
}
?></td>
 </tr>
<?php 
if($stats==0)
{

?>
<tr>
 <td colspan="5">
  <a class="modal-trigger waves-effect waves-light btn" href="#modal1">Tomar&nbsp;Acción</a>
<form name="adminaction" method="post">
<div id="modal1" class="modal modal-fixed-footer" style="height: 60%">
    <div class="modal-content" style="width:90%">
        <h4>Tomar acción</h4>
          <select class="browser-default" name="status" required="">
                                            <option value="">Escoge tu opción</option>
                                            <option value="1">Aprobada</option>
                                            <option value="2">No aprobada</option>
                                        </select></p>
                                        <p><textarea id="textarea1" name="description" class="materialize-textarea" name="description" placeholder="Descripción" length="500" maxlength="500"></textarea></p>
    </div>
    <div class="modal-footer" style="width:90%">
       <input type="submit" class="waves-effect waves-light btn blue m-b-xs" name="update" value="Enviar">
    </div>

</div>   

 </td>
</tr>
<?php } ?>
   </form>                                     </tr>
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