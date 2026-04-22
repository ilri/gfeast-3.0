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
      <div class="card shadow-none" style="margin-top:20px;margin-bottom:90px;">
        <div class="card-body">
          <!-- <a class="btn btn-md btn-round btn-success pull-right addeventtype" href="javascript:void(0);">
            <i class="fa fa-plus addeventtype" aria-hidden="true"></i> Debt
          </a> -->
          <h5 class="card-title">Event Type Details(By Id)</h5>
          <div class="table-responsive">
            <table class="table mb-0" id="test">
              <thead>
                <tr>
                  <th scope="col">Sl.NO</th>
                  <th scope="col">ID</th>
                  <th scope="col">Training Type ID</th>
                  <th scope="col">Training Specific Type ID</th>
                  <th scope="col">Name</th>
                   <th scope="col">Added On</th>
                  <th>Status</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php  foreach ($eventtype as $key =>  $eventtype) {?>
                  <tr>
                    <td class="text-truncate"><?php echo $key+1; ?></td>
                    <td class="text-truncate" ><?php echo $eventtype->id;?></td>
                    <td class="text-truncate" ><?php echo $eventtype->trainingtype_id;?></td>
                    <td class="text-truncate" ><?php echo $eventtype->trainingspecifics_id;?></td>
                    <td class="text-truncate" ><?php echo $eventtype->name;?></td>
                    <td class="text-truncate" ><?php echo $eventtype->added_datetime;?></td>
                    <td style="display: none;" class="status_<?php echo $eventtype->id;?>">
                      <input type="hidden" name="status" class="status" value="<?php echo $eventtype->status;?>">
                      <?php if($eventtype->status=='1'){?>
                        <i style="font-size:25px;color:green;" class="fa fa-check"></i>
                      <?php }else{ ?>
                        <i style="font-size:25px;color:red" class="fa fa-close"></i>
                      <?php } ?>
                    </td>
                    <td class="text-truncate">
                      <a href="javascript:void(0);" class="delete" id="<?php echo $eventtype->id;?>">
                        <i style="font-size:25px;" class="fa fa-toggle-on"></i>
                      </a>
                      <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/eventtype_edit/<?php echo $eventtype->id;?>" class="edit" id="<?php echo $eventtype->id;?>">
                        <i class="fa fa-edit" style="font-size:25px;"></i>
                      </a>
                      <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/get_subeventtype/<?php echo $eventtype->id;?>">
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
      var eventtype_id=$(this).attr('id');
      var eventtype_status=$(this).parent().prev().find('.status').val();
      if(eventtype_id!="" && eventtype_status!=""){
        $.ajax({
          url : '<?php echo base_url();?><?php echo $this->uri->segment(1);?>/eventtype_delete',
          type : 'POST',
          dataType:'json',
          data : {
            eventtype_id:eventtype_id,
            eventtype_status:eventtype_status,
          },
          success : function(data){	
            if(data.cstatus==1){
              $('.status_'+county_id).html("");
              $('#test tbody').find('.status_'+eventtype_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:green;" class="fa fa-check"></i>');
              $(".togg"+county_id).removeClass('fa fa-toggle-off');
              $('.togg'+county_id).addClass('fa fa-toggle-on togg'+county_id+'').css("color","green");
            }
            else{
              $('.status_'+eventtype_id).html("");
              $('#test tbody').find('.status_'+eventtype_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:red;" class="fa fa-close"></i>');
              $(".togg"+county_id).removeClass('fa fa-toggle-on');
              $('.togg'+county_id).addClass('fa fa-toggle-off togg'+county_id+'').css("color","red");
            }
          },
        });
      }
    });
    $('.eventtype').click(function(){
      $('.add').show();

    });
    $('#submit').click(function(){
      var eventtype_id=$('.eventtype_id').val(); 
      var eventtype_name=$('.name').val();
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
  //     if(county_name!="" && error=='0'){
  //       $.ajax({
  //         url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/addcounty",
  //         type:'post',
  //         dataType:'json',
  //         data:{
  //           county_id:county_id,
  //           county_name:county_name,
  //           latitude:latitude,
  //           longitude:longitude,
  //         },
  //         success:function(data){
  //           if(data.status=='1'){
  //             $('#form').trigger("reset");
  //             $("#msg").removeAttr("style");
  //             if(county_id==""){
  //               $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong> County Inserted Successfully.</div>').delay(3000).fadeOut();
  //             }
  //             else{
  //               $("#msg").removeAttr('style');
  //               $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong>County Updated Successfully.</div>').delay(3000).fadeOut();
  //             }
  //           }
  //           else{
  //             $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
  //           }
  //         },
  //         error:function(data){
  //           if(county_id==""){
  //             $('#msg').html("Unable to Insert county");
  //           }
  //           else{
  //             $('#msg').html("Unable to Update county");
  //           }
  //         }
  //       });
  //     }
  //   });
  });
</script>
