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
          <h3 class="title"><?php if(!empty($this->uri->segment(3))){ echo 'Edit';}else{ echo 'Add';}?> Value Chain</h3>
        </div>
        
        <div class="col-md-12 mt-10">
          <div class="card p-10">
            <div id="msg" style="color:green;text-align: center;"></div>
            <form  action="javascript:void(0);" id="form">
              <input type="hidden" name="valuechain_id" class='valuechain_id' >
              <div class="row">
                <div class="col-md-3">
                  <label>Value Chain </label>
                  <input type="text" name="valuechain_name" class="name form-control" placeholder="Enter Value Chain here" >
                  <span class="name_err"></span>
                </div>
                <div class="col-md-3">
                  <label>Description</label>
                  <input type="text" name="valuechain_description" class="description form-control" placeholder="Enter Valuechain  Description here" >
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
          <a class="btn btn-md btn-round btn-success pull-right addvaluechain" href="javascript:void(0);">
            <i class="fa fa-plus addvaluechain" aria-hidden="true"></i> Value Chain
          </a>
          <h5 class="card-title">Value Chain Details</h5>
          <div class="table-responsive">
            <table class="table mb-0" id="test">
              <thead>
                <tr>
                  <th scope="col">Sl.NO</th>
                  <!-- <th scope="col">Training Type</th> -->
                  <th scope="col">Name</th>
                  <th scope="col">Description</th>
                  <th>Status</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php  foreach ($valuechains as $key =>  $valuechain) {?>
                  <tr>
                    <td class="text-truncate"><?php echo $key+1; ?></td>
                    <td class="text-truncate" ><?php echo $valuechain->value_chain_name;?></td>
                     <td class="text-truncate"><?php echo $valuechain->value_chain_description;?></td>
                    <td style="display:none;" class="status_<?php echo $valuechain->value_chain_id;?>">
                      <input type="hidden" name="status" class="status" value="<?php echo $valuechain->status;?>">
                      <?php if($valuechain->status=='1'){?>
                        <i style="font-size:25px;color:green;" class="fa fa-check"></i>
                      <?php }else{ ?>
                        <i style="font-size:25px;color:red" class="fa fa-close"></i>
                      <?php } ?>
                    </td>
                    <td class="text-truncate" class="status2_<?php echo $valuechain->value_chain_id;?>">
                      <?php if($valuechain->status=='1'){?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $valuechain->value_chain_id;?>">
                        <i style="font-size:25px;color:green" class="togg<?php echo $valuechain->value_chain_id;?> <?php echo "fa fa-toggle-on"; ?>"></i>
                      </a><?php }else{ ?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $valuechain->value_chain_id;?>">
                        <i style="font-size:25px;color: red" class="togg<?php echo $valuechain->value_chain_id;?> <?php echo "fa fa-toggle-off"; ?>"></i>
                      </a><?php } ?>
                    </td>
                    <td class="text-truncate">
                      <a href="javascript:void(0);" class="edit" id="<?php echo $valuechain->value_chain_id;?>">
                        <i class="fa fa-edit" style="font-size:25px;"></i>
                      </a>
                    <!--   <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/get_subvaluechain/<?php echo $specific->value_chain_id;?>">
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
      var valuechain_id=$(this).attr('id');
      var valuechain_status=$(this).parent().prev().find('.status').val();
      if(valuechain_id!="" && valuechain_status!=""){
        $.ajax({
          url : '<?php echo base_url();?><?php echo $this->uri->segment(1);?>/valuechain_delete',
          type : 'POST',
          dataType:'json',
          data : {
            valuechain_id:valuechain_id,
            valuechain_status:valuechain_status,
          },
          success : function(data){ 
            if(data.cstatus==1){
              $('.status_'+valuechain_id).html("");
              $('#test tbody').find('.status_'+valuechain_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:green;" class="fa fa-check"></i>');
              $(".togg"+valuechain_id).removeClass('fa fa-toggle-off');
              $('.togg'+valuechain_id).addClass('fa fa-toggle-on "togg'+valuechain_id+'"').css("color","green");
            }
            else{
              $('.status_'+valuechain_id).html("");
              $('#test tbody').find('.status_'+valuechain_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:red;" class="fa fa-close"></i>');
              $(".togg"+valuechain_id).removeClass('fa fa-toggle-on');
              $('.togg'+valuechain_id).addClass('fa fa-toggle-off "togg'+valuechain_id+'"').css("color","red");
            }
          },
        });
      }
    });
    $('.addvaluechain').click(function(){
      $('.add').show();
      window.scrollTo({ top: 0, behavior: 'smooth' });

    });
    $('#submit').click(function(){
      var valuechain_id=$('.valuechain_id').val(); 
      var valuechain_name=$('.name').val();
      var valuechain_description=$('.description').val();
      var error=0;
      if(valuechain_name==""){
        var error=error+1;
        $('.name').css('border','1px solid red');
        $('.name_err').html('please enter valuechain name').css('color','red');
      }
      else
      {
      if(valuechain_name!="" && error=='0'){
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/add_valuechain",
          type:'post',
          dataType:'json',
          data:{
            valuechain_id:valuechain_id,
            valuechain_name:valuechain_name,
            valuechain_description:valuechain_description,
          },
          success:function(data){
            if(data.status=='1'){
              $('#form').trigger("reset");
              $("#msg").removeAttr("style");
              if(valuechain_id==""){
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong> Value Chain Inserted Successfully.</div>').delay(3000).fadeOut();
              }
              else{
                $("#msg").removeAttr('style');
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong>Value Chain Updated Successfully.</div>').delay(3000).fadeOut();
              }
            }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
            if(valuechain_id==""){
              $('#msg').html("Unable to Insert valuechain ");
            }
            else{
              $('#msg').html("Unable to Update valuechain");
            }
          }
        });
      }
      }
    });

    $('.edit').click(function(){
      $('.add').show();
      var valuechain_id=$(this).attr('id');
      if(valuechain_id!="")
      {
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/valuechain_edit",
          type:'post',
          dataType:'json',
          data:{
           valuechain_id:valuechain_id,
          },
          success:function(data){
            if(data.status=='1'){
              data.valuechain.forEach(function(valuechain,index){
              $('.valuechain_id').val(valuechain.value_chain_id);
              $('.name').val(valuechain.value_chain_name);
              $('.description').val(valuechain.value_chain_description);
              $('.add').show();
              window.scrollTo({ top: 0, behavior: 'smooth' });
               });
              }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
            if(valuechain_id==""){
              $('#msg').html("Unable to Insert valuechain ");
            }
            else{
              $('#msg').html("Unable to Update valuechain");
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
