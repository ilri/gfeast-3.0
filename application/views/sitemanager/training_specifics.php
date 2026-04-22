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
          <h3 class="title">Add Training Specifics</h3>
        </div>
        <div class="col-md-12 mt-10">
          <div class="card p-10">
            <div id="msg" style="color:green;text-align:center;text-align-last:center;"></div>
            <form  action="javascript:void(0);" id="form">
              <input type="hidden" name="ward_id" class="specific_id">
              <div class="row">
                <div class="col-md-4">
                  <label>Select Training Type</label>
                  <select name="county_id" class="form-control type" id="type" >
                    <option value="<?php if(!empty($wards)){ echo $wards[0]->county_id;}?>"><?php if(!empty($wards)){echo $wards[0]->name;}else{echo 'Select Training Type';}?></option>
                    <?php if(!empty($types)) { 
                      foreach($types as $type){?>
                        <option value="<?php echo $type->id;?>"><?php echo $type->name;?></option>
                      <?php } 
                    } ?>
                  </select>
                  <span class="trainingtype_err"></span>
                </div>
                <div class="col-md-4">
                  <label>Training Specific Name</label>
                  <input type="text" name="district_name" class="name form-control" placeholder="Enter Training Specific name here" value="<?php if(!empty($wards)){ echo $wards[0]->ward_name;}?>"  >
                  <span class="name_err"></span>
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
            <i class="fa fa-plus addcounty" aria-hidden="true"></i> Training Specifics
          </a>
          <h5 class="card-title">Training Specifics</h5>
          <div class="table-responsive">
            <table class="table mb-0" id="test">
              <thead>
                <tr>
                  <th scope="col">Sl.NO</th>
                  <th scope="col">Training Type</th>
                  <th scope="col">Name</th>
                  <th>Status</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php  foreach ($specifics as $key =>  $specific) {?>
                  <tr>
                    <td class="text-truncate"><?php echo $key+1; ?></td>
                    <td class="text-truncate" ><?php echo $specific->type_name;?></td>
                    <td class="text-truncate" ><?php echo $specific->name;?></td>
                    <td style="display: none;" class="status_<?php echo $specific->id;?>">
                      <input type="hidden" name="status" class="status" value="<?php echo $specific->status;?>">
                      <?php if($specific->status=='1'){?>
                        <i style="font-size:25px;color:green;" class="fa fa-check"></i>
                      <?php }else{ ?>
                        <i style="font-size:25px;color:red" class="fa fa-close"></i>
                      <?php } ?>
                    </td>
                    <td class="text-truncate" class="status2_<?php echo $specific->id;?>">
                      <?php if($specific->status=='1'){?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $specific->id;?>">
                        <i style="font-size:25px;color:green" class="togg<?php echo $specific->id;?> <?php echo "fa fa-toggle-on"; ?>"></i>
                      </a><?php }else{ ?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $specific->id;?>">
                        <i style="font-size:25px;color: red" class="togg<?php echo $specific->id;?> <?php echo "fa fa-toggle-off"; ?>"></i>
                      </a><?php } ?>
                    </td>
                    <td>
                      <a href="javascript:void(0);" class="edit" data-typeid="<?php echo $specific->trainingtype_id;?>" id="<?php echo $specific->id;?>">
                        <i class="fa fa-edit" style="font-size:25px;"></i>
                      </a>
                    <!--   <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/get_subcounty/<?php echo $specific->id;?>">
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
      var specific_id=$(this).attr('id');
      var specific_status=$(this).parent().prev().find('.status').val();
      if(specific_id!="" && specific_status!=""){
        $.ajax({
          url : '<?php echo base_url();?><?php echo $this->uri->segment(1);?>/specific_delete',
          type : 'POST',
          dataType:'json',
          data : {
           specific_id:specific_id,
           specific_status:specific_status,
          },
          success : function(data){	
            if(data.cstatus==1){
              $('.status_'+specific_id).html("");
              $('#test tbody').find('.status_'+specific_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:green;" class="fa fa-check"></i>');
              $(".togg"+specific_id).removeClass('fa fa-toggle-off');
              $('.togg'+specific_id).addClass('fa fa-toggle-on togg'+specific_id+'').css("color","green");
            }
            else{
              $('.status_'+specific_id).html("");
              $('#test tbody').find('.status_'+specific_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:red;" class="fa fa-close"></i>');
              $(".togg"+specific_id).removeClass('fa fa-toggle-on');
              $('.togg'+specific_id).addClass('fa fa-toggle-off togg'+specific_id+'').css("color","red");
            }
          },
        });
      }
    });
    $('.addcounty').click(function(){
      $('.add').show();
      window.scrollTo({ top: 0, behavior: 'smooth' });

    });
    $('#submit').click(function(){
      var specific_id=$('.specific_id').val(); 
      var type_id=$('.type').val();
      var specific_name=$('.name').val();
      var error=0;
      if(specific_name==""){
        var error=error+1;
        $('.name').css('border','1px solid red');
        $('.name_err').html('please enter country name').css('color','red');
      }
      else
      {
        $('.name').css('border','1px solid green');
        $('.name_err').html("");
      }
      if(type_id==""){
        var error=error+1;
        $('.type').css('border','1px solid red');
        $('.trainingtype_err').html('please select training type').css('color','red');
      }
      else
      {
        $('.type').css('border','1px solid green');
        $('.trainingtype_err').html("");
      }
      if(specific_name!="" && type_id!="" && error=='0'){
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/addtraining_specific",
          type:'post',
          dataType:'json',
          data:{
            specific_id:specific_id,
            type_id:type_id,
            specific_name:specific_name,
          },
          success:function(data){
            if(data.status=='1'){
              $('#form').trigger("reset");
              $("#type").html("");
              $("#msg").removeAttr("style");
              if(specific_id==""){
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong> Training Specific Inserted Successfully.</div>').delay(3000).fadeOut();
              }
              else{
                $("#msg").removeAttr('style');
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong>Training specific Updated Successfully.</div>').delay(3000).fadeOut();
              }
            }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
            if(county_id==""){
              $('#msg').html("Unable to Insert Training Specific");
            }
            else{
              $('#msg').html("Unable to Update Training Specific");
            }
          }
        });
      }
    });
    $('.edit').click(function(){
      var specific_id=$(this).attr('id');
      var typeid=$(this).data('typeid');
      $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/edittraining_specific",
          type:'post',
          dataType:'json',
          data:{
            specific_id:specific_id,
          },
          success:function(data){
            
            if(data.status=='1'){
              // var sel_type_id="";
              var options="<option>-select Training Type- </option>";
                data.specifics.forEach(function(specifics,index){
                  $('.name').val(specifics.name);
                  $('.specific_id').val(specifics.id);

                });
                data.trainingtypes.forEach(function(trainingtypes,index){
                  if(typeid==trainingtypes.id)
                  {
                    var select_val='selected';
                }else{
                  var select_val='';   
                  }            
                    options +='<option value="'+trainingtypes.id+'" '+select_val+'>'+trainingtypes.name+'</option>';
                });
                $('.type').html(options);
                $('.add').show();
                window.scrollTo({ top: 0, behavior: 'smooth' });
              }
      
            },
          error:function(data){
            if( specific_id==""){
              $('#msg').html("Unable to Insert Training Specific");
            }
            else{
              $('#msg').html("Unable to Update Training Specific");
            }
          }
        });
    });
  });
</script>
