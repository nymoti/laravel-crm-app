<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>A Complex Table</title>
  
  
  
      <link rel="stylesheet" href="css/style.css">

<style>
body{
	background-color:#333;
	font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
	color:#333;
	text-align:left;
	font-size:18px;
	margin:0;
}
.container{
	margin:0 auto;
	margin-top:35px;
	padding:40px;
	width:750px;
	height:auto;
	background-color:#fff;
}
caption{
	font-size:28px;
	margin-bottom:15px;
}
table{
	border:1px solid #333;
	border-collapse:collapse;
	margin:0 auto;
	width:740px;
}
td, tr, th{
	padding:12px;
	border:1px solid #333;
	width:185px;
}
th{
	background-color: #f0f0f0;
}
h4, p{
	margin:0px;
}
</style>
</head>

<body>

  <div class="container">
	<table>
		<caption>
			A Complex Table
		</caption>
		<thead>
			<tr>
				<th colspan="3">Invoice#123456789</th>
				<th>14 January 2025</th>
			</tr>
			<tr>
				<td colspan="2">
					<h4>Pay to:</h4>
					<p>Acme Billing Co.<br>
					123 Main St.<br>
					Cityville, NA 12345</p>
				</td>
				<td colspan="2">
					<h4>Customer:</h4>
					<p>John Smith<br>
					321 Willow Way<br>
					Southeast Northwestershire, MA 54321</p>
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>Name/Description</th>
				<th>Qty.</th>
				<th>@</th>
				<th>Cost</th>
			</tr>
			<tr>
				<td>Paperclips</td>
				<td>1000</td>
				<td>0.01</td>
				<td>10.00</td>
			</tr>
			<tr>
				<td>Staples(box)</td>
				<td>100</td>
				<td>1.00</td>
				<td>100.00</td>
			</tr>
			<tr>
				<th colspan="3">Subtotal</th>
				<td>110.00</td>
			</tr>
			<tr>
				<th>Tax</th>
				<td>1000</td>
				<td>8%</td>
				<td>8.80</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="3">Grand Total</th>
				<td>$118.80</td>
			</tr>
		</tfoot>
	</table>
</div>
  
  

</body>

</html>
