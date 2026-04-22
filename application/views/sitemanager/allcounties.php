<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
	.btn{
		margin-top: 30px;
    	margin-bottom:30px;
	}
	th, td{
		text-align: center;
	}
</style>

<div class="app-content content" style="margin-left: 0px;">
  <div class="content-wrapper">
    <div class="content-body">

        <div class="row add">
        <div class="col-md-12">
          <!-- <a class="btn btn-success pull-right" href="<?php echo base_url();?><?php echo $this->uri->segment('1');?>/get_country">Countries List</a> -->
          <h3 class="title"><?php if(!empty($this->uri->segment(3))){ echo 'Edit';}else{ echo 'Add';}?> County</h3>
        </div>
        
        <div class="col-md-12 mt-10 add">
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
    <div class="card shadow-none" style="margin-top:20px;margin-bottom:90px;">
        <div class="card-body">
          <a class="btn btn-md btn-round btn-success pull-right addcounty" href="javascript:void(0);">
            <i class="fa fa-plus addcounty" aria-hidden="true"></i> County
          </a>
          <h5 class="card-title">Country Details</h5>
          <div class="table-responsive">
            <table class="table mb-0" id="test">
              <thead>
                <tr>
                  <th scope="col">Sl.NO</th>
                  <th scope="col">County Id</th>
                  <th scope="col">County Name</th>
                  <!-- <th scope="col">Country Code</th> -->
                  <th>Status</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php  foreach ($counties as $key =>  $county) {?>
                  <tr>
                    <td class="text-truncate"><?php echo $key+1; ?></td>
                    <td class="text-truncate" ><?php echo $county->county_id;?></td>
                    <td class="text-truncate"><?php echo $county->name;?></td>
                   <!--  <td class="text-truncate"><?php echo $country->country_code; ?></td> -->
                    <td style="display: none;" class="status_<?php echo $county->county_id;?>">
                      <input type="hidden" name="status" class="status" value="<?php echo $county->status;?>">
                      <?php if($county->status=='1'){?>
                        <i style="font-size:25px;color:green;" class="fa fa-check"></i>
                      <?php }else{ ?>
                        <i style="font-size:25px;color:red" class="fa fa-close"></i>
                      <?php } ?>
                    </td>
                    <td class="text-truncate" class="status2_<?php echo $county->county_id;?>">
                      <?php if($county->status=='1'){?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $county->county_id;?>">
                        <i style="font-size:25px;color:green" class="togg<?php echo $county->county_id;?> <?php echo "fa fa-toggle-on"; ?>"></i>
                      </a><?php }else{ ?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $county->county_id;?>">
                        <i style="font-size:25px;color: red" class="togg<?php echo $county->county_id;?> <?php echo "fa fa-toggle-off"; ?>"></i>
                      </a><?php } ?>
                    </td>
                    <td>
                      <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/county_edit/<?php echo $county->county_id;?>" class="edit" id="<?php echo $county->county_id;?>">
                        <i class="fa fa-edit" style="font-size:25px;"></i>
                      </a>
                      <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/get_subcounty/<?php echo $county->county_id;?>">
                        <i class="fa fa-list" style="font-size:25px;"></i>
                      </a>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script>
  $(function(){
    $('.add').hide();
    $('.delete').click(function(){
      var county_id=$(this).attr('id');
      var county_status=$(this).parent().prev().find('.status').val();
      if(county_id!="" && county_status!=""){
        $.ajax({
          url : '<?php echo base_url();?><?php echo $this->uri->segment(1);?>/county_delete',
          type : 'POST',
          dataType:'json',
          data : {
            county_id:county_id,
            county_status:county_status,
          },
          success : function(data){	
            if(data.cstatus==1){
              $('.status_'+county_id).html("");
              $('#test tbody').find('.status_'+county_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:green;" class="fa fa-check"></i>');
              $(".togg"+county_id).removeClass('fa fa-toggle-off');
              $('.togg'+county_id).addClass('fa fa-toggle-on "togg'+county_id+'"').css("color","green");
            }
            else{
              $('.status_'+county_id).html("");
              $('#test tbody').find('.status_'+county_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:red;" class="fa fa-close"></i>');
              $(".togg"+county_id).removeClass('fa fa-toggle-on');
              $('.togg'+county_id).addClass('fa fa-toggle-off "togg'+county_id+'"').css("color","red");
            }
          },
        });
      }
    });
    $('.addcounty').click(function(){
      $('.add').show();

    });
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
