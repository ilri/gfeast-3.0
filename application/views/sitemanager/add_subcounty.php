<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
		<div class="content-body" style="margin-bottom: 40px;">
			<div class="row">
				<div class="col-md-12">
					<!-- <a class="btn btn-success pull-right" href="<?php echo base_url();?><?php echo $this->uri->segment('1');?>/get_district">District List</a> -->
					<h3 class="title"><?php if(empty($this->uri->segment(3))){ echo 'Add';}else{ echo 'Edit';}?>&nbsp;Subcounty</h3>
					<a class="btn btn-md btn-round btn-success pull-right" href="<?php echo base_url(); ?><?php echo $this->uri->segment(1);?>/get_subcounty/<?php echo $subcounty[0]->county_id;?>">
            			<i class="fa fa-plus" aria-hidden="true"></i> Back
          			</a>
				</div>
				
				<div class="col-md-12 mt-10">
					<div class="card p-10">
						<div id="msg" style="color:green;text-align:center;text-align-last:center;"></div>
						<form  action="javascript:void(0);" id="form">
							<input type="hidden" name="subcounty_id" class="subcounty_id" value="<?php if(!empty($subcounty)){ echo $subcounty[0]->sub_county_id;}?>">
							<div class="row">
								<div class="col-md-4">
									<label>Select Country</label>
									<select name="county_id" class="form-control county" >
										<option value="<?php if(!empty($subcounty)){ echo $subcounty[0]->county_id;}?>"><?php if(!empty($subcounty[0]->name)){echo $subcounty[0]->name;}else{echo 'Select County';}?></option>
										<?php if(!empty($counties)) { 
											foreach($counties as $county){?>
												<option value="<?php echo $county->county_id;?>"><?php echo $county->name;?></option>
											<?php } 
										} ?>
									</select>
									<span class="county_err"></span>
								</div>
								<!-- <div class="col-md-4">
									<label>Select State</label>
									<select name="org_id" class="form-control state" style="text-align-last:center;">
										<option value="<?php if(!empty($districts)){ echo $districts[0]->state_id;}?>"><?php if(!empty($districts)){echo $districts[0]->state_name;}else{echo'Select State';}?></option>
									</select>
									<span class="state_err"></span>
								</div> -->
								<div class="col-md-4">
									<label>Subcounty Name</label>
									<input type="text" name="district_name" class="name form-control" placeholder="Enter Subcounty name here" value="<?php if(!empty($subcounty)){ echo $subcounty[0]->sub_county_name;}?>"  >
									<span class="name_err"></span>
								</div>
								<!-- <div class="col-md-4">
									<label>District Code</label>
									<input type="text" name="district_code"  class="code form-control" placeholder="Enter District code here" value="<?php if(!empty($districts)){echo $districts[0]->dist_code;}?>">
									<span class="code_err"></span>
								</div> -->
								<div class="col-md-4">
									<label>latitude</label>
									<input type="text" name="latitude"  class="latitude form-control" placeholder="Enter latitude here" value="<?php if(!empty($subcounty)){ echo $subcounty[0]->lat;}?>">
									<span class="latitude_err"></span>
								</div>
								<div class="col-md-4">
									<label>longitude</label>
									<input type="text" name="longitude"  class="longitude form-control" placeholder="Enter longitude here" value="<?php if(!empty($subcounty)){echo $subcounty[0]->lng;}?>">
									<span class="longitude_err"></span>
								</div>
								<div class="col-md-12">
									<input class="btn btn-success pull-right" type="submit" name="submit" id="submit" class="submit" value="submit">
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

		$('#submit').click(function(){
			var subcounty_id=$('.subcounty_id').val();
			var county_id=$('.county').val();
			// var state_id=$('.state').val();
			var subcounty_name=$('.name').val();
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
			// if(state_id == ""){
			// 	var error=error+1;
			// 	$('.state').css('border','1px solid red');
			// 	$('.state_err').html('Please select State').css('color','red');
			// }
			// else{
			// 	$('.state').css('border','1px solid green');
			// 	$('.state_err').html('');
			// }
			if(subcounty_name==""){
				var error=error+1;
				$('.name').css('border','1px solid red');
				$('.name_err').html('please enter subcounty name').css('color','red');
			}
			else{
				$('.name').css('border','1px solid green');
				$('.name_err').html('');
			}
			// if(code==""){
			// 	var error=error+1;
			// 	$('.code').css('border','1px solid red');
			// 	$('.code_err').html('please enter District code').css('color','red');
			// }
			// else{
			// 	$('.code').css('border','1px solid green');
			// 	$('.code_err').html('');
			// }
			if(county_id!=""  && subcounty_name!=""  && error=='0'){
				$.ajax({
					url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/addsubcounty",
					type:'post',
					dataType:'json',
					data:{
						subcounty_id:subcounty_id,
						county_id:county_id,
						// state_id:state_id,
						subcounty_name:subcounty_name,
						// code:code,
						latitude:latitude,
						longitude:longitude,
					},
					success:function(data){
						if(data.status=='1'){
							if(subcounty_id==""){ 
								$('#form').trigger("reset");
								$('#msg').removeAttr('style');
								$('#msg').html('<div class="alert alert-success"><strong>Success!</strong> Subcounty Inserted Successfully.</div>').delay(3000).fadeOut();
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

	});
</script>