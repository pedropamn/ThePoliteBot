<?php

	require('../db.php');

	$conn = get_conn();
	$stmt1 = $conn->prepare('SELECT * FROM groups ORDER BY polite_occurrences DESC');
	$stmt1->execute();
	$result = $stmt1->get_result();
	$trs = "";
	while ($row = $result->fetch_assoc()) {
		$name = $row['name'];
		$unpolite_occurrences = $row['unpolite_occurrences'];
		$polite_occurrences = $row['polite_occurrences'];
		$points = $polite_occurrences - $unpolite_occurrences;
		$trs .= " <tr>
                <td class='position'></td>
                <td>{$name}</td>
				<td>{$points}</td>
                <td>{$polite_occurrences}</td>
                <td>{$unpolite_occurrences}</td>
                
            </tr>";
			
	}
	
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
#sailorTableArea{
    max-height: 1000px;
    overflow-x: auto;
    overflow-y: auto;
}
#sailorTable{
    white-space: normal;
}
tbody {
    display:block;
    overflow:auto;
	text-align:center;
}
thead, tbody tr {
    display:table;
    width:100%;
    table-layout:fixed;
	text-align:center;
}

img{
	border-radius: 50%;
}

</style>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<script>
$(document).ready(function() {
    $('#sailorTable').DataTable( {
        "order": [[ 2, "desc" ]]
    } );
	
	//Nums in positions
	var i = 1;
	$( ".position" ).each(function( index ) {
	   $( this ).text(i);
	   i++;
	});
} );
</script>
<div class="table-responsive" id="sailorTableArea">
	<center><h1>The Polite Ranking</h1></center>
    <table id="sailorTable" class="table table-striped table-bordered" width="100%">

        <thead>
			  
            <tr>
                <th>#</th>
                <th>Name</th>
				<th>Points</th>
                <th>Polites</th>
                <th>Unpolites</th>
                

            </tr>
        </thead>
        <tbody>
           <?php echo $trs; ?>
           
        </tbody>
    </table>
    </div>