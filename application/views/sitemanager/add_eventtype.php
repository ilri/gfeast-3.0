<div class="app-content content" style="margin-left: 0px;">
  <div class="content-wrapper">
    <div class="content-body" style="margin-bottom: 40px;">
      <div class="row">
        <div class="col-md-12">
          <a class="btn btn-md btn-round btn-success pull-right " href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/get_eventtype">
            <i class="fa fa-left" aria-hidden="true"></i> Back
          </a>
          <h3 class="title"><?php if(!empty($this->uri->segment(3))){ echo 'Edit';}else{ echo 'Add';}?> Event Type</h3>
        </div>
        <?php //var_dump($type['tsname']);die(); ?>
        <div class="col-md-12 mt-10">
          <div class="card p-10">
            <div id="msg" style="color:green;text-align: center;"></div>
            <form  action="javascript:void(0);" id="form">
              <input type="hidden" name="eventtype_id" class='eventtype_id' value="<?php if(!empty($eventtype)){echo $eventtype['id'];}?>">
              <div class="row">
                <div class="col-md-3">
                  <label>Name</label>
                  <input type="text" name="eventtype_name" class="name form-control" placeholder="Enter eventtype name here"  value="<?php if(!empty($eventtype)){ echo $eventtype['ename'];}?>">
                  <span class="name_err"></span>
                </div>
                <div class="col-md-4">
                  <label>Training Type Name</label>
                   <select name="trainingtype" id="<?php echo $eventtype['tid']; ?>" 
                    class="tname form-control" >
                     <option value="<?php if(!empty($types)){ echo $types[0]->id;}?>"><?php if(!empty($types)){echo $types[0]->name;}else{echo 'Select Training Type';}?></option>
                    <?php if(!empty($types)) { 
                      foreach($types as $type){?>
                        <option value="<?php echo $type->id;?>"><?php echo $type->name;?></option>
                      <?php } 
                    } ?>
                  </select>
                  <span class="tname_err"></span>
                </div>
                <div class="col-md-3">
                  <label>Training Specific Name</label>
                   <select name="trainingspecific" id="<?php echo $eventtype['tsid']; ?>" 
                    class="tsname form-control" >
                    <option value="<?php if(!empty($specifics)){ echo $specifics[0]->id;}?>"><?php if(!empty($specifics)){echo $specifics[0]->name;}else{echo 'Select Training Type';}?></option>
                    <?php if(!empty($specifics)) { 
                      foreach($specifics as $specifics){?>
                        <option value="<?php echo $specifics->id;?>"><?php echo $specifics->name;?></option>
                      <?php } 
                    } ?>
                  </select>
                  <span class="tsname_err"></span>
                </div>
              <!--   <div class="col-md-3">
                  <label>longitude</label>
                  <input type="text" name="longitude"  class="longitude form-control" placeholder="Enter longitude here" value="<?php if(!empty($eventtype)){ $eventtype[0]->lng;}?>" >
                  <span class="longitude_err"></span>
                </div> -->
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
      var eventtype_id=$('.eventtype_id').val(); 
      var eventtype_name=$('.name').val();
      var trainingtype_id=$('.tname').val();
      var trainingspecifics_id=$('.tsname').val();
      var error=0;
      if(eventtype_name==""){
        var error=error+1;
        $('.name').css('border','1px solid red');
        $('.name_err').html('please enter name').css('color','red');
        $('.tname').css('border','1px solid red');
        $('.tname_err').html('please enter name').css('color','red');
        $('.tsname').css('border','1px solid red');
        $('.tsname_err').html('please enter name').css('color','red');
      }
      else
      {
        $('.name').css('border','1px solid green');
        $('.name_err').html("");
        $('.tname').css('border','1px solid green');
        $('.tname_err').html("");
        $('.tsname').css('border','1px solid green');
        $('.tsname_err').html("");
      }
      if(eventtype_name!="" && error==0){
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/addeventtype",
          type:'post',
          dataType:'json',
          data:{
            eventtype_id:eventtype_id,
            trainingtype_id:trainingtype_id,
            trainingspecifics_id:trainingspecifics_id,
            eventtype_name:eventtype_name,
          },
          success:function(data){
            if(data.status==1){
              $('#form').trigger("reset");
              $("#msg").removeAttr("style");
              if(eventtype_id==""){
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong> Event Inserted Successfully.</div>').delay(3000).fadeOut();
              }
              else{
                
                $("#msg").removeAttr('style');
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong> Event Updated Successfully.</div>').delay(3000).fadeOut();
              }
            }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
           if(eventtype_id==""){
             $('#msg').html("Unable to Insert Event Type Name");
           }
           else{
             $('#msg').html("Unable to Update Event Type Name");
           }
          }
        });
      }
    });
  });
</script> 
    });
  });
</script> 