<?php
include_once("header.php");
include_once("connect.php");

if(isset($_POST['submit']))
{
	$designation_code = trim($_POST['designation_code']);
	$designation_name = trim($_POST['designation_name']);
	$date = date('Y-m-d');
	$query=mysqli_query($con,"INSERT INTO designation(designation_code, designation_name, date) VALUES('$designation_code','$designation_name','$date')");
 
	if($query)
	{
		echo"<script>alert('Designation Submitted Successfully');window.location='designation.php';</script>";

	}
	else
	{
		echo"<script>alert('Failed');window.location='designation.php';</script>";
	}
}
?>	
<div class="container">
  <div class="page-inner">	
	<div class="row">
	  <div class="col-md-12">
		<form action="" method="POST">
		<div class="card">
		  <div class="card-header">
			<div class="card-title">Designation</div>
		  </div>
		  <div class="card-body">
			<div class="row">
			  <div class="col-md-6 col-lg-4">
				<div class="form-group">
				  <label for="email2">Designation Code</label>
				  <input type="text" class="form-control" name="designation_code" placeholder="Enter Designation Code"/>
				</div>
			  </div>
			  <div class="col-md-6 col-lg-4">
				<div class="form-group">
				  <label for="email2">Designation Name</label>
				  <input type="text" class="form-control" name="designation_name" placeholder="Enter Designation Name"/>
				</div>
			  </div>
			</div>
		  </div>
		  <div class="card-action">
			<button class="btn btn-success" name="submit">Submit</button>
			<button class="btn btn-danger">Cancel</button>
		  </div>
		</div>
		</form>
	  </div>
	</div>
  </div>
</div>
		
<div class="container" style="margin-top:-150px;">
  <div class="page-inner">            
	<div class="row">
	  <div class="col-md-12">
		<div class="card">
		  <div class="card-header">
			<h4 class="card-title">Designation List</h4>
		  </div>
		  <div class="card-body">
			<div class="table-responsive">
			  <table id="basic-datatables" class="display table table-striped table-hover">
				<thead>
				  <tr>
					<th>Sr.No.</th>
					<th>Designation Code</th>
					<th>Designation Name</th>
					<th>Action</th>
				  </tr>
				</thead>
				<tfoot>
				  <tr>
					<th>Sr.No.</th>
					<th>Designation Code</th>
					<th>Designation Name</th>
					<th>Action</th>
				  </tr>
				</tfoot>
				<tbody>
				<?php
					$i=0;
					$res = mysqli_query($conn, "SELECT * FROM designation order by id desc");
					foreach($res as $keys => $values)
					{
						$i++;
				?>
				  <tr>
					<td><?php echo $i;?></td>
					<td><?php echo $values['designation_code'];?></td>
					<td><?php echo $values['designation_name'];?></td>
					<td><a href="#" class="btn btn-success">Edit</a> <a href="#" class="btn btn-danger">Delete</a></td>
				  </tr>
				  <?php
					}
				  ?>
				</tbody>
			  </table>
			</div>
		  </div>
		</div>
	  </div>
	</div>
  </div>
</div>
<?php
include_once("footer.php");
?>
        