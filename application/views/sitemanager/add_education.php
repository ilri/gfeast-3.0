<div class="app-content content" style="margin-left: 0px;">
  <div class="content-wrapper">
    <div class="content-body" style="margin-bottom: 40px;">
      <div class="row">
        <div class="col-md-12">
          <!-- <a class="btn btn-success pull-right" href="<?php echo base_url();?><?php echo $this->uri->segment('1');?>/get_country">Countries List</a> -->
          <h3 class="title"><?php if(!empty($this->uri->segment(3))){ echo 'Edit';}else{ echo 'Add';}?> Education</h3>
        </div>
        
        <div class="col-md-12 mt-10">
          <div class="card p-10">
            <div id="msg" style="color:green;text-align: center;"></div>
            <form  action="javascript:void(0);" id="form">
              <input type="hidden" name="education_id" class='education_id' value="<?php if(!empty($education)){echo $education[0]->education_id;}?>">
              <div class="row">
                <div class="col-md-3">
                  <label>Name</label>
                  <input type="text" name="education_name" class="name form-control" placeholder="Enter education name here"  value="<?php if(!empty($education)){ echo $education[0]->education_name;}?>">
                  <span class="name_err"></span>
                </div>
                <div class="col-md-4">
                  <!-- <label>Country Code</label>
                  <input type="text" name="country_code"  class="code form-control" placeholder="Enter country code here" value="<?php if(!empty($country)){ echo $country[0]->country_code;}?>">
                  <span class="code_err"></span> -->
                </div>
                <div class="col-md-3">
                  <!-- <label>Name</label>
                  <input type="text" name="name"  class="name form-control" placeholder="Enter name here" value="<?php if(!empty($education)){ $education[0]->name;}?>"><span class="latitude_err"></span> -->
                </div>
                <div class="col-md-3">
                  <!-- <label>longitude</label>
                  <input type="text" name="longitude"  class="longitude form-control" placeholder="Enter longitude here" value="<?php if(!empty($education)){ $education[0]->lng;}?>" >
                  <span class="longitude_err"></span> -->
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
      var education_id=$('.education_id').val(); 
      var education_name=$('.name').val();

      // console.log(education_id+"  "+education_name);
      var error=0;
      if(education_name==""){
        var error=error+1;
        $('.name').css('border','1px solid red');
        $('.name_err').html('please enter name').css('color','red');
      }
      else
      {
        $('.name').css('border','1px solid green');
        $('.name_err').html("");
      }
      if(education_name!="" && error=='0'){
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/addeducation",
          type:'post',
          dataType:'json',
          data:{
            education_id:education_id,
            education_name:education_name,
          },
          success:function(data){
            if(data.status==1){
              $('#form').trigger("reset");
              $("#msg").removeAttr("style");
              if(education_id==""){
                
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong> Yes/No Inserted Successfully.</div>').delay(3000).fadeOut();
              }
              else{
                
                $("#msg").removeAttr('style');
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong> Data Updated Successfully.</div>').delay(3000).fadeOut();
              }
            }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
           if(education_id==""){
             $('#msg').html("Unable to Insert Education Name");
           }
           else{
             $('#msg').html("Unable to Update Education Name");
           }
          }
        });
      }
    });
  });
</script> 