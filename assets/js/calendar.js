$(document).ready(function(){
    var queryString = window.location.search;
    var urlParams = new URLSearchParams(queryString);
    var anuncioParam = urlParams.get('job');
    console.log(anuncioParam)
    $( function() {
        var parametros ="opc=fechas" + "&roll=" + anuncioParam ;
        $.ajax({
            cache: false,
            url: 'dates.php',
            type: 'POST',
            dataType: 'json',
            data: parametros,
            success:function(response){
                console.log(response.rol)
                let arreglo = new Array;
                response.conn.forEach(element => {
                    arreglo.push(element);
                });
                function DisableSpecificDates(date) {
                    var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                    return [arreglo.indexOf(string) == -1];
                }

                $('#datepicker2').datepicker({
                    beforeShowDay: DisableSpecificDates, 
                    altField: "yyyy-mm-dd",
                    dateFormat:"yy-mm-dd"

                });

                // datepicker
                $('#datepicker').datepicker({
                    beforeShowDay: DisableSpecificDates,
                    altField : "yyyy-mm-dd",
                    dateFormat:"yy-mm-dd"
                    

                });
            },
            error:function(xhr,status,error) {
                alert("error en la conexion a base de datos");
            }
        });
    });
});
