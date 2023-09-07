<?php
include('includes/config.php');
switch ($_POST["opc"]) {
    case 'fechas':
        fechas($conn, $_POST["roll"]);
        break;
    
    default:
        # code...
        break;
}

function fechas($conn, $roll){
 
    if ($roll == 'Programador')
    { 
        if ($result = $conn->query("SELECT  Day, Rol, Status, count(*) FROM `days` WHERE
            (Rol= 'Programador' or Rol= '') and (LeaveType='Vacaciones' OR LeaveType='Dias a favor'  OR LeaveType='Asueto' ) AND Status=1
            group by 1,2,3  having count(*) >= 2")) {
            $arreglo = array();
            while($obj = $result->fetch_object()){
                $var = $obj -> Day;
                array_push($arreglo, $var);
            }
        }
    }

    if ($roll == 'Arquitecto')
    { 
        if ($result = $conn->query("SELECT Day, Rol, Status, count(*) AS contador FROM days WHERE  
             (Rol= '$roll' or Rol= '') AND  (LeaveType='Vacaciones' OR LeaveType='Dias a favor'  OR LeaveType='Asueto' ) and
            Status=1 group by 1,2,3 having count(*) >= 1;")) {
            $arreglo = array();
            while($obj = $result->fetch_object()){
                $var = $obj -> Day;
                array_push($arreglo, $var);
            }
        }
    }
    $hola = array("hola" => "hola", "conn" => $arreglo, "rol" => $roll);
    return print json_encode($hola);
}


?>