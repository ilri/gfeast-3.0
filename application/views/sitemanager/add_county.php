<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
		<div class="content-body" style="margin-bottom: 40px;">
			<div class="row">
				<div class="col-md-12">
					<!-- <a class="btn btn-success pull-right" href="<?php echo base_url();?><?php echo $this->uri->segment('1');?>/get_country">Countries List</a> -->
					<h3 class="title"><?php if(!empty($this->uri->segment(3))){ echo 'Edit';}else{ echo 'Add';}?> County</h3>
				</div>
				
				<div class="col-md-12 mt-10">
					<div class="card p-10">
						<div id="msg" style="color:green;text-align: center;"></div>
						<form  action="javascript:void(0);" id="form">
							<input type="hidden" name="county_id" class='county_id' value="<?php if(!empty($county)){echo $county[0]->county_id;}?>">
							<div class="row">
								<div class="col-md-3">
									<label>Country Name</label>
									<input type="text" name="county_name" class="name form-control" placeholder="Enter County name here"  value="<?php if(!empty($county)){ echo $county[0]->name;}?>">
									<span class="name_err"></span>
								</div>
								<!-- <div class="col-md-4">
									<label>Country Code</label>
									<input type="text" name="country_code"  class="code form-control" placeholder="Enter country code here" value="<?php if(!empty($country)){ echo $country[0]->country_code;}?>">
									<span class="code_err"></span>
								</div> -->
								<div class="col-md-3">
									<label>latitude</label>
									<input type="text" name="latitude"  class="latitude form-control" placeholder="Enter latitude here" value="<?php if(!empty($county)){ $county[0]->lat;}?>"><span class="latitude_err"></span>
								</div>
								<div class="col-md-3">
									<label>longitude</label>
									<input type="text" name="longitude"  class="longitude form-control" placeholder="Enter longitude here" value="<?php if(!empty($county)){ $county[0]->lng;}?>" >
									<span class="longitude_err"></span>
								</div>
								<div class="col-md-1">
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
			var county_id=$('.county_id').val(); 
			var county_name=$('.name').val();
			var latitude=$('.latitude').val();
			var longitude=$('.longitude').val();
			var error=0;
			if(county_name==""){
				var error=error+1;
				$('.name').css('border','1px solid red');
				$('.name_err').html('please enter country name').css('color','red');
			}
			else
			{
				$('.name').css('border','1px solid green');
				$('.name_err').html("");
			}
			if(county_name!="" && error=='0'){
				$.ajax({
					url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/addcounty",
					type:'post',
					dataType:'json',
					data:{
						county_id:county_id,
						county_name:county_name,
						latitude:latitude,
						longitude:longitude,
					},
					success:function(data){
						if(data.status=='1'){
							$('#form').trigger("reset");
							$("#msg").removeAttr("style");
							if(county_id==""){
								$('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong> County Inserted Successfully.</div>').delay(3000).fadeOut();
							}
							else{
								$("#msg").removeAttr('style');
								$('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong>County Updated Successfully.</div>').delay(3000).fadeOut();
							}
						}
						else{
							$('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
						}
					},
					error:function(data){
						if(county_id==""){
							$('#msg').html("Unable to Insert county");
						}
						else{
							$('#msg').html("Unable to Update county");
						}
					}
				});
			}
		});
	});
</script> 