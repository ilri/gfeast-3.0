<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
	.btn{
		margin-top: 30px;
    	margin-bottom:30px;
	}
</style>
<div class="app-content content" style="margin-left: 0px;">
  <div class="content-wrapper">
    <div class="content-body">
      <div class="row add">
        <div class="col-md-12">
          <h3 class="title">Add Event Type</h3>
        </div>
        <div class="col-md-12 mt-10">
          <div class="card p-10">
            <div id="msg" style="color:green;text-align:center;text-align-last:center;"></div>
            <form  action="javascript:void(0);" id="form">
                <div class="col-md-4">
                  <label>Select Training Type</label>
                  <select name="county_id" class="form-control type" id="type" >
                        <option> Select Training Type</option>
                      <?php if(!empty($types)) { 
                      foreach($types as $type){?>
                        <option value="<?php echo $type->id;?>"><?php echo $type->name;?></option>
                      <?php } 
                    } ?>
                  </select>
                  <span class="county_err"></span>
                </div>
                <div class="col-md-4">
                  <label>Select Training specifics</label>
                 <select name="county_id" class="form-control specifics" id="specificstype" >
                  <option> Select Training specifics</option>
                    <?php if(!empty($specifics)) { 
                      foreach($specifics as $specifics){?>
                        <option value="<?php echo $specifics->id;?>"><?php echo $specifics->name;?></option>
                      <?php } 
                    } ?>
                  </select>
                  <span class="subcounty_err"></span>
                </div>
                <div class="col-md-4">
                  <label>Event Type Name</label>
                  <input type="text" name="district_name" class="name form-control" placeholder="Enter Event name here" value="<?php if(!empty($wards)){ echo $wards[0]->ward_name;}?>"  >
                  <span class="name_err"></span>
                </div>
                <div class="col-md-12">
                  <input class="btn btn-success pull-right" type="submit" name="submit" id="submit" class="submit" value="submit">
                </div>
              </div>            
             </form> 
          </div>
        </div>
      </div>
      <div class="card shadow-none" style="margin-top:20px;margin-bottom:90px;">
        <div class="card-body">
          <a class="btn btn-md btn-round btn-success pull-right addeventtype" href="javascript:void(0);">
            <i class="fa fa-plus addeventtype" aria-hidden="true"></i> Add Event Type
          </a>
          <h5 class="card-title">Event Type Details</h5>
          <div class="table-responsive">
            <table class="table mb-0" id="test">
              <thead>
                <tr>
                  <th scope="col">Sl.NO</th>
                  <th scope="col">ID</th>
                  <th scope="col">Training Type Name</th>
                  <th scope="col">Training Specific Name</th>
                  <th scope="col">Name</th>
                   <th scope="col">Added On</th>
                  <th>Status</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php  foreach ($eventtype as $key =>  $eventtype) { ?>
                  <tr>
                    <td class="text-truncate"><?php echo $key+1; ?></td>
                    <td class="text-truncate" ><?php echo $eventtype->id;?></td>
                    <td class="text-truncate" ><?php echo $eventtype->typename;?></td>
                    <td class="text-truncate" ><?php echo $eventtype->specificname;?></td>
                    <td class="text-truncate" ><?php echo $eventtype->name;?></td>
                    <td class="text-truncate" ><?php echo $eventtype->added_datetime;?></td>
                    <td style="display: none;"class="status_<?php echo $eventtype->id;?>">
                      <input type="hidden" name="status" class="status" value="<?php echo $eventtype->status;?>">
                      <?php if($eventtype->status=='1'){?>
                        <i style="font-size:25px;color:green;" class="fa fa-check"></i>
                      <?php }else{ ?>
                        <i style="font-size:25px;color:red" class="fa fa-close"></i>
                      <?php } ?>
                    </td>
                    <td class="text-truncate" class="status2_<?php echo $eventtype->id;?>">
                      <?php if($eventtype->status=='1'){?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $eventtype->id;?>">
                        <i style="font-size:25px;color:green" class="togg<?php echo $eventtype->id;?> <?php echo "fa fa-toggle-on"; ?>"></i>
                      </a><?php }else{ ?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $eventtype->id;?>">
                        <i style="font-size:25px;color: red" class="togg<?php echo $eventtype->id;?> <?php echo "fa fa-toggle-off"; ?>"></i>
                      </a><?php } ?>
                    </td>
                    <td>
                      <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/eventtype_edit/<?php echo $eventtype->id;?>" class="edit" id="<?php echo $eventtype->id;?>">
                        <i class="fa fa-edit" style="font-size:25px;"></i>
                      </a>
                     <!--  <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/get_subeventtype/<?php echo $eventtype->id;?>">
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
      var eventtype_id=$(this).attr('id');
      var eventtype_status=$(this).parent().prev().find('.status').val();
      if(eventtype_id!="" && eventtype_status!=""){
        $.ajax({
          url : '<?php echo base_url();?><?php echo $this->uri->segment(1);?>/eventtype_delete',
          type : 'POST',
          dataType:'json',
          data : {
            id:eventtype_id,
            eventtype_status:eventtype_status,
          },
          success : function(data){	
            if(data.cstatus==1){
              $('.status_'+eventtype_id).html("");
              $('#test tbody').find('.status_'+eventtype_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:green;" class="fa fa-check"></i>');
              $(".togg"+eventtype_id).removeClass('fa fa-toggle-off');
              $('.togg'+eventtype_id).addClass('fa fa-toggle-on "togg'+eventtype_id+'"').css("color","green");
            }
            else{
              $('.status_'+eventtype_id).html("");
              $('#test tbody').find('.status_'+eventtype_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:red;" class="fa fa-close"></i>');
              $(".togg"+eventtype_id).removeClass('fa fa-toggle-on');
              $('.togg'+eventtype_id).addClass('fa fa-toggle-off "togg'+eventtype_id+'"').css("color","red");
            }
          },
        });
      }
    });
    $('.addeventtype').click(function(){
      $('.add').show();
      window.scrollTo({ top: 0, behavior: 'smooth' });

    });
    $('#submit').click(function(){
      var eventtype_id=$('.eventtype_id').val();
      var type_id=$('.type').val(); 
      var specific_id=$('.specifics').val();
      var type_name=$('.name').val();
      var error=0;
      if(type_name==""){
        var error=error+1;
        $('.name').css('border','1px solid red');
        $('.name_err').html('please enter event type name').css('color','red');
      }
      else
      {
        $('.name').css('border','1px solid green');
        $('.name_err').html("");
      }
      if(type_name!="" && error==0){
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/addeventtype",
          type:'post',
          dataType:'json',
          data:{
            eventtype_id:eventtype_id,
            trainingtype_id:type_id,
            trainingspecifics_id:specific_id,
            eventtype_name:type_name,
          },
          success:function(data){
            if(data.status=='1'){
              $('#form').trigger("reset");
              $("#msg").removeAttr("style");
              if(eventtype_id==""){
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong> Event Type Inserted Successfully.</div>').delay(3000).fadeOut();
              }
              else{
                $("#msg").removeAttr('style');
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong>Event Type Updated Successfully.</div>').delay(3000).fadeOut();
              }
            }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
            if(county_id==""){
              $('#msg').html("Unable to Insert Event");
            }
            else{
              $('#msg').html("Unable to Update Event");
            }
          }
        });
      }
    });
    $('.type').change(function(){
      var type_id=$('.type').val();
      if(type_id!=""){
          $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/get_specificsby_type",
          type:'post',
          dataType:'json',
          data:{
            type_id:type_id,
          },
          success:function(data){
                if(data.status==1){
            var options='<option value="">Select Training Specifics</option>';
            data.specifics.forEach(function(specifics,index){
              options += '<option value="' + specifics.id + '">' + specifics.name + '</option>';
            });
            $('.specifics').html(options);
          }
       
          },
    });
     }
     });
  });
</script>