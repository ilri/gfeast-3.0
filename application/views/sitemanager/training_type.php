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
          <h3 class="title"><?php if(!empty($this->uri->segment(3))){ echo 'Edit';}else{ echo 'Add';}?> County</h3>
        </div>
        
        <div class="col-md-12 mt-10">
          <div class="card p-10">
            <div id="msg" style="color:green;text-align: center;"></div>
            <form  action="javascript:void(0);" id="form">
              <input type="hidden" name="trainingtype_id" class='trainingtype_id' value="<?php if(!empty($county)){echo $county[0]->county_id;}?>">
              <div class="row">
                <div class="col-md-3">
                  <label>Training Type Name</label>
                  <input type="text" name="county_name" class="name form-control" placeholder="Enter training type here"  value="<?php if(!empty($county)){ echo $county[0]->name;}?>">
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
            <i class="fa fa-plus addcounty" aria-hidden="true"></i> Training Type
          </a>
          <h5 class="card-title">Training Type Details</h5>
          <div class="table-responsive">
            <table class="table mb-0" id="test">
              <thead>
                <tr>
                  <th scope="col">Sl.NO</th>
                  <!-- <th scope="col">Training Type</th> -->
                  <th scope="col">Name</th>
                  <th>Status</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php  foreach ($types as $key =>  $type) {?>
                  <tr>
                    <td class="text-truncate"><?php echo $key+1; ?></td>
                   <!--  <td class="text-truncate" ><?php echo $specific->type_name;?></td> -->
                    <td class="text-truncate" ><?php echo $type->name;?></td>
                    <td style="display: none;" class="status_<?php echo $type->id;?>">
                      <input type="hidden" name="status" class="status" value="<?php echo $type->status;?>">
                      <?php if($type->status=='1'){?>
                        <i style="font-size:25px;color:green;" class="fa fa-check"></i>
                      <?php }else{ ?>
                        <i style="font-size:25px;color:red" class="fa fa-close"></i>
                      <?php } ?>
                    </td>
                    <td class="text-truncate" class="status2_<?php echo $type->id;?>">
                      <?php if($type->status=='1'){?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $type->id;?>">
                        <i style="font-size:25px;color:green" class="togg<?php echo $type->id;?> <?php echo "fa fa-toggle-on"; ?>"></i>
                      </a><?php }else{ ?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $type->id;?>">
                        <i style="font-size:25px;color: red" class="togg<?php echo $type->id;?> <?php echo "fa fa-toggle-off"; ?>"></i>
                      </a><?php } ?>
                    </td>
                    <td>
                      <a href="javascript:void(0);" class="edit" id="<?php echo $type->id;?>">
                        <i class="fa fa-edit" style="font-size:25px;"></i>
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
              $('.togg'+county_id).addClass('fa fa-toggle-on togg'+county_id+'').css("color","green");

            }
            else{
              $('.status_'+county_id).html("");
              $('#test tbody').find('.status_'+county_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:red;" class="fa fa-close"></i>');
              $(".togg"+county_id).removeClass('fa fa-toggle-on');
              $('.togg'+county_id).addClass('fa fa-toggle-off togg'+county_id+'').css("color","red");
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
      var trainingtype_id=$('.trainingtype_id').val();
      var trainingtype_name=$('.name').val();
      var error=0;
      if(trainingtype_name==""){
        var error=error+1;
        $('.name').css('border','1px solid red');
        $('.name_err').html('please enter country name').css('color','red');
      }
      else
      {
        $('.name').css('border','1px solid green');
        $('.name_err').html("");
      }
      if(trainingtype_name!="" && error=='0'){
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/addtrainingtype",
          type:'post',
          dataType:'json',
          data:{
            trainingtype_id:trainingtype_id,
            trainingtype_name:trainingtype_name,
          },
          success:function(data){
            if(data.status=='1'){
              $('#form').trigger("reset");
              $("#msg").removeAttr("style");
              if(trainingtype_id==""){
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong> Training Type Inserted Successfully.</div>').delay(3000).fadeOut();
              }
              else{
                $("#msg").removeAttr('style');
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong>Training Type Updated Successfully.</div>').delay(3000).fadeOut();
              }
            }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
            if(trainingtype_id==""){
              $('#msg').html("Unable to Insert Training Type");
            }
            else{
              $('#msg').html("Unable to Update Training Type");
            }
          }
        });
      }
    });
    $('.edit').click(function(){
      var trainingtype_id=$(this).attr('id');
     if(trainingtype_id!="")
     {
       $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/edit_trainingtype",
          type:'post',
          dataType:'json',
          data:{
            trainingtype_id:trainingtype_id,
            // trainingtype_name:trainingtype_name,
          },
          success:function(data){
            if(data.status=='1'){
                data.types.forEach(function(types,index){
                  $('.trainingtype_id').val(types.id);
                  $('.name').val(types.name);
                  $('.add').show();
                  window.scrollTo({ top: 0, behavior: 'smooth' });


                });
            }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
            if(trainingtype_id==""){
              $('#msg').html("Unable to Insert Training Type");
            }
            else{
              $('#msg').html("Unable to Update Training Type");
            }
          }
        });

     }
    });
  });
</script>
