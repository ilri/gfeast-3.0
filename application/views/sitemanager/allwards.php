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
          <h3 class="title">Add Ward</h3>
        </div>
        <div class="col-md-12 mt-10">
          <div class="card p-10">
            <div id="msg" style="color:green;text-align:center;text-align-last:center;"></div>
            <form  action="javascript:void(0);" id="form">
              <input type="hidden" name="county_id" class="ward_id" value="<?php if(!empty($wards)){ echo $wards[0]->county_id;}?>">
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



























      <div class="card shadow-none" style="margin-top:20px;margin-bottom:90px;">
        <div class="card-body">
          <a class="btn btn-md btn-round btn-success pull-right addward" href="javascript:void(0);">
            <i class="fa fa-plus " aria-hidden="true"></i> Ward
          </a>
          <h5 class="card-title">Ward Details</h5>
          <div class="table-responsive">
            <table class="table mb-0" id="test">
              <thead>
                <tr>
                  <th scope="col">Sl.NO</th>
                  <th scope="col">Ward Id</th>
                  <th scope="col">Ward Name</th>
                  <!-- <th scope="col">Country Code</th> -->
                  <th>Status</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php  foreach ($wards1 as $key =>  $ward) {?>
                  <tr>
                    <td class="text-truncate"><?php echo $key+1; ?></td>
                    <td class="text-truncate" ><?php echo $ward->ward_id;?></td>
                    <td class="text-truncate"><?php echo $ward->ward_name;?></td>
                   <!--  <td class="text-truncate"><?php echo $country->country_code; ?></td> -->
                    <td style="display: none;" class="status_<?php echo $ward->ward_id;?>">
                      <input type="hidden" name="status" class="status" value="<?php echo $ward->status;?>">
                      <?php if($ward->status=='1'){?>
                        <i style="font-size:25px;color:green;" class="fa fa-check"></i>
                      <?php }else{ ?>
                        <i style="font-size:25px;color:red" class="fa fa-close"></i>
                      <?php } ?>
                    </td>
                    <td class="text-truncate" class="status2_<?php echo $ward->ward_id;?>">
                      <?php if($ward->status=='1'){?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $ward->ward_id;?>">
                        <i style="font-size:25px;color:green" class="togg<?php echo $ward->ward_id;?> <?php echo "fa fa-toggle-on"; ?>"></i>
                      </a><?php }else{ ?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $ward->ward_id;?>">
                        <i style="font-size:25px;color: red" class="togg<?php echo $ward->ward_id;?> <?php echo "fa fa-toggle-off"; ?>"></i>
                      </a><?php } ?>
                    </td>
                    <td>
                      <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/ward_edit/<?php echo $ward->ward_id;?>" class="edit" id="<?php echo $ward->ward_id;?>">
                        <i class="fa fa-edit" style="font-size:25px;"></i>
                      </a>
                      <!-- <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/get_ward/<?php echo $ward->county_id;?>">
                        <i class="fa fa-list" style="font-size:25px;"></i>
                      </a> -->
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
      var ward_id=$(this).attr('id');
      var ward_status=$(this).parent().prev().find('.status').val();
      if(ward_id!="" && ward_status!=""){
        $.ajax({
          url : '<?php echo base_url();?><?php echo $this->uri->segment(1);?>/ward_delete',
          type : 'POST',
          dataType:'json',
          data : {
            ward_id:ward_id,
            status:ward_status,
          },
          success : function(data){	
            if(data.cstatus==1){
              $('.status_'+ward_id).html("");
              $('#test tbody').find('.status_'+ward_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:green;" class="fa fa-check"></i>');
               $(".togg"+ward_id).removeClass('fa fa-toggle-off');
              $('.togg'+ward_id).addClass('fa fa-toggle-on "togg'+ward_id+'"').css("color","green");
              // location.reload();
            }
            else{
              $('.status_'+ward_id).html("");
              $('#test tbody').find('.status_'+ward_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:red;" class="fa fa-close"></i>');
              $(".togg"+ward_id).removeClass('fa fa-toggle-on');
              $('.togg'+ward_id).addClass('fa fa-toggle-off "togg'+ward_id+'"').css("color","red");
              // location.reload();
            }
          },
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
    $('#submit').click(function(){ 
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
        $('.subcounty_err').html('Please select Subcounty').css('color','red');
      }
      else{
        $('.subcounty').css('border','1px solid green');
        $('.subcounty_err').html('');
      }
      if(ward_name==""){
        var error=error+1;
        $('.name').css('border','1px solid red');
        $('.name_err').html('please enter Ward name').css('color','red');
      }
      else{
        $('.name').css('border','1px solid green');
        $('.name_err').html('');
      }
      // if(code==""){
      //  var error=error+1;
      //  $('.code').css('border','1px solid red');
      //  $('.code_err').html('please enter District code').css('color','red');
      // }
      // else{
      //  $('.code').css('border','1px solid green');
      //  $('.code_err').html('');
      // }
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

    $('.addward').click(function(){
          $('.add').show();

      });

  });
</script> 
