<style type="text/css">
  p {
      margin-top: 0;
      margin-bottom: 0;
  }
  .panel-title {
      display: block;
      padding: 8px 12px;
      margin-top: 0;
      margin-bottom: 0;
      font-size: 15px;
      color: #424242;
  }
  .panel-body {
      position: relative;
      padding: 15px;
  }
</style>

<div class="app-content content" style="margin-left: 0px;">
  <div class="content-wrapper">
    <div class="content-body" style="margin-top: 10px;">
      <div class="row">
        <div class="col-md-12">
          <!-- <?php if($main_menu['permission_list'] != ''){ ?>                    
            <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">   
              <button class="btn btn-info round dropdown-toggle dropdown-menu-right box-shadow-2 px-2" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <?php foreach ($main_menu['permission_list'] as $key => $value) { ?>
                    <a class="dropdown-item" href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $value['module_key']; ?>">
                      <?php echo $value['name']; ?>
                    </a>
                <?php } ?>
              </div>
            </div>
          <?php } ?> -->
          <h4 style="font-weight: bold;">Manage Survey</h4>
        </div>
      </div>

      <div class="row mt-10"> 
        <form id="formdata" style="margin-bottom: 40px;">
          <div id="surveyform">
            <div class="card p-20">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Select County</label>
                    <select class="form-control" name="county">
                      <option value="">Select County</option>
                      <?php foreach ($user_county_data as $key => $county) { ?>
                        <option value="<?php echo $county['county_id']; ?>"><?php echo $county['name']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Select Subcounty</label>
                    <select class="form-control" name="subcounty">
                      <option value="">Select Subcounty</option>
                      
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Select Ward</label>
                    <select class="form-control" name="ward">
                      <option value="">Select Ward</option>
                      
                    </select>
                  </div>
                </div>
              </div>
              <div class="row"><?php
                $i = 1;
                foreach ($get_surveyfields as $key => $value) {
                  $formfield = "field_".$value['field_id'];
                    if($value['parent_id'] == null){
                      switch ($value['type']) {
                        case 'collapse': ?>
                          <div class="col-md-12">
                            <div class="panel panel-default" style="border: 1px solid #1e9ff2; margin: 2px; font-weight: bold;">
                              <div class="panel-heading">
                                <h4 class="panel-title expand title">
                                  <a data-toggle="collapse" data-parent="#panel<?php echo $value['field_id']; ?>" href="#<?php echo $value['field_id']; ?>" style="text-decoration: none;"><?php echo $value['label']; ?></a>
                                  <span class="pull-right panel-collapse-clickable" data-toggle="collapse" data-parent="#panel<?php echo $value['field_id']; ?>" href="#<?php echo $value['field_id']; ?>">
                                      <i class="icon-plus success float-right"></i>
                                  </span>
                                </h4>
                              </div>
                              <div id="<?php echo $value['field_id']; ?>" class="panel-collapse panel-collapse collapse">
                                <div class="panel-body">
                                  <div class="row">
                                    <?php foreach ($value['collapse_fields'] as $key => $collapse) {
                                      $collapseformfield = "field_".$collapse['field_id'];
                                      if($collapse['parent_id'] == $value['field_id']){
                                        switch ($collapse['type']) {
                                          //display of text box field
                                          case 'text': ?>
                                            <div class="col-md-12">
                                              <div class="form-group">
                                                <?php $textquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                                                <label class="english"><?php echo ($collapse['field_count'] == 1) ? $textquestion.". ".$collapse['label'] : $collapse['label'];
                                                  echo ($collapse['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                                                </label>
                                                <?php echo ($collapse['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$collapse["description"].'</p>' : '';
                                                if($collapse['subtype'] == 'datetime-local'){ ?>
                                                    <input type="text" name="field_<?php echo $collapse['field_id']; ?>" class="<?php echo $collapse['className']; ?> datetimepicker5" >
                                                  <?php }else{ ?>
                                                    <input type="<?php echo $collapse['subtype']; ?>" name="field_<?php echo $collapse['field_id']; ?>" class="<?php echo $collapse['className']; ?>" data-subtype="<?php echo $collapse['subtype']; ?>" data-maxlength = "<?php echo $collapse['maxlength']; ?>" data-required = "<?php if($collapse['required'] == 1){ echo 'required'; }else{ echo 'notrequired'; } ?>" value="<?php echo (isset($record_details)) ? $record_details[$collapseformfield] : '' ?>" >
                                                  <?php } ?>
                                                <p class="error red-800"></p>
                                                <p class="maxlengtherror red-800"></p>
                                              </div>
                                            </div>
                                            <?php break;

                                          //display of header
                                          case 'header': ?>
                                            <div class="col-md-12">
                                              <?php switch ($collapse['subtype']) {
                                                case 'h1': ?>
                                                  <h1 style="margin-top: 0px; margin-bottom: 20px;" class="title">
                                                    <?php echo $collapse['label']; ?>
                                                  </h1>
                                                <?php  break;

                                                case 'h2': ?>
                                                    <h2 style="margin-top: 0px; margin-bottom: 20px;" class="title">
                                                      <?php echo $collapse['label']; ?>
                                                    </h2>
                                                <?php  break;

                                                case 'h3': ?>
                                                    <h3 style="margin-top: 0px; margin-bottom: 20px;" class="title">
                                                      <?php echo $collapse['label']; ?>
                                                    </h3>
                                                <?php  break;

                                                case 'h4': ?>
                                                    <h4 style="margin-top: 0px; margin-bottom: 20px;" class="title">
                                                      <?php echo $collapse['label']; ?>
                                                    </h4>
                                                <?php  break;

                                                case 'h5': ?>
                                                    <h5 style="margin-top: 0px; margin-bottom: 20px;" class="title">
                                                      <?php echo $collapse['label']; ?>
                                                    </h5>
                                                <?php  break;
                                              } ?>
                                            </div>
                                          <?php break;

                                          //display radio button
                                          case 'radio-group': ?>
                                            <div class="col-md-12">
                                              <div class="form-group">
                                                <?php $radioquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                                                <label class="english"><?php echo ($collapse['field_count'] == 1) ? $radioquestion.". ".$collapse['label'] : $collapse['label'];
                                                  echo ($collapse['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                                                </label>
                                                <?php echo ($collapse['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$collapse["description"].'</p>' : ''; ?>
                                                <div class="form-check">
                                                  <div class="row"><?php
                                                    foreach ($collapse['options'] as $key => $option) { ?>
                                                      <div class="col-md-4">
                                                        <label class="<?php if($collapse['inline'] == 'true' || $collapse['inline'] == 'TRUE'){ echo "radio-inline"; } ?>" >
                                                          <?php if(isset($record_details)){
                                                            if($record_details[$collapseformfield] == $option['value']){
                                                              $radio_value = 'checked';
                                                            }else{
                                                              $radio_value = '';
                                                            }
                                                          }else{
                                                            if($option['selected'] == 'true' || $option['selected'] == 'TRUE'){ 
                                                              $radio_value = "checked"; 
                                                            }else{
                                                              $radio_value = '';
                                                            }
                                                          } ?>
                                                          <input type="radio" name="field_<?php echo $collapse['field_id']; ?>"  class="<?php if($collapse['className'] != ''){ echo $collapse['className']; }  ?>" value = "<?php echo $option['value']; ?>"  style="margin-right: 5px;" data-field_id = "<?php echo $collapse['field_id']; ?>" data-field_value = "<?php echo $option['value']; ?>" data-required = "<?php echo ($collapse['required'] == 1) ? 'required' : 'notrequired'; ?>" <?php echo $radio_value; ?> ><span class="english"><?php echo $option['label'] ?></span><span class="french hidden" style="padding-left: 6px;">(<?php echo $option['french_label'] ?>)</span>
                                                        </label>
                                                      </div><?php
                                                    } ?>
                                                  </div>
                                                </div>                                                      
                                                <p class="error red-800"></p>
                                              </div>
                                            </div>
                                            <div class="col-md-12">
                                              <div class="row childfields childof<?php echo $collapse['field_id']; ?>">
                                              
                                              </div>
                                            </div>
                                            <?php break;

                                          //display number field
                                          case 'number': ?>
                                            <div class="col-md-12">
                                              <div class="form-group">
                                                <?php $numberquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                                                <label class="english"><?php echo ($collapse['field_count'] == 1) ? $numberquestion.". ".$collapse['label'] : $collapse['label'];
                                                  echo ($collapse['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                                                </label>
                                                <?php echo ($collapse['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$collapse["description"].'</p>' : ''; 
                                                switch ($collapse['subtype']) {
                                                  case 'desimal': ?>
                                                      <input type="text" name="field_<?php echo $collapse['field_id']; ?>" class="<?php echo $collapse['className']; ?> decimal" data-subtype="<?php echo $collapse['subtype']; ?>" data-maxlength = "<?php echo $collapse['maxlength']; ?>" data-required = "<?php echo ($collapse['required'] == 1) ? 'required' : 'notrequired'; ?>" value="<?php echo (isset($record_details)) ? $record_details[$collapseformfield] : '' ?>" >
                                                      <?php break;

                                                  case 'number': ?>
                                                      <input type="text" name="field_<?php echo $collapse['field_id']; ?>" class="<?php echo $collapse['className']; ?> number" data-subtype="<?php echo $collapse['subtype']; ?>" data-maxlength = "<?php echo $collapse['maxlength']; ?>" data-required = "<?php echo ($collapse['required'] == 1) ? 'required' : 'notrequired'; ?>" value="<?php echo (isset($record_details)) ? $record_details[$collapseformfield] : '' ?>" >
                                                      <?php break;

                                                  case 'latitude': ?>
                                                      <input type="text" name="field_<?php echo $collapse['field_id']; ?>" class="<?php echo $collapse['className']; ?> latlong" data-subtype="<?php echo $collapse['subtype']; ?>" data-maxlength = "<?php echo $collapse['maxlength']; ?>" data-required = "<?php echo ($collapse['required'] == 1) ? 'required' : 'notrequired'; ?>" value="<?php echo (isset($record_details)) ? $record_details[$collapseformfield] : '' ?>" >
                                                      <?php break;

                                                  case 'longitude': ?>
                                                      <input type="text" name="field_<?php echo $collapse['field_id']; ?>" class="<?php echo $collapse['className']; ?> latlong" data-subtype="<?php echo $collapse['subtype']; ?>" data-maxlength = "<?php echo $collapse['maxlength']; ?>" data-required = "<?php echo ($collapse['required'] == 1) ? 'required' : 'notrequired'; ?>" value="<?php echo (isset($record_details)) ? $record_details[$collapseformfield] : '' ?>" >
                                                      <?php break;

                                                  case 'phone': ?>
                                                      <input type="tel" name="field_<?php echo $collapse['field_id']; ?>" class="<?php echo $collapse['className']; ?> phone" style="width: 621.5px;" data-subtype="<?php echo $collapse['subtype']; ?>" data-maxlength = "<?php echo $collapse['maxlength']; ?>" data-required = "<?php echo ($collapse['required'] == 1) ? 'required' : 'notrequired'; ?>" value="<?php echo (isset($record_details)) ? $record_details[$collapseformfield] : '' ?>" >
                                                      <p class="phonenumber red-800" ></p>
                                                      <?php break;
                                                  
                                                  default: ?>
                                                      <input type="text" name="field_<?php echo $collapse['field_id']; ?>" class="<?php echo $collapse['className']; ?> numberfield" data-subtype="<?php echo $collapse['subtype']; ?>" data-maxlength = "<?php echo $collapse['maxlength']; ?>" data-required = "<?php echo ($collapse['required'] == 1) ? 'required' : 'notrequired'; ?>" value="<?php echo (isset($record_details)) ? $record_details[$collapseformfield] : '' ?>" >
                                                      <?php break;
                                                } ?>
                                                <p class="error red-800"></p>
                                                <p class="maxlengtherror red-800"></p>
                                              </div>
                                            </div>
                                            <?php break;

                                          //display checkbox
                                          case 'checkbox-group': ?>
                                            <div class="col-md-12">
                                              <div class="form-group">
                                                <?php $checkboxquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                                                <label class="english"><?php echo ($collapse['field_count'] == 1) ? $checkboxquestion.". ".$collapse['label'] : $collapse['label'];
                                                  echo ($collapse['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                                                </label>
                                                <?php echo ($collapse['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$collapse["description"].'</p>' : '';  ?>
                                                <div class="form-radio row"><?php
                                                  foreach ($collapse['options'] as $key => $option) { ?>
                                                    <div class="col-md-4">
                                                      <label class="<?php if($collapse['inline'] == 'true' || $collapse['inline'] == 'TRUE'){ echo "checkbox-inline"; } ?>" >
                                                        <?php if(isset($record_details)){
                                                          if($record_details[$collapseformfield] == $option['value']){
                                                            $checkbox_value = 'checked';
                                                          }else{
                                                            $checkbox_value = '';
                                                          }
                                                        }else{
                                                          if($option['selected'] == 'true' || $option['selected'] == 'TRUE'){ 
                                                            $checkbox_value = "checked"; 
                                                          }else{
                                                            $checkbox_value = '';
                                                          }
                                                        } ?>
                                                        <input type="checkbox" name="field_<?php echo $collapse['field_id']; ?>[]"  class="<?php if($collapse['className'] != ''){ echo $collapse['className']; }  ?>" value = "<?php echo $option['value']; ?>"  style = "margin-right: 5px;" data-required = "<?php echo ($collapse['required'] == 1) ? 'required' : 'notrequired'; ?>" <?php echo $checkbox_value; ?> ><span class="english"><?php echo $option['label'] ?></span><span class="french hidden" style="padding-left: 6px;">(<?php echo $option['french_label'] ?>)</span>
                                                      </label>
                                                    </div>
                                                  <?php } ?>
                                                </div>
                                                <p class="error red-800"></p>
                                              </div>
                                            </div>
                                            <?php break;

                                          //display of textarea
                                          case 'textarea': ?>
                                            <div class="col-md-12">
                                              <div class="form-group">
                                                <?php $textareaquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                                                <label class="english"><?php echo ($collapse['field_count'] == 1) ? $textareaquestion.". ".$collapse['label'] : $collapse['label'];
                                                  echo ($collapse['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                                                </label>
                                                <?php echo ($collapse['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$collapse["description"].'</p>' : '';  ?>
                                                <textarea name="field_<?php echo $collapse['field_id']; ?>" rows="8" class="<?php echo $collapse['className']; ?>" data-subtype="<?php echo $collapse['subtype']; ?>" data-maxlength = "<?php echo $collapse['maxlength']; ?>" data-required = "<?php echo ($collapse['required'] == 1) ? 'required' : 'notrequired'; ?>" ><?php echo (isset($record_details)) ? $record_details[$collapseformfield]  : ''; ?></textarea>
                                                <p class="error red-800"></p>
                                                <p class="maxlengtherror red-800"></p>
                                              </div>
                                            </div>
                                            <?php break;

                                          //display of select box
                                          case 'select': ?>
                                            <div class="col-md-12">
                                              <div class="form-group">
                                                <?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                                                <label class="english"><?php echo ($collapse['field_count'] == 1) ? $selectquestion.". ".$collapse['label'] : $collapse['label'];
                                                  echo ($collapse['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                                                </label>
                                                <?php echo ($collapse['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$collapse["description"].'</p>' : '';  
                                                  if($collapse['multiple'] == 'true' || $collapse['multiple'] == 'TRUE'){ ?>
                                                    <select name="field_<?php echo $collapse['field_id']; ?>[]" multiple class="form-control" <?php if($collapse['required'] == 1){ echo "required"; } ?> data-required = "<?php echo ($collapse['required'] == 1) ? 'required' : 'notrequired'; ?>" data-field_id = "<?php echo $collapse['field_id']; ?>" >
                                                  <?php }else{ ?>
                                                    <select name="field_<?php echo $collapse['field_id']; ?>" class="form-control" <?php if($collapse['required'] == 1){ echo "required"; } ?> data-required = "<?php echo ($collapse['required'] == 1) ? 'required' : 'notrequired'; ?>" data-field_id = "<?php echo $collapse['field_id']; ?>">
                                                      <option value="">Select an option</option>
                                                  <?php  }
                                                  $select_array = explode("&#44;", $record_details[$collapseformfield]);
                                                  foreach ($collapse['options'] as $key => $option) { 
                                                    if(isset($record_details)){
                                                      if(in_array($option['value'], $select_array)){
                                                        $select_value = 'selected';
                                                      }else{
                                                        $select_value = '';
                                                      }
                                                    }else{
                                                      if($option['selected'] == 'true' || $option['selected'] == 'TRUE'){ 
                                                        $select_value = "selected"; 
                                                      }else{
                                                        $select_value = '';
                                                      }
                                                    } ?>
                                                    <option value = "<?php echo $option['value']; ?>" <?php echo $select_value; ?> ><?php echo $option['label']; ?></option>
                                                  <?php  } ?>
                                                </select>
                                                <p class="error red-800"></p>
                                              </div>
                                            </div>
                                            <div class="col-md-12">
                                              <div class="row childfields childof<?php echo $collapse['field_id']; ?>">
                                                <?php if(isset($record_details) && ($record_details[$formfield] != NULL)){ ?>
                                                  <script type="text/javascript">
                                                    var calltype = 'onload';
                                                    var field_val = '<?php echo $record_details[$formfield]; ?>';
                                                    var field_val_array = field_val.split("&#44;");
                                                    getchild_field(<?php echo $value['field_id']; ?>, field_val_array, calltype);
                                                  </script>
                                                <?php } ?>
                                              </div>
                                            </div>
                                            <?php break;

                                          case 'lkp_market': ?>
                                            <div class="col-md-12">
                                              <div class="form-group">
                                                <?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                                                <label class="english"><?php echo ($collapse['field_count'] == 1) ? $selectquestion.". ".$collapse['label'] : $collapse['label'];
                                                  echo ($collapse['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                                                </label>
                                                <?php echo ($collapse['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$collapse["description"].'</p>' : '';  ?>
                                                <select name="field_<?php echo $collapse['field_id']; ?>" class="form-control" <?php if($collapse['required'] == 1){ echo "required"; } ?> data-required = "<?php echo ($collapse['required'] == 1) ? 'required' : 'notrequired'; ?>" data-field_id = "<?php echo $collapse['field_id']; ?>">
                                                  <option value="">Select an option</option>
                                                  <?php $select_array = explode("&#44;", $record_details[$collapseformfield]);
                                                  foreach ($collapse['options'] as $key => $option) { 
                                                    if(isset($record_details)){
                                                      if(in_array($option['value'], $select_array)){
                                                        $select_value = 'selected';
                                                      }else{
                                                        $select_value = '';
                                                      }
                                                    }else{
                                                      if($option['selected'] == 'true' || $option['selected'] == 'TRUE'){ 
                                                        $select_value = "selected"; 
                                                      }else{
                                                        $select_value = '';
                                                      }
                                                    } ?>
                                                    <option value = "<?php echo $option['value']; ?>" <?php echo $select_value; ?> ><?php echo $option['label']; ?></option>
                                                  <?php  } ?>
                                                </select>
                                                <p class="error red-800"></p>
                                              </div>
                                            </div>
                                            <?php break;

                                          //display date field
                                          case 'date': ?>
                                            <div class="col-md-12">
                                              <div class="form-group">
                                                <?php $datequestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                                                <label class="english"><?php echo ($collapse['field_count'] == 1) ? $datequestion.". ".$collapse['label'] : $collapse['label'];
                                                  echo ($collapse['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                                                </label>
                                                <?php echo ($collapse['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$collapse["description"].'</p>' : '';  ?>
                                                <input type="text" name="field_<?php echo $collapse['field_id']; ?>" class="<?php echo $collapse['className']; ?> picker" onkeydown="return false" data-required = "<?php echo ($collapse['required'] == 1) ? 'required' : 'notrequired'; ?>" value="<?php echo (isset($record_details)) ? $record_details[$collapseformfield] : '' ?>" >
                                                <p class="error red-800"></p>     
                                              </div>
                                            </div>
                                            <?php break;

                                          //display date field
                                          case 'month': ?>
                                            <div class="col-md-12">
                                              <div class="form-group">
                                                <?php $monthquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                                                <label class="english"><?php echo ($collapse['field_count'] == 1) ? $monthquestion.". ".$collapse['label'] : $collapse['label'];
                                                  echo ($collapse['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                                                </label>
                                                <?php echo ($collapse['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$collapse["description"].'</p>' : '';  ?>
                                                <input type="text" name="field_<?php echo $collapse['field_id']; ?>" class="<?php echo $collapse['className']; ?> monthpicker" onkeydown="return false" data-required = "<?php echo ($collapse['required'] == 1) ? 'required' : 'notrequired'; ?>" value="<?php echo (isset($record_details)) ? $record_details[$collapseformfield] : '' ?>" >
                                                <p class="error red-800"></p> 
                                              </div>
                                            </div>
                                            <?php break;
                                        }
                                      }
                                    } ?>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php break;

                        //display of text box field
                        case 'text':
                          if($value['subtype'] != 'tel'){ ?>
                            <div class="col-md-12">
                              <div class="form-group">
                                <?php $textquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                                <label class="english"><?php echo ($value['field_count'] == 1) ? $textquestion.". ".$value['label'] : $value['label'];
                                  echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                                </label>
                                <?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
                                <div class="row">
                                  <div class="col-md-6">
                                    <input type="<?php echo $value['subtype']; ?>" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?>" data-subtype="<?php echo $value['subtype']; ?>" data-maxlength = "<?php echo $value['maxlength']; ?>" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>">
                                    <p class="error red-800"></p>
                                    <p class="maxlengtherror red-800"></p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          <?php }
                          break;

                        //display date field
                        case 'date': ?>
                          <div class="col-md-12">
                            <div class="form-group">
                              <?php $datequestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                              <label class="english">
                                <?php echo ($value['field_count'] == 1) ? $datequestion.". ".$value['label'] : $value['label'];
                                echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                              </label>
                              <?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
                              <div class="row">
                                <div class="col-md-6">
                                  <input type="text" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> picker" onkeydown="return false" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>" >
                                  <p class="error red-800"></p>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php break;

                        //display date field
                        case 'month': ?>
                          <div class="col-md-12">
                            <div class="form-group">
                              <?php $monthquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                              <label class="english">
                                <?php echo ($value['field_count'] == 1) ? $monthquestion.". ".$value['label'] : $value['label'];
                                echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                              </label>
                              <?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
                              <div class="row">
                                <div class="col-md-6">
                                  <input type="text" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> monthpicker" onkeydown="return false" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>" >
                                  <p class="error red-800"></p>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php break;
                        
                        //display number field
                        case 'number': ?>
                          <div class="col-md-12">
                            <div class="form-group">
                              <?php $numberquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                              <label class="english"><?php echo ($value['field_count'] == 1) ? $numberquestion.". ".$value['label'] : $value['label'];
                                echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                              </label>
                              <?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
                              <div class="row">
                                <div class="col-md-6">
                                  <?php switch ($value['subtype']) {
                                    case 'desimal': ?>
                                      <input type="text" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> decimal" data-subtype="<?php echo $value['subtype']; ?>" data-maxlength = "<?php echo $value['maxlength']; ?>" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>" >
                                      <?php break;

                                    case 'number': ?>
                                      <input type="text" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> number" data-subtype="<?php echo $value['subtype']; ?>" data-maxlength = "<?php echo $value['maxlength']; ?>" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>" >
                                      <?php break;

                                    case 'latitude': ?>
                                      <input type="text" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> latlong" data-subtype="<?php echo $value['subtype']; ?>" data-maxlength = "<?php echo $value['maxlength']; ?>" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>" >
                                      <?php break;

                                    case 'longitude': ?>
                                      <input type="text" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> latlong" data-subtype="<?php echo $value['subtype']; ?>" data-maxlength = "<?php echo $value['maxlength']; ?>" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>" >
                                      <?php break;

                                    case 'phone': ?>
                                      <input type="tel" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> phone" style="width: 486px;" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>" >
                                      <?php break;
                                    
                                    default: ?>
                                      <input type="text" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> numberfield" data-subtype="<?php echo $value['subtype']; ?>" data-maxlength = "<?php echo $value['maxlength']; ?>" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>" >
                                      <?php break;
                                  } ?>
                                  <p class="error red-800"></p>
                                  <p class="maxlengtherror red-800"></p>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php break;

                        //display radio button
                        case 'radio-group': ?>
                          <div class="col-md-12">
                            <div class="form-group">
                              <?php $radioquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                              <label class="english"><?php echo ($value['field_count'] == 1) ? $radioquestion.". ".$value['label'] : $value['label'];echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                              </label>
                              <?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
                              <div class="form-check">
                                <div class="row">
                                  <?php foreach ($value['options'] as $key => $option) { ?>
                                    <div class="col-md-4">
                                      <label class="<?php if($value['inline'] == 'true' || $value['inline'] == 'TRUE'){ echo "radio-inline"; } ?>" >
                                        <?php if($option['selected'] == 'true' || $option['selected'] == 'TRUE'){ 
                                          $radio_value = "checked"; 
                                        }else{
                                          $radio_value = '';
                                        } ?>
                                        <input type="radio" name="field_<?php echo $value['field_id']; ?>" value = "<?php echo $option['value']; ?>"  style="margin-right: 5px;" data-field_id = "<?php echo $value['field_id']; ?>" data-field_value = "<?php echo $option['value']; ?>" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>" <?php echo $radio_value; ?> >
                                        <span class="english"><?php echo $option['label'] ?></span>
                                      </label>
                                    </div>
                                  <?php } ?>
                                </div>
                              </div>
                              <p class="error red-800"></p>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="row childfields childof<?php echo $value['field_id']; ?>">
                              
                            </div>
                          </div>
                        <?php break;

                        //display checkbox
                        case 'checkbox-group': ?>
                          <div class="col-md-12">
                            <div class="form-group">
                              <?php $checkboxquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                              <label class="english"><?php echo ($value['field_count'] == 1) ? $checkboxquestion.". ".$value['label'] : $value['label'];
                                echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                              </label>
                              <?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : '';  ?>
                              <div class="form-radio row">
                                <?php foreach ($value['options'] as $key => $option) { ?>
                                  <div class="col-md-4">
                                    <label class="<?php if($value['inline'] == 'true' || $value['inline'] == 'TRUE'){ echo "checkbox-inline"; } ?>" >
                                      <?php if($option['selected'] == 'true' || $option['selected'] == 'TRUE'){ 
                                        $checkbox_value = "checked"; 
                                      }else{
                                        $checkbox_value = '';
                                      } ?>
                                      <input type="checkbox" name="field_<?php echo $value['field_id']; ?>[]"  value = "<?php echo $option['value']; ?>" data-field_id = "<?php echo $value['field_id']; ?>" style = "margin-right: 5px;" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>" <?php echo $checkbox_value; ?> ><span class="english"><?php echo $option['label'] ?></span><span class="french hidden" style="padding-left: 6px;">(<?php echo $option['french_label'] ?>)</span>
                                    </label>
                                  </div>
                                <?php } ?>
                              </div>
                              <p class="error red-800"></p>
                            </div>
                          </div>
                          
                          <div class="col-md-12">
                            <div class="row childfields childof<?php echo $value['field_id']; ?>">
                              <?php if(isset($record_details) && ($record_details[$formfield] != NULL)){ ?>
                                <script type="text/javascript">
                                  var calltype = 'onload';
                                  var field_val = '<?php echo $record_details[$formfield]; ?>';
                                  var field_val_array = field_val.split("&#44;");
                                  getchild_field(<?php echo $value['field_id']; ?>, field_val_array, calltype);
                                </script>
                              <?php } ?>
                            </div>
                          </div>
                        <?php break;

                        //display of textarea
                        case 'textarea': ?>
                          <div class="col-md-12">
                            <div class="form-group">
                              <?php $textareaquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                              <label class="english"><?php echo ($value['field_count'] == 1) ? $textareaquestion.". ".$value['label'] : $value['label'];
                                echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                              </label>
                              <?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
                              <div class="row">
                                <div class="col-md-6">
                                  <textarea name="field_<?php echo $value['field_id']; ?>" rows="8" class="<?php echo $value['className']; ?>" data-subtype="<?php echo $value['subtype']; ?>" data-maxlength = "<?php echo $value['maxlength']; ?>" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>"><?php echo (isset($record_details)) ? $record_details[$formfield]  : ''; ?></textarea>
                                  <p class="error red-800"></p>
                                  <p class="maxlengtherror red-800"></p>
                                </div>
                              </div>
                            </div>
                          </div>
                        <?php break;

                        //display of select box
                        case 'select': ?>
                          <div class="col-md-12">
                            <div class="form-group">
                              <?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                              <label class="english"><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
                                echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                              </label>
                              <?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
                              <div class="row">
                                <div class="col-md-6"><?php
                                  if($value['multiple'] == 'true' || $value['multiple'] == 'TRUE'){ ?>
                                    <select name="field_<?php echo $value['field_id']; ?>[]" multiple class="form-control" data-required = "<?php echo ($value['required'] == 1) ? "required" : "notrequired"; ?>" data-field_id = "<?php echo $value['field_id']; ?>" >
                                  <?php  }else{ ?>
                                    <select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required = "<?php echo  ($value['required'] == 1) ? "required" : "notrequired"; ?>" data-field_id = "<?php echo $value['field_id']; ?>">
                                      <option value="">Select an option</option>
                                  <?php  }
                                    foreach ($value['options'] as $key => $option) {
                                      if($option['selected'] == 'true' || $option['selected'] == 'TRUE'){ 
                                        $select_value = "selected"; 
                                      }else{
                                        $select_value = '';
                                      } ?>
                                      <option value = "<?php echo $option['value']; ?>" <?php echo $select_value; ?> ><?php echo $option['label']; ?></option>
                                    <?php } ?>
                                  </select>
                                  <p class="error red-800"></p>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="row childfields childof<?php echo $value['field_id']; ?>">
                              <?php if(isset($record_details) && ($record_details[$formfield] != NULL)){ ?>
                                <script type="text/javascript">
                                  var calltype = 'onload';
                                  var field_val = '<?php echo $record_details[$formfield]; ?>';
                                  var field_val_array = field_val.split("&#44;");
                                  getchild_field(<?php echo $value['field_id']; ?>, field_val_array, calltype);
                                </script>
                              <?php } ?>
                            </div>
                          </div>
                          <?php break;

                        case 'lkp_market': ?>
                          <div class="col-md-12">
                            <div class="form-group">
                              <?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                              <label class="english"><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
                                echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                              </label>
                              <?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
                              <div class="row">
                                <div class="col-md-6">
                                  <select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required = "<?php echo  ($value['required'] == 1) ? "required" : "notrequired"; ?>" data-field_id = "<?php echo $value['field_id']; ?>">
                                    <option value="">Select an option</option>
                                    <?php foreach ($value['market_options'] as $key => $option) { ?>
                                      <option value = "<?php echo $option['id']; ?>" ><?php echo $option['name']; ?></option>
                                    <?php } ?>
                                  </select>
                                  <p class="error red-800"></p>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php break;

                        //display of header
                        case 'header': ?>
                          <div class="col-md-12">
                            <?php switch ($value['subtype']) {
                              case 'h1': ?>
                                <h1 style="margin-top: 0px; margin-bottom: 20px;" class="title">
                                  <?php echo $value['label']; ?>
                                </h1>
                              <?php  break;

                              case 'h2': ?>
                                  <h2 style="margin-top: 0px; margin-bottom: 20px;" class="title">
                                    <?php echo $value['label']; ?>
                                  </h2>
                              <?php  break;

                              case 'h3': ?>
                                  <h3 style="margin-top: 0px; margin-bottom: 20px;" class="title">
                                    <?php echo $value['label']; ?>
                                  </h3>
                              <?php  break;

                              case 'h4': ?>
                                  <h4 style="margin-top: 0px; margin-bottom: 20px;" class="title">
                                    <?php echo $value['label']; ?>
                                  </h4>
                              <?php  break;

                              case 'h5': ?>
                                  <h5 style="margin-top: 0px; margin-bottom: 20px;" class="title">
                                    <?php echo $value['label']; ?>
                                  </h5>
                              <?php  break;
                            } ?>
                          </div>
                          <?php break;
                      }
                    }
                } ?>

                <div class="col-md-12" style="margin-top: 10px;">
                  <div class="form-group">
                      <label>Upload relevant images (if available)</label>
                      <input type="file" multiple name="survey_images[]" id="surv_images" />
                      <div class="help-block pull-right" id="holder" style="border:1px solid #6cc00c;"></div>
                      <p style="font-size: 10px; font-style: italic; color: gray;">
                        File size must be less than 5MB<br/>
                        Only image file types are allowed
                      </p>
                      <p class="error red-800" id="si_err"></p>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="row">
                    <?php if(isset($record_images)){
                      foreach ($record_images as $key => $img) { ?>
                        <div class="col-md-3" style="margin-right: 20px;">
                          <img src="<?php echo base_url(); ?>upload/survey/<?php echo $img['image']; ?>" style="width: 200px;">
                          <!-- <a href="javascript:void(0);" class="" style="text-decoration: none;">Remove</a> -->
                        </div>
                      <?php }
                    } ?>
                  </div>
                </div>
              </div>
          </div>
        </div>
      
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(function(){
    $('body').on('click', '.expand, .hide_child', function(e){
      $elem = $(this);
      if($elem.hasClass('expand')){
        $elem.removeClass('expand');
        $elem.addClass('hide_child');
        $elem.parent().next('div').removeClass('collapse');
      } else {
        $elem.removeClass('hide_child');
        $elem.addClass('expand');
        $elem.parent().next('div').addClass('collapse');
      }      
    });
  })
</script>