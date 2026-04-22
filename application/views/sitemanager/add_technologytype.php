<div class="app-content content" style="margin-left: 0px;">
  <div class="content-wrapper">
    <div class="content-body" style="margin-bottom: 40px;">
      <div class="row">
        <div class="col-md-12">
          <!-- <a class="btn btn-success pull-right" href="<?php echo base_url();?><?php echo $this->uri->segment('1');?>/get_country">Countries List</a> -->
          <h3 class="title"><?php if(!empty($this->uri->segment(3))){ echo 'Edit';}else{ echo 'Add';}?> Technology Type</h3>
        </div>
        
        <div class="col-md-12 mt-10">
          <div class="card p-10">
            <div id="msg" style="color:green;text-align: center;"></div>
            <form  action="javascript:void(0);" id="form">
              <input type="hidden" name="technologytype_id" class='technologytype_id' value="<?php if(!empty($technologytype)){echo $technologytype[0]->technologytype_id;}?>">
              <div class="row">
                <div class="col-md-3">
                  <label>Name</label>
                  <input type="text" name="technologytype_name" class="name form-control" placeholder="Enter technologytype name here"  value="<?php if(!empty($technologytype)){ echo $technologytype[0]->technologytype_name;}?>">
                  <span class="name_err"></span>
                </div>
                <div class="col-md-4">
                  <!-- <label>Country Code</label>
                  <input type="text" name="country_code"  class="code form-control" placeholder="Enter country code here" value="<?php if(!empty($country)){ echo $country[0]->country_code;}?>">
                  <span class="code_err"></span> -->
                </div>
                <div class="col-md-3">
                  <!-- <label>Name</label>
                  <input type="text" name="name"  class="name form-control" placeholder="Enter name here" value="<?php if(!empty($technologytype)){ $technologytype[0]->name;}?>"><span class="latitude_err"></span> -->
                </div>
                <div class="col-md-3">
                  <!-- <label>longitude</label>
                  <input type="text" name="longitude"  class="longitude form-control" placeholder="Enter longitude here" value="<?php if(!empty($technologytype)){ $technologytype[0]->lng;}?>" >
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
      var technologytype_id=$('.technologytype_id').val(); 
      var technologytype_name=$('.name').val();

      // console.log(technologytype_id+"  "+technologytype_name);
      var error=0;
      if(technologytype_name==""){
        var error=error+1;
        $('.name').css('border','1px solid red');
        $('.name_err').html('please enter name').css('color','red');
      }
      else
      {
        $('.name').css('border','1px solid green');
        $('.name_err').html("");
        // console.log("entered input");
      }
      if(technologytype_name!="" && error=='0'){
         console.log("technologytype_name is not null"+technologytype_name);
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/addtechnologytype",
          type:'post',
          dataType:'json',
          data:{
            id:technologytype_id,
            technologytype_name:technologytype_name,
          },
          success:function(data){
            if(data.status==1){
              $('#form').trigger("reset");
              $("#msg").removeAttr("style");
              if(technologytype_id==""){
                
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
           if(technologytype_id==""){
             $('#msg').html("Unable to Insert Technology Type Name");
           }
           else{
             $('#msg').html("Unable to Update Technology Type Name");
           }
          }
        });
      }
    });
  });
</script> 