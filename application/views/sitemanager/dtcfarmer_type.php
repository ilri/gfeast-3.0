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
          <h3 class="title"><?php if(!empty($this->uri->segment(3))){ echo 'Edit';}else{ echo 'Add';}?> DTC Farmer Type</h3>
        </div>
        
        <div class="col-md-12 mt-10">
          <div class="card p-10">
            <div id="msg" style="color:green;text-align: center;"></div>
            <form  action="javascript:void(0);" id="form">
              <input type="hidden" name="dtcfarmer_id" class='dtcfarmer_id' >
              <div class="row">
                <div class="col-md-3">
                  <label>DTC Farmer Type </label>
                  <input type="text" name="dtcfarmer_name" class="name form-control" placeholder="Enter DTC Farmer Type here" >
                  <span class="name_err"></span>
                </div>
               <!--  <div class="col-md-3">
                  <label>Description</label>
                  <input type="text" name="latitude"  class="desc form-control" placeholder="Enter Description here" value="<?php if(!empty($dtcfarmer)){ $dtcfarmer[0]->lat;}?>"><span class="desc_err"></span>
                </div> -->

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
          <a class="btn btn-md btn-round btn-success pull-right adddtcfarmer" href="javascript:void(0);">
            <i class="fa fa-plus adddtcfarmer" aria-hidden="true"></i> DTC Farmer Type
          </a>
          <h5 class="card-title">DTC Farmer Type Details</h5>
          <div class="table-responsive">
            <table class="table mb-0" id="test">
              <thead>
                <tr>
                  <th scope="col">Sl.NO</th>
                  <th scope="col">Name</th>
                  <th scope="col">Added By</th>
                  <th>Status</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php  foreach ($dtcfarmertypes as $key =>  $dtcfarmer) {?>
                  <tr>
                    <td class="text-truncate"><?php echo $key+1; ?></td>
                    <td class="text-truncate" ><?php echo $dtcfarmer->type;?></td>
                     <td class="text-truncate" ><?php echo $dtcfarmer->first_name.' '.$dtcfarmer->last_name;?></td>
                    <td style="display: none;"class="status_<?php echo $dtcfarmer->id;?>">
                      <input type="hidden" name="status" class="status" value="<?php echo $dtcfarmer->status;?>">
                      <?php if($dtcfarmer->status=='1'){?>
                        <i style="font-size:25px;color:green;" class="fa fa-check"></i>
                      <?php }else{ ?>
                        <i style="font-size:25px;color:red" class="fa fa-close"></i>
                      <?php } ?>
                    </td>
                    <td class="text-truncate" class="status2_<?php echo $dtcfarmer->id;?>">
                      <?php if($dtcfarmer->status=='1'){?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $dtcfarmer->id;?>">
                        <i style="font-size:25px;color:green" class="togg<?php echo $dtcfarmer->id;?> <?php echo "fa fa-toggle-on"; ?>"></i>
                      </a><?php }else{ ?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $dtcfarmer->id;?>">
                        <i style="font-size:25px;color: red" class="togg<?php echo $dtcfarmer->id;?> <?php echo "fa fa-toggle-off"; ?>"></i>
                      </a><?php } ?>
                    </td>
                    <td>
                      <a href="javascript:void(0);" class="edit" id="<?php echo $dtcfarmer->id;?>">
                        <i class="fa fa-edit" style="font-size:25px;"></i>
                      </a>
                    <!--   <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/get_subdtcfarmer/<?php echo $specific->id;?>">
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
      var dtcfarmer_id=$(this).attr('id');
      var dtcfarmer_status=$(this).parent().prev().find('.status').val();
      if(dtcfarmer_id!="" && dtcfarmer_status!=""){
        $.ajax({
          url : '<?php echo base_url();?><?php echo $this->uri->segment(1);?>/dtcfarmer_delete',
          type : 'POST',
          dataType:'json',
          data : {
            dtcfarmer_id:dtcfarmer_id,
            dtcfarmer_status:dtcfarmer_status,
          },
          success : function(data){ 
            if(data.cstatus==1){
              $('.status_'+dtcfarmer_id).html("");
              $('#test tbody').find('.status_'+dtcfarmer_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:green;" class="fa fa-check"></i>');
              $(".togg"+dtcfarmer_id).removeClass('fa fa-toggle-off');
              $('.togg'+dtcfarmer_id).addClass('fa fa-toggle-on "togg'+dtcfarmer_id+'"').css("color","green");
            }
            else{
              $('.status_'+dtcfarmer_id).html("");
              $('#test tbody').find('.status_'+dtcfarmer_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:red;" class="fa fa-close"></i>');
            $(".togg"+dtcfarmer_id).removeClass('fa fa-toggle-on');
              $('.togg'+dtcfarmer_id).addClass('fa fa-toggle-off "togg'+dtcfarmer_id+'"').css("color","red"); }
          },
        });
      }
    });
    $('.adddtcfarmer').click(function(){
      $('.add').show();
      window.scrollTo({ top: 0, behavior: 'smooth' });

    });
    $('#submit').click(function(){
      var dtcfarmer_id=$('.dtcfarmer_id').val();
      var dtcfarmer_name=$('.name').val();
      var error=0;
      if(dtcfarmer_name==""){
        var error=error+1;
        $('.name').css('border','1px solid red');
        $('.name_err').html('please enter dtcfarmer name').css('color','red');
      }
      else
      {
      if(dtcfarmer_name!="" && error=='0'){
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/adddtcfarmer",
          type:'post',
          dataType:'json',
          data:{
            dtcfarmer_id:dtcfarmer_id,
            dtcfarmer_name:dtcfarmer_name,
          },
          success:function(data){
            if(data.status=='1'){
              $('#form').trigger("reset");
              $("#msg").removeAttr("style");
              if(dtcfarmer_id==""){
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong> DTC Farmer Type Inserted Successfully.</div>').delay(3000).fadeOut();
              }
              else{
                $("#msg").removeAttr('style');
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong>DTC Farmer Type Updated Successfully.</div>').delay(3000).fadeOut();
              }
            }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
            if(dtcfarmer_id==""){
              $('#msg').html("Unable to Insert dtcfarmer ");
            }
            else{
              $('#msg').html("Unable to Update dtcfarmer");
            }
          }
        });
      }
      }
    });

    $('.edit').click(function(){
      var dtcfarmer_id=$(this).attr('id');
      if(dtcfarmer_id!="")
      {
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/dtcfarmer_edit",
          type:'post',
          dataType:'json',
          data:{
           dtcfarmer_id:dtcfarmer_id,
          },
          success:function(data){
            if(data.status=='1'){
              data.dtcfarmer.forEach(function(dtcfarmer,index){
              $('.dtcfarmer_id').val(dtcfarmer.id);
              $('.name').val(dtcfarmer.type);
              $('.add').show();
              window.scrollTo({ top: 0, behavior: 'smooth' });
               });
              }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
            if(dtcfarmer_id==""){
              $('#msg').html("Unable to Insert dtcfarmer ");
            }
            else{
              $('#msg').html("Unable to Update dtcfarmer");
            }
          }
        });

      }
      else
      {
        $("#msg").html("Invalid Edit");
      }
    });
  });
</script>
