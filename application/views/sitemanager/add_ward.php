<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
		<div class="content-body" style="margin-bottom: 40px;">

			<div class="row add">
				<div class="col-md-12">
					<h3 class="title"><?php if(empty($this->uri->segment(3))){ echo 'Add';}else{ echo 'Edit';}?>&nbsp;Ward</h3>

					<a class="btn btn-md btn-round btn-success pull-right" href="<?php echo base_url(); ?><?php echo $this->uri->segment(1);?>/get_ward/<?php echo $wards[0]->sub_county_id;?>">
            			<i class="fa fa-plus" aria-hidden="true"></i> Back
          			</a>
				</div>
				<div class="col-md-12 mt-10">
					<div class="card p-10">
						<div id="msg" style="color:green;text-align:center;text-align-last:center;"></div>
						<form  action="javascript:void(0);" id="form">
							<input type="hidden" name="ward_id" class="ward_id" value="<?php if(!empty($wards)){ echo $wards[0]->ward_id;}?>">
							<div class="row">
								<div class="col-md-4">
									<label>Select Country</label>
									<select name="county_id" class="form-control county" >
										<option value="<?php if(!empty($wards)){ echo $wards[0]->county_id;}?>"><?php if(!empty($wards)){echo $wards[0]->name;}else{echo 'Select County';}?></option>
										<?php if(!empty($counties)) { 
											foreach($counties as $county){?>
												<option value="<?php echo $county->county_id;?>"><?php echo $county->name;?></option>
											<?php } 
										} ?>
									</select>
									<span class="county_err"></span>
								</div>
								<div class="col-md-4">
									<label>Select Subcounty</label>
									<select name="org_id" class="form-control subcounty">
										<option value="<?php if(!empty($wards)){ echo $wards[0]->sub_county_id;}?>"><?php if(!empty($wards)){echo $wards[0]->sub_county_name;}else{echo'Select Subcounty';}?></option>
									</select>
									<span class="subcounty_err"></span>
								</div>
								<div class="col-md-4">
									<label>Ward Name</label>
									<input type="text" name="district_name" class="name form-control" placeholder="Enter ward name here" value="<?php if(!empty($wards)){ echo $wards[0]->ward_name;}?>"  >
									<span class="name_err"></span>
								</div>
								<!-- <div class="col-md-4">
									<label>District Code</label>
									<input type="text" name="district_code"  class="code form-control" placeholder="Enter District code here" value="<?php if(!empty($districts)){echo $districts[0]->dist_code;}?>">
									<span class="code_err"></span>
								</div> -->
								<div class="col-md-4">
									<label>latitude</label>
									<input type="text" name="latitude"  class="latitude form-control" placeholder="Enter latitude here" value="<?php if(!empty($wards)){ echo $wards[0]->lat;}?>">
									<span class="latitude_err"></span>
								</div>
								<div class="col-md-4">
									<label>longitude</label>
									<input type="text" name="longitude"  class="longitude form-control" placeholder="Enter longitude here" value="<?php if(!empty($wards)){echo $wards[0]->lng;}?>">
									<span class="longitude_err"></span>
								</div>
								<div class="col-md-12">
									<input class="btn btn-success pull-right submit" type="submit" name="submit" id="submit"  value="submit">
								</div>
							</div>						
						 </form> 
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(function(){
		// $('.add').hide();

		$('#submit').click(function(){ alert();
			var subcounty_id=$('.subcounty').val();
			var county_id=$('.county').val();
			
			var ward_id=$('.ward_id').val();
			var ward_name=$('.name').val();
			// var code=$('.code').val();
			var latitude=$('.latitude').val();
			var longitude=$('.longitude').val();
			var error='0';
			if(county_id == ""){
				var error=error+1;
				$('.county').css('border','1px solid red');
				$('.county_err').html('Please select county').css('color','red');
			}
			else{
				$('.county').css('border','1px solid green');
				$('.county_err').html('');
			}
			if(subcounty_id == ""){
				var error=error+1;
				$('.subcounty').css('border','1px solid red');
				$('.subcounty_err').html('Please select State').css('color','red');
			}
			else{
				$('.subcounty').css('border','1px solid green');
				$('.subcounty_err').html('');
			}
			if(ward_name==""){
				var error=error+1;
				$('.name').css('border','1px solid red');
				$('.name_err').html('please enter subcounty name').css('color','red');
			}
			else{
				$('.name').css('border','1px solid green');
				$('.name_err').html('');
			}

			if(county_id!=""  && subcounty_id!="" && ward_name!="" && error=='0'){
				$.ajax({
					url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/addward",
					type:'post',
					dataType:'json',
					data:{
						subcounty_id:subcounty_id,
						county_id:county_id,
						ward_id:ward_id,
						ward_name:ward_name,
						latitude:latitude,
						longitude:longitude,
					},
					success:function(data){
						if(data.status=='1'){
							if(ward_id==""){ 
								$('#form').trigger("reset");
								$('#msg').removeAttr('style');
								$('#msg').html('<div class="alert alert-success"><strong>Success!</strong> Ward Inserted Successfully.</div>').delay(3000).fadeOut();
							}
							else{
								$("#msg").removeAttr('style');
								$('#msg').html('<div class="alert alert-success"style="text-align:center"><strong>Success!</strong> Subcounty Updated Successfully.</div>').delay(3000).fadeOut();
							}
						}
						else
						{
							$('#msg').html('<div class="alert alert-success"style="text-align:center"><strong>Success!</strong>'+data.msg+'</div>').delay(3000).fadeOut();
						}
				},
				error:function(data){
						$('#msg').html("Unable to Insert state");
					}
				});
			}
		});


		$('.county').change(function(){
			var county_id=$('.county').val();
			if(county_id!=""){
				$.ajax({
					url : '<?php echo base_url();?><?php echo $this->uri->segment(1);?>/get_subcountyby_county',
					type : 'POST',
					dataType:'json',
					data : {
						county_id:county_id,
					},
					success : function(data)
					{ 
						if(data.status==1){
						var options='<option value="">Select Subcounty</option>';
						data.subcounty.forEach(function(subcounty,index){
							options += '<option value="' + subcounty.sub_county_id + '">' + subcounty.sub_county_name + '</option>';
						});
						$('.subcounty').html(options);
					}
					else
					{
						$('#msg').html('<div class="alert alert-danger"style="text-align:center">'+data.msg+'</div>');
					}
					},
					error:function(data){
						$('#msg').html("Unable to get state");
					}
				});
			}
			else
			{
				var options='<option value="">Select Subcounty</option>';
				$('.subcounty').html(options);
			}
		});

	});
</script>