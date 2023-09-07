<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])<>'')
    {   
header('location:index.php');
}
else{
$eid=intval($_GET['empid']);
if(isset($_POST['update']))
{
$empid=$_POST['empcode'];
$fname=$_POST['firstName'];
$lname=$_POST['lastName'];   
$dob=$_POST['dob']; 
$department=$_POST['department']; 
$occupation=$_POST['occupation']; 
$address=$_POST['address']; 
$city=$_POST['city']; 
$country=$_POST['country']; 
$mobileno=$_POST['mobileno']; 
$experience=$_POST['experience']; 
$days=$_POST['days']; 

$sql="update tblemployees set FirstName='$fname',LastName='$lname',Dob='$dob',Department='$department', Occupation='$occupation', Experience='$experience', Days='$days',
Address='$address',Phonenumber=$mobileno where id=$eid";
 if(mysqli_query($conn, $sql))
    {
        $msg="Registro de Emplead@ Actualizado Exitosamente";
    }
    else
    {
        $error="Algo salio mal. Intentalo de nuevo";
    } 

$total="UPDATE tblemployees JOIN tblvacations ON tblemployees.Experience=tblvacations.years
    SET tblemployees.TotalVacations= tblvacations.Vacations+tblemployees.Days WHERE tblemployees.id=$eid";
mysqli_query($conn, $total);   

//CALCULOS PARA SACAR LOS DIAS AGENDADOS Y LOS DIAS POR AGENDAR
$diastomados="SELECT count(*) as taked FROM days WHERE EmpId='$empid' AND Status=1";
$resultado=mysqli_query($conn,$diastomados);

while($row = mysqli_fetch_array($resultado)) {
    /*Imprimir campo por nombre*/
    $dias=$row['taked'];
    $actualizacion="UPDATE tblemployees SET days_taken=$dias WHERE EmpId='$empid'";
    mysqli_query($conn,$actualizacion);
}

$diaspendientes="UPDATE tblemployees SET Pending_days=TotalVacations-$dias WHERE EmpId='$empid'";
mysqli_query($conn,$diaspendientes);
}

    ?>

<!DOCTYPE html>
<html lang="es">
    <head>
        
        <!-- Title -->
        <title>Admin | Actualizar Info Emplead@</title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta charset="UTF-8">
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        
        
        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet"> 
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
                        <div class="page-title">Actualizar Info de Empleado</div>
                    </div>
                    <div class="col s12 m12 l12">
                        <div class="card">
                            <div class="card-content">
                                <form id="example-form" method="post" name="updatemp">
                                    <div>
                                        <h3>Actualizar Info de Empleado</h3>
                                           <?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo ($error); ?> </div><?php } 
                else if($msg){?><div class="succWrap"><strong>Proceso Exitoso</strong> : <?php echo ($msg); ?> </div><?php }?>
                                        <section>
                                            <div class="wizard-content">
                                                <div class="row">
                                                    <div class="col m6">
                                                        <div class="row">
<?php 
$eid=intval($_GET['empid']);
$sql = "SELECT * from  tblemployees where id=:eid";
$query = $dbh -> prepare($sql);
$query -> bindParam(':eid',$eid, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?> 
 <div class="input-field col  s12">
<label for="empcode">Número de Empleado (Debe ser único)</label>
<input  name="empcode" id="empcode" value="<?php echo ($result->EmpId);?>" type="text" autocomplete="off" readonly required>
<span id="empid-availability" style="font-size:12px;"></span> 
</div>

<div class="input-field col m6 s12">
<label for="firstName">Nombre</label>
<input id="firstName" name="firstName" value="<?php echo ($result->FirstName);?>"  type="text" required>
</div>

<div class="input-field col m6 s12">
<label for="lastName">Apellido</label>
<input id="lastName" name="lastName" value="<?php echo ($result->LastName);?>" type="text" autocomplete="off" required>
</div>

<div class="input-field col s12">
<label for="email">Correo</label>
<input  name="email" type="email" id="email" value="<?php echo ($result->EmailId);?>" readonly autocomplete="off" required>
<span id="emailid-availability" style="font-size:12px;"></span> 
</div>

<div class="input-field col s12">
<label for="phone">Número de telefono</label>
<input id="phone" name="mobileno" type="tel" value="<?php echo ($result->Phonenumber);?>" maxlength="10" autocomplete="off" required>
 </div>

</div>
</div>
                                                    
<div class="col m6">
<div class="row">
<div class="input-field col m6 s12">
<input id="birthdate" name="dob"  class="datepicker" value="<?php echo ($result->Dob);?>" >
</div>

                                                    

<div class="input-field col m6 s12">
<select  name="department" autocomplete="off">
<option value="<?php echo ($result->Department);?>"><?php echo ($result->Department);?></option>
<?php $sql = "SELECT DepartmentName from tbldepartments";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $resultt)
{   ?>                                            
<option value="<?php echo ($resultt->DepartmentName);?>"><?php echo ($resultt->DepartmentName);?></option>
<?php }} ?>
</select>
</div>

<div class="input-field col m6 s12">
<select  name="occupation" autocomplete="off">
<option value="<?php echo ($result->Occupation);?>"><?php echo ($result->Occupation);?></option>                                          
<option value="Arquitecto">Arquitecto</option>
<option value="Programador">Programador</option>
</select>
</div>

<div class="input-field col m6 s12">
<label for="address">Dirección</label>
<input id="address" name="address" type="text"  value="<?php echo ($result->Address);?>" autocomplete="off" required>
</div>

<div class="col m6 s12">
<div class="input-field col m6 s12">
<label for="experience">Años de antiguedad</label>
<input id="experience" name="experience" type="text"  value="<?php echo ($result->Experience);?>" autocomplete="off" required>
</div>

<div class="input-field col m6 s12">
<label for="days">Dias a favor</label>
<input id="days" name="days" type="text"  value="<?php echo ($result->Days);?>" autocomplete="off" required>
</div>

<div class="input-field col m6 s12">
<label for="totalVacations">Total de Vacaciones</label>
<input id="totalVacations" name="totalVacations" type="text"  value="<?php echo ($result->TotalVacations);?>" readonly autocomplete="off" required>
</div>
</div>

<div class="col m6 s12">
<div class="input-field col m6 s12">
<label for="daystaked">Dias agendados</label>
<input id="daystaked" name="daystaked" type="text"  value="<?php echo ($result->Days_taken);?>" readonly autocomplete="off" required>
</div>

<div class="input-field col m6 s12">
<label for="pendingdays">Dias pendientes</label>
<input id="pendingdays" name="pendingdays" type="text"  value="<?php echo ($result->Pending_days);?>" readonly autocomplete="off" required>
</div>


</div>



                                                        
<?php }}?>
                                                        
<div class="input-field col s12">
<button type="submit" name="update"  id="update" class="waves-effect waves-light btn indigo m-b-xs">Actualizar</button>

</div>

                                                        </div>
                                                    </div>
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
        <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
        <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
        <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
        <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="../assets/js/alpha.min.js"></script>
        <script src="../assets/js/pages/form_elements.js"></script>
        
    </body>
</html>
<?php } ?> 