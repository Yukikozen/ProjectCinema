<?php
session_start();
if(empty($_SESSION["username"]))
{
    header("Location:index.php");
}
else
{
    include_once "header.php";
}

$checked_value=0;
if(isset($_POST["booking_btn"]))
{
    $con=new connec();
     
    $cust_id=$_POST["cust_id"];
    $show_id=$_POST["show_id"];
    $no_ticket=$_POST["no_ticket"];

    $booking_date=$_POST["booking_date"];

    $total_amt=(15* $no_ticket);
    
  
    
   $seat_number=$_POST["seat_dt"];
    $seat_arr=explode(", ",$seat_number);

    foreach($seat_arr as $item)
    {
        $sql="insert into seat_reserved values(0,$show_id,$cust_id,'$item','true')";
       $abc= $con->insert_lastid($sql);
    }
    
    $sql="INSERT into seat_detail values(0,$cust_id, $show_id,$no_ticket)";  
    $seat_dt_id=$con->insert_lastid($sql);
  
    
   $sql="INSERT into booking values(0,$cust_id, $show_id,$no_ticket, '$booking_date',$total_amt, $seat_dt_id)";
//   echo "SQL Query: " . $sql;

   $con->insert($sql, "Your Ticket is Booked");
}
?>

<script>
$(document).ready(function(){
    for(i=1; i <=4; i++)
    {
//        $('#seat_chart').append('<tr>')
        for(j=1; j<=10; j++)
        {

        $('#seat_chart').append('<div class="col-md-2 mt-2 mb-2 ml-2 mr-2 text-center" style="background-color:grey;color:white"><input type="checkbox" id="seat" value="R'+(i+'S'+j)+'" name="seat_chart[]" class="mr-2  col-md-2 mb-2" onclick="checkboxtotal();" >R'+(i+'S'+j)+'</div>')        }
    }
//    $('#seat_chart').append('<tr>')

});
//var ch =0;
function checkboxtotal()
{
//    ch++;
//    document.getElementById("no_ticket").value=ch;

var seat=[];
$('input[name="seat_chart[]"]:checked').each(function(){
seat.push($(this).val());    
});

var st=seat.length;
document.getElementById('no_ticket').value=st;
//$('#seat_details').text(seat.join(", "));
$('#seat_dt').val(seat.join(", "));
}
</script>

<section class="mt-5">
    <h3 class="text-center" style="color:maroon;"> Book Your ticket Now</h3>
    
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div id="seat-map" id="seatCharts">
                    <h3 class="text-center mt-5" style="color:maroon;">Select Seat</h3>
                    <hr>
                    <label class="text-center" style="width:100%; background-color:maroon;color:white; padding:2%;">
                        SCREEN
                    </label>
                    <div class="row" id="seat_chart"></div>
                </div>
            </div>
            <div class="col-md-6">
                <form method="POST" class="mt-5">
                    <div class="container" style="color:maroon;">
        <center>
            <p>Please fill this form to book your ticket</p>
        </center>
        <hr>
        
        <label for="cust_id"><b>Customer Id</b></label>
         <input type="number" style="border-radius:30px;"  name="cust_id"  required value=<?php echo $_SESSION["cust_id"]; ?> >

         <label for=""><b>Show</b></label>
         <input type="text" style="border-radius:30px;"  name="show_id"  required>
         
         <label for=""><b>No. of Tickets</b></label>
         <input type="number" style="border-radius:30px;"  name="no_ticket"  id="no_ticket" required>

         <label for=""><b>Seat Details</b></label>
         <input type="text" style="border-radius:30px;"  name="seat_dt" id="seat_dt" required>
         
         <label for=""><b>Booking Date</b></label>
         <input type="date" style="border-radius:30px;"  name="booking_date"  required>
        </div>
                    
        <button type="submit" name="booking_btn" class="btn" style="background-color:maroon;color:white;">Confirm Booking</button>

    </form>
            </div>

        </div>
    </div>
    
    <script>
//        function checkboxtotal()
//{
//    alert();
//}
        
        </script>
    
    
</section>