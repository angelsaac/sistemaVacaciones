
<aside id="slide-out" class="side-nav white fixed">
                <div class="side-nav-wrapper">
                    <div class="sidebar-profile">
                        <div class="sidebar-profile-image">
                            <img src="assets/images/profile-image.png" class="circle" alt="">
                        </div>
                        <div class="sidebar-profile-info">
                    <?php
session_start();
$eid =$_SESSION['uname'];

$sql = "SELECT FirstName,LastName,Occupation,EmpId from  tblemployees where EmpId=:eid";
$query = $dbh -> prepare($sql);
$query->bindParam(':eid',$eid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>
                                <p><?php echo ($result->FirstName." ".$result->LastName);?></p>
                                <span><?php echo ($result->EmpId)?></span>
                         <?php }} ?>
                        </div>
                    </div>

                <ul class="sidebar-menu collapsible collapsible-accordion" data-collapsible="accordion">
  <li class="no-padding"><a class="waves-effect waves-grey" href='myprofile.php?empid=<?php echo urlencode($_SESSION['uname']);?>'><i class="material-icons">account_box</i>Mi Perfil</a></li>
  <li class="no-padding"><a class="waves-effect waves-grey" href="emp-changepassword.php?empid=<?php echo urlencode($_SESSION['uname']);?>"><i class="material-icons">settings_input_svideo</i>Cambiar Contraseña</a></li>
                    <li class="no-padding">
                        <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">apps</i>Ausencias<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a href="apply-leave.php?empid=<?php echo urlencode($_SESSION['uname']);?>&job=<?php echo htmlentities($result->Occupation)?>">Solicitar Ausencia</a></li>
                                <li><a href="leavehistory.php?empid=<?php echo urlencode($_SESSION['uname']);?>">Historial Ausencias</a></li>
                            </ul>
                        </div>
                    </li>

                  <li class="no-padding">
                                <a class="waves-effect waves-grey" href="logout.php"><i class="material-icons">exit_to_app</i>Cerrar Sesión</a>
                            </li>
                </ul> 
                </div>
            </aside>