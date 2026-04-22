<link rel="stylesheet" href="<?php echo base_url(); ?>include/plugins/daterangepicker/daterangepicker.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>


<style type="text/css">
	.red-800{
		color: red;
	}
</style>
<script type="text/javascript">
	function getchild_field(field_id, field_value, calltype, class_name, fieldtype, groupcount) {
		if(typeof class_name !== 'undefined'){
			var classname = class_name;	
		}else{
			var classname = 'childof'+field_id;
		}
		var survey_id = <?php echo $this->uri->segment(3); ?>;
		$.ajax({
        	url: "<?php echo base_url(); ?>survey/check_childfields",
	        type: "POST",
	        dataType: "json",
	        data : {
				field_id : field_id,
				field_value : field_value,
				calltype : calltype,
				survey_id : survey_id
	        },
	        error : function(){
	        	$('html,body').animate({
		          	scrollTop: $('.'+classname).offset().top - 300
		        }, 500);
		        $('.'+classname).html('<p align="center" class="red-800">Please check your internet connection and try again</p>');
		        setTimeout(function(){
		          	$('.'+classname).empty();
		        }, 5000);
	        },
	        success : function (response) {
	        	if(response.status == 1){
	        		if(response.child_field.length > 0){
	        			var CHILD_HTML = '';
	        			for(var field of response.child_field) {
	        				switch (field.type){
	        					case 'radio-group' :
	        						CHILD_HTML += '<div class="col-md-12">\
										<div class="form-group">\
											<label class="english">'+field.label;
											  	if(field.required == 1){ 
											  		CHILD_HTML += '<font color="red">*</font>';
											  	}
											CHILD_HTML += '</label>';
											if(field.description != null){ 
											  	CHILD_HTML += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
											}
											if(typeof fieldtype !== 'undefined' && fieldtype != ''){
				    							var radiofieldname = "field_"+field.field_id+"["+(groupcount-1)+"]";
				    						}else{
				    							var radiofieldname = "field_"+field.field_id+"";
				    						}
											if(field.inline == 'true' || field.inline == 'TRUE'){ 
											  	CHILD_HTML += '<div class="form-check">\
											    	<div class="row">';
											    		field.options.forEach(function(option, optionindex ){
											    			CHILD_HTML += '<div class="col-md-4">';
											    				var radioclass = (field.inline == "true" || field.inline == "TRUE") ? 'radio-inline' : '';
											    				var inputradioclass = (field.className != '') ? field.className : '';
											    				if(typeof field.value !== 'undefined'){
											    					var columnfield = "field_"+field.field_id;
											    					var selectedvalue = (field.value == option.value) ? "checked" : "";
											    				}else{
											    					var selectedvalue = (option.selected == 'true' || option.selected == 'TRUE')  ? "checked" : "";
											    				}
											    				var requiredval = (field.required == 1) ? "required" : "notrequired";
											          			CHILD_HTML += '<label class="'+radioclass+'" >\
											            			<input type="radio" name="'+radiofieldname+'"  class="" value = "'+option.value+'" '+selectedvalue+' style="margin-right: 5px;" data-field_id = "'+field.field_id+'" data-field_value = "'+option.value+'" data-required = "'+requiredval+'" >'+option.label+'\
											          			</label>\
											        		</div>';
											    		});
											    	CHILD_HTML += '</div>\
											  </div>';
											}else{ 
											  	CHILD_HTML += '<div class="row">';
											  		field.options.forEach(function(option, optionindex ){
										    			CHILD_HTML += '<div class="col-md-4">\
										    				<div class="form-check">';
										    					var radioclass = (field.inline == "true" || field.inline == "TRUE") ? 'radio-inline' : '';
										    					var inputradioclass = (field.className != '') ? field.className : '';
											    				if(typeof field.value !== 'undefined'){
											    					var columnfield = "field_"+field.field_id;
											    					var selectedvalue = (field.value == option.value) ? "checked" : "";
											    				}else{
											    					var selectedvalue = (option.selected == 'true' || option.selected == 'TRUE') ? "checked" : "";
											    				}
											    				var requiredval = (field.required == 1) ? "required" : "notrequired";
											          			CHILD_HTML += '<label class="'+radioclass+'" >\
											            			<input type="radio" name="'+radiofieldname+'"  class="" value = "'+option.value+'" '+selectedvalue+' style="margin-right: 5px;" data-field_id = "'+field.field_id+'" data-field_value = "'+option.value+'" data-required = "'+requiredval+'" >'+option.label+'\
											          			</label>\
											          		</div>\
										        		</div>';
										    		});
											  	CHILD_HTML += '</div>';
											}
											CHILD_HTML += '<p class="error red-800"></p>\
										</div>\
				                    </div>\
				                    <div class="col-md-12">\
				                    	<div class="row childfields childof'+field.field_id+'"></div>\
				                    </div>';
	        						break;

	        					case 'checkbox-group' :
	        						CHILD_HTML += '<div class="col-md-12">\
										<div class="form-group">\
											<label class="english">'+field.label;
											  	if(field.required == 1){ 
											  		CHILD_HTML += '<font color="red">*</font>';
											  	}
											CHILD_HTML += '</label>';
											if(field.description != null){ 
											  	CHILD_HTML += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
											}
											if(typeof fieldtype !== 'undefined' && fieldtype != ''){
				    							var checkboxfieldname = "field_"+field.field_id+"["+(groupcount-1)+"][]";
				    						}else{
				    							var checkboxfieldname = "field_"+field.field_id+"[]";
				    						}
											if(field.inline == 'true' || field.inline == 'TRUE'){ 
											  	CHILD_HTML += '<div class="form-check">\
											    	<div class="row">';
											    		field.options.forEach(function(option, optionindex ){
											    			CHILD_HTML += '<div class="col-md-4">';
											    				var radioclass = (field.inline == "true" || field.inline == "TRUE") ? 'radio-inline' : '';
											    				var inputradioclass = (field.className != '') ? field.className : '';
											    				
											    				if(typeof field.value !== 'undefined'){
											    					var columnfield = "field_"+field.field_id;
											    					var selectedvalue = (field.value == option.value) ? "checked" : "";
											    				}else{
											    					var selectedvalue = (option.selected == 'true' || option.selected == 'TRUE') ? "checked" : "";
											    				}
											    				var requiredval = (field.required == 1) ? "required" : "notrequired";
											          			CHILD_HTML += '<label class="'+radioclass+'" >\
											            			<input type="checkbox" name="'+checkboxfieldname+'"  class="'+inputradioclass+'" value = "'+option.value+'" '+selectedvalue+' style="margin-right: 5px;" data-field_id = "'+field.field_id+'" data-field_value = "'+option.value+'" data-required = "'+requiredval+'" >'+option.label+'\
											          			</label>\
											        		</div>';
											    		});
											    	CHILD_HTML += '</div>\
											  </div>';
											}else{ 
											  	CHILD_HTML += '<div class="row">';
											  		field.options.forEach(function(option, optionindex ){
										    			CHILD_HTML += '<div class="col-md-4">\
										    				<div class="form-check">';
										    					var radioclass = (field.inline == "true" || field.inline == "TRUE") ? 'radio-inline' : '';
										    					var inputradioclass = (field.className != '') ? field.className : '';
											    				
											    				if(typeof field.value !== 'undefined'){
											    					var columnfield = "field_"+field.field_id;
											    					var selectedvalue = (field.value == option.value) ? "checked" : "";
											    				}else{
											    					var selectedvalue = (option.selected == 'true' || option.selected == 'TRUE') ? "checked" : "";
											    				}
											    				var requiredval = (field.required == 1) ? "required" : "notrequired";
											          			CHILD_HTML += '<label class="'+radioclass+'" >\
											            			<input type="checkbox" name="'+checkboxfieldname+'"  class="'+inputradioclass+'" value = "'+option.value+'" '+selectedvalue+' style="margin-right: 5px;" data-field_id = "'+field.field_id+'" data-field_value = "'+option.value+'" data-required = "'+requiredval+'" >'+option.label+'\
											          			</label>\
											          		</div>\
										        		</div>';
										    		});
											  	CHILD_HTML += '</div>';
											}
											CHILD_HTML += '<p class="error red-800"></p>\
										</div>\
				                    </div>\
				                    <div class="col-md-12">\
				                    	<div class="row childfields childof'+field.field_id+'"></div>\
				                    </div>';
	        						break;

	        					case 'number':
	        						CHILD_HTML += '<div class="col-md-12">\
			                        	<div class="form-group">\
			                          		<label>'+field.label;
											  	if(field.required == 1){ 
											  		CHILD_HTML += '<font color="red">*</font>';
											  	}
											CHILD_HTML += '</label>';
											if(field.description != null){
			                            		CHILD_HTML += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
											}
											var inputclass = (field.className != '') ? field.className : '';
	                            			var requiredval = (field.required == 1) ? "required" : "notrequired";
						    				if(typeof field.value !== 'undefined' && field.value != null){
						    					var columnfield = "field_"+field.field_id;
						    					var numberfield_value = (field.value == null) ? '' : field.value;
						    				}else{
						    					var numberfield_value = '';
						    				}
				    						if(typeof fieldtype !== 'undefined' && fieldtype != ''){
				    							var numberfieldname = "field_"+field.field_id+"["+(groupcount-1)+"]";
				    						}else{
				    							var numberfieldname = "field_"+field.field_id;
				    						}

	                             			switch (field.subtype) {
				                                case 'desimal': 
				                                  	CHILD_HTML += '<input type="text" name="'+numberfieldname+'" class=" '+inputclass+' decimal" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'"  value="'+numberfield_value+'" >';
				                                break;

				                                case 'number':
				                                  	CHILD_HTML += '<input type="text" name="'+numberfieldname+'" class=" '+inputclass+' number" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'" value="'+numberfield_value+'" >';
				                                break;

				                                case 'latitude':
				                                  	CHILD_HTML += '<input type="text" name="'+numberfieldname+'" class=" '+inputclass+' latlong" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'" value="'+numberfield_value+'" >';
				                                break;

				                                case 'longitude':
				                                  	CHILD_HTML += '<input type="text" name="'+numberfieldname+'" class=" '+inputclass+' latlong" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'" value="'+numberfield_value+'" >';
				                                break;
				                                
				                                default:
				                                  	CHILD_HTML += '<input type="text" name="'+numberfieldname+'" class=" '+inputclass+' numberfield" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'" value="'+numberfield_value+'" >';
				                                break;
	                              			}
	                              			CHILD_HTML += '<p class="error red-800"></p>\
			                        	</div>\
			                      	</div>';
	        						break;

	        					case 'text' :
	        						CHILD_HTML += '<div class="col-md-12">\
				                      	<div class="form-group">\
					                        <label>'+field.label;
											  	if(field.required == 1){ 
											  		CHILD_HTML += '<font color="red">*</font>';
											  	}
											CHILD_HTML += '</label>';
					                        if(field.description != null){
		                            			CHILD_HTML += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
											}
											var inputclass = (field.className != '') ? field.className : '';
			                          		var requiredval = (field.required == 1) ? "required" : "notrequired";
						    				if(typeof field.value !== 'undefined' && field.value != null){
						    					var columnfield = "field_"+field.field_id;
						    					var textfield_value = field.value;
						    				}else{
						    					var textfield_value = '';
						    				}
						    				
				    						if(typeof fieldtype !== 'undefined' && fieldtype != ''){
				    							var textfieldname = "field_"+field.field_id+"["+(groupcount-1)+"]";
				    						}else{
				    							var textfieldname = "field_"+field.field_id;
				    						}

			                            	if(field.subtype == 'datetime-local'){
			                              		CHILD_HTML += '<input type="text" name="'+textfieldname+'" class="'+inputclass+' datetimepicker5" >';
			                            	}else{
			                              		CHILD_HTML += '<input type="text" name="'+textfieldname+'" class="'+inputclass+'" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'" value="'+textfield_value+'" >';
			                            	}
			                            	CHILD_HTML += '<p class="error red-800"></p>\
				                      	</div>\
				                    </div>';
	        						break;

	        					case 'select':
	        						CHILD_HTML += '<div class="col-md-12">\
										<div class="form-group">\
											<label>'+field.label;
											  	if(field.required == 1){ 
											  		CHILD_HTML += '<font color="red">*</font>';
											  	}
											CHILD_HTML += '</label>';
											if(field.description != null){
		                            			CHILD_HTML += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
											}
											var requiredval = (field.required == 1) ? "required" : "notrequired";
											CHILD_HTML += '<div class="row">\
												<div class="col-md-6">';
										   			if(field.multiple == 'true' || field.multiple == 'TRUE'){
										   				if(typeof fieldtype !== 'undefined' && fieldtype != ''){

										   					var textfieldname = "field_"+field.field_id+"["+(groupcount-1)+"][]";
										   					var group_var = groupcount;
										   					var g_fieldtype = "groupfield";
										   				}else{
										   					var textfieldname = "field_"+field.field_id+"[]";
										   					var group_var = '';
										   					var g_fieldtype = "";
										   				}
										      			CHILD_HTML += '<select name="'+textfieldname+'" multiple class="form-control" data-required = "'+requiredval+'" data-field_id = "'+field.field_id+'" data-fieldtype="'+g_fieldtype+'" data-groupcount="'+group_var+'">';
										    		}else{
										    			if(typeof fieldtype !== 'undefined' && fieldtype != ''){
										   					var textfieldname = "field_"+field.field_id+"["+(groupcount-1)+"]";
										   					var group_var = groupcount;
										   					var g_fieldtype = "groupfield";
										   				}else{
										   					var textfieldname = "field_"+field.field_id+"";
										   					var group_var = '';
										   					var g_fieldtype = "";
										   				}
										      			CHILD_HTML += '<select name="'+textfieldname+'" class="form-control" data-required = "'+requiredval+'" data-field_id = "'+field.field_id+'" data-fieldtype="'+g_fieldtype+'" data-groupcount="'+group_var+'">\
										        			<option value="">Select an option</option>';
										    		}
										    		
										    		field.options.forEach(function(option, optionindex){
										    			if(typeof field.value !== 'undefined'){
									    					if(field.value == option.value){
									    						var optionselected = "selected";
									    					}else{
									    						var optionselected = '';
									    					}
									    				}else{
									    					if(option.selected == "true" || option.selected == "TRUE"){
											    				var optionselected = "selected";
											    			}else{
											    				var optionselected = "";
											    			}
									    				}

										    			CHILD_HTML +='<option value = "'+option.value+'" '+optionselected+'>'+option.label+'</option>';
										    		});
										    		CHILD_HTML += '</select>\
										    	</div>\
										    </div>\
								    		<p class="error red-800"></p>\
										</div>\
				                    </div>\
				                    <div class="col-md-12">';
				                    	if(typeof fieldtype !== 'undefined' && fieldtype != '' ){
				                    		var child_class = "childof"+field.field_id+"_"+groupcount;
				                    	}else{
				                    		var child_class = "childof"+field.field_id;
				                    	}
				                    	CHILD_HTML += '<div class="row childfields '+child_class+'"></div>\
				                    </div>';
	        						break;

	        					case 'header':
				                    CHILD_HTML += '<div class="col-md-12">\
				                    	<'+field.subtype+' class="title" style="margin-top: 0px; margin-bottom: 20px;">'+field.label+'</'+field.subtype+'>\
				                    </div>';
				                	break;

				                case 'date':
				                	CHILD_HTML += '<div class="col-md-12">\
				                      	<div class="form-group">\
					                        <label>'+field.label;
											  	if(field.required == 1){ 
											  		CHILD_HTML += '<font color="red">*</font>';
											  	}
											CHILD_HTML += '</label>';
					                        if(field.description != null){
		                            			CHILD_HTML += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
											}
											var inputclass = (field.className != '') ? field.className : '';
						    				var requiredval = (field.required == 1) ? "required" : "notrequired";
						    				
						    				if(typeof field.value !== 'undefined' && field.value != null){
						    					var columnfield = "field_"+field.field_id;
						    					var textfield_value = field.value;
						    				}else{
						    					var textfield_value = '';
						    				}
			                            	if(field.subtype == 'datetime-local'){
			                              		CHILD_HTML += '<input type="text" name="field_'+field.field_id+'" class="'+inputclass+' datetimepicker5" >';
			                            	}else{
			                              		CHILD_HTML += '<input type="text" name="field_'+field.field_id+'" class="'+inputclass+' picker" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'" value="'+textfield_value+'" autocomplete="off" onkeydown="return false">';
			                            	}
			                            	CHILD_HTML += '<p class="error red-800"></p>\
				                      	</div>\
				                    </div>';
				                	break;

				                case 'month':
				                	CHILD_HTML += '<div class="col-md-12">\
				                      	<div class="form-group">\
					                        <label>'+field.label;
											  	if(field.required == 1){ 
											  		CHILD_HTML += '<font color="red">*</font>';
											  	}
											CHILD_HTML += '</label>';
					                        if(field.description != null){
		                            			CHILD_HTML += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
											}
											var inputclass = (field.className != '') ? field.className : '';
						    				var requiredval = (field.required == 1) ? "required" : "notrequired";
						    				
						    				if(typeof field.value !== 'undefined' && field.value != null){
						    					var columnfield = "field_"+field.field_id;
						    					var textfield_value = field.value;
						    				}else{
						    					var textfield_value = '';
						    				}
						    				CHILD_HTML += '<div class="row">\
						    					<div class="col-md-6">';
					                            	if(field.subtype == 'datetime-local'){
					                              		CHILD_HTML += '<input type="text" name="field_'+field.field_id+'" class="'+inputclass+' datetimepicker5" >';
					                            	}else{
					                              		CHILD_HTML += '<input type="text" name="field_'+field.field_id+'" class="'+inputclass+' monthpicker" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'" value="'+textfield_value+'" autocomplete="off" onkeydown="return false">';
					                            	}
					                            CHILD_HTML += '</div>\
					                        </div>\
			                            	<p class="error red-800"></p>\
				                      	</div>\
				                    </div>';
				                	break;


				                case 'textarea' :
				                	CHILD_HTML += '<div class="col-md-12">\
				                      	<div class="form-group">\
					                        <label>'+field.label;
											  	if(field.required == 1){ 
											  		CHILD_HTML += '<font color="red">*</font>';
											  	}
											CHILD_HTML += '</label>';
					                        if(field.description != null){
		                            			CHILD_HTML += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
											}
											var inputclass = (field.className != '') ? field.className : '';
						    				var requiredval = (field.required == 1) ? "required" : "notrequired";
						    				if(typeof field.value !== 'undefined' && field.value != null){
						    					var columnfield = "field_"+field.field_id;
						    					var textfield_value = field.value;
						    				}else{
						    					var textfield_value = '';
						    				}
						    				CHILD_HTML += '<textarea name="field_'+field.field_id+'" rows="8" class="'+inputclass+'" data-subtype="'+field.subtype+'" data-maxlength = "'+field.maxlength+'" data-required="'+requiredval+'">'+textfield_value+'</textarea>';
			                            	CHILD_HTML += '<p class="error red-800"></p>\
				                      	</div>\
				                    </div>';
				                	break;

				                case 'uploadfile':
				                	CHILD_HTML += '<div class="col-md-12">';
				                		if(field.subtype == 'excel'){
					                      	CHILD_HTML += '<div class="form-group">\
												<label>'+field.label;
												  	if(field.required == 1){ 
												  		CHILD_HTML += '<font color="red">*</font>';
												  	}
												CHILD_HTML += '</label>';
												if(field.description != null){
			                            			CHILD_HTML += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
												}
												var requiredval = (field.required == 1) ? "required" : "notrequired";
												(field.description != null) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>' : '';
					                        	CHILD_HTML += '<div class="row">\
					                          		<div class="col-md-6">\
					                            		<input type="file" class="uploadfile" data-fieldtype = "'+field.type+'" data-fieldsubtype = "'+field.subtype+'"  data-required = "'+requiredval+'" name="field_'+field.field_id+'">\
					                            		<p style="font-size: 10px; font-style: italic; color: gray;">\
					                              			File size must be less than 500KB<br/>\
					                              			Only .xlsx, .xls file type are allowed\
					                            		</p>\
					                           			<p class="error" style="color: red"></p>\
					                          		</div>\
					                        	</div>\
					                      	</div>';
						                }else if(field.subtype == 'document'){
						                	CHILD_HTML += '<div class="form-group">\
												<label>'+field.label;
												  	if(field.required == 1){ 
												  		CHILD_HTML += '<font color="red">*</font>';
												  	}
												CHILD_HTML += '</label>';
												if(field.description != null){
			                            			CHILD_HTML += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
												}
												var requiredval = (field.required == 1) ? "required" : "notrequired";
												(field.description != null) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>' : '';
					                        	CHILD_HTML += '<div class="row">\
					                          		<div class="col-md-6">\
					                            		<input type="file" class="uploaddocument" data-fieldtype = "'+field.type+'" data-fieldsubtype = "'+field.subtype+'"  data-required = "'+requiredval+'" name="field_'+field.field_id+'">\
					                            		<p style="font-size: 10px; font-style: italic; color: gray;">\
					                              			File size must be less than 5MB<br/>\
					                              			Only .pdf file type are allowed\
					                            		</p>\
					                           			<p class="error" style="color: red"></p>\
					                          		</div>\
					                        	</div>\
					                      	</div>';
						                }
				                    CHILD_HTML += '</div>';
				                	break;

				                case 'group':
				                	CHILD_HTML += '<div class="col-md-12 mb-10 mt-10">\
	        							<div class="panel panel-default" style="border: 1px solid #1e9ff2; margin: 0px; font-weight: bold; margin-bottom: 10px;">\
										    <div class="panel-heading">\
										        <h4 class="panel-title expand title">\
										            <span class="pull-right panel-collapse-clickable" data-toggle="collapse" data-parent="#panel'+field.field_id+'" href="#'+field.field_id+'">\
										                <i class="icon-plus success float-right"></i>\
										            </span>\
										            <a data-toggle="collapse" data-parent="#panel'+field.field_id+'" href="#'+field.field_id+'" style="text-decoration: none;">'+field.label+'</a>\
										        </h4>\
										    </div>\
										    <div id="'+field.field_id+'" class="panel-collapse panel-collapse collapse show">\
										        <div class="panel-body">\
										            <div class="row">\
										            	<div class="col-md-6" style="margin-top: -10px; margin-bottom: -20px;">\
										            		<div class="form-group">\
											            		<label>'+(field.description == null || field.description == '' ? "Enter count to repeat the fields" : field.description)+'</label>\
												            	<input type="text" name="group_'+field.field_id+'" class="form-control groupcount" data-groupid = "'+field.field_id+'" data-required="required">\
											            		<p class="error red-800"></p>\
											            	</div>\
										            	</div>\
										            </div>\
										            <div class="row group'+field.field_id+'_div mt-20 groupfields"></div>\
										        </div>\
										    </div>\
										</div>\
									</div>';
				                	break;				                
		        			}
	        			};

	        			$('.'+classname).html(CHILD_HTML);

					    //month picker
						$('.monthpicker').datepicker({
							format: 'yyyy-mm',
							autoclose: true,
							viewMode: "months", 
							minViewMode: "months"
						});
	        		}
	        	}else{
	        		$('html,body').animate({
			          	scrollTop: $('.'+classname).offset().top - 300
			        }, 500);
			        $('.'+classname).html('<p align="center" class="red-800">'+response.msg+'</p>');
			        setTimeout(function(){
			          	$('.'+classname).empty();
			        }, 5000);
	        	}
	        }
        });
	}
</script>

<div class="main-content">
  	<div class="p-4">
  		<a href="<?php echo base_url(); ?>reporting/survey_list" class="btn btn-sm btn-success float-right">Back</a>
    	<h5 style="font-weight: bold;"><?php echo $form_details['title']; ?></h5>
    	<?php echo form_open('', array('id' => 'surveyForm')); ?>
	    	<div class="card">
	      		<div class="card-body">
	      			<div class="row">

	      				<?php if($this->uri->segment(3) == '1'){ ?>
	                    	<div class="col-md-4">
	                    		<div class="form-group">
	                    			<label>Company</label>
	                    			<select class="form-control" name="unit_val">
	                    				<?php foreach ($lkp_company as $key => $value) { ?>
	                    					<option value="<?php echo $value['COMPANY_ID']; ?>"><?php echo $value['TEXT1']; ?></option>
	                    				<?php } ?>
	                    			</select>
	                    		</div>
	                    	</div>

	                    	<div class="col-md-4">
	                    		<div class="form-group">
	                    			<label>Unit</label>
	                    			<select class="form-control" name="unit_val">
	                    				<?php foreach ($lkp_unit as $key => $value) { 
	                    					if($lkp_company[0]['BUKRS'] == $value['company_code']){	?>
	                    						<option value="<?php echo $value['UNIT_ID']; ?>"><?php echo $value['UNIT_NAME']; ?></option>
	                    					<?php }
	                    				} ?>
	                    			</select>
	                    		</div>
	                    	</div>

	                    	<div class="col-md-4">
	                    		<div class="form-group">
	                    			<label>Account group code</label>
	                    			<select class="form-control" name="unit_val">
	                    				<?php foreach ($lkp_account_group_master as $key => $value) {
	                    					if($lkp_unit[0]['UNIT_ID'] == $value['unit_id']){	?>
	                    						<option value="<?php echo $value['account_group_id']; ?>"><?php echo $value['account_group_name']; ?></option>
	                    					<?php }
	                    				} ?>
	                    			</select>
	                    		</div>
	                    	</div>
	                    <?php } ?>

		      			<?php if($this->uri->segment(3) == '2' || $this->uri->segment(3) == '3' || $this->uri->segment(3) == '4'){ ?>
		      				<div class="col-md-12">
		      					<div class="form-group">
		      						<label class="english">
		      							Search by<font color="red">*</font>
		      						</label>
	      							<div class="form-check">
	      								<div class="row">
	      									<div class="col-md-2">
	      										<label class="radio-inline">
	      											<input type="radio" name="searchby" value="Phone Number" style="margin-right: 5px;" data-field_id="1041" data-field_value="No" data-required="required">
	      											<span class="english">Phone Number</span>
	      										</label>
	      									</div>
	      									<div class="col-md-3">
	      										<label class="radio-inline">
	      											<input type="radio" name="searchby" value="Aadhar Number" style="margin-right: 5px;" data-field_id="1041" data-field_value="Yes" data-required="required">
	      											<span class="english">Aadhar Number</span>
	      										</label>
	      									</div>
	      									<div class="col-md-2">
	      										<label class="radio-inline">
	      											<input type="radio" name="searchby" value="Ryot code" style="margin-right: 5px;" data-field_id="1041" data-field_value="Yes" data-required="required" checked>
	      											<span class="english">Ryot code</span>
	      										</label>
	      									</div>
	      									<div class="col-md-2">
	      										<label class="radio-inline">
	      											<input type="radio" name="searchby" value="First Name" style="margin-right: 5px;" data-field_id="1041" data-field_value="Yes" data-required="required">
	      											<span class="english">First Name</span>
	      										</label>
	      									</div>
	      									<div class="col-md-2">
	      										<label class="radio-inline">
	      											<input type="radio" name="searchby" value="Last Name" style="margin-right: 5px;" data-field_id="1041" data-field_value="Yes" data-required="required">
	      											<span class="english">Last Name</span>
	      										</label>
	      									</div>
	      								</div>
	      							</div>
		      						<p class="error red-800"></p>
		      					</div>
		      				</div>

	      					<div class="col-md-12">
								<div class="form-group">
									<select name="search_info" class="form-control search_info" id="search_info" data-required="required">
										<option value="">Select User</option>
									</select>
									<p class="error red-800"></p>
								</div>
							</div>
		      			<?php } ?>
	        		
	          			<?php $i = 1;
	                	foreach ($survey_formfields as $key => $value) {
	                  		$formfield = "field_".$value['field_id'];
	                    	if($value['parent_id'] == null){
	                      		switch ($value['type']) {
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
	                      							<input type="<?php echo $value['subtype']; ?>" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?>" data-subtype="<?php echo $value['subtype']; ?>" data-maxlength = "<?php echo $value['maxlength']; ?>" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>">
	                      							<p class="error red-800"></p>
	                      							<p class="maxlengtherror red-800"></p>
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
	                      						<input type="text" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> datepicker" onkeydown="return false" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>" readonly >
	                      						<p class="error red-800"></p>
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
	                      						<input type="text" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> monthpicker" onkeydown="return false" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>" >
	                      						<p class="error red-800"></p>
			                      			</div>
			                      		</div>
			                      		<?php break;
	                        
	                        		//display number field
			                      	case 'number': ?>
			                      		<div class="col-md-12">
			                      			<div class="form-group">
			                      				<?php $numberquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
			                      				<label class="english">
			                      					<?php echo ($value['field_count'] == 1) ? $numberquestion.". ".$value['label'] : $value['label'];
			                      					echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
			                      				</label>
			                      				<?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
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
			                      		<?php break;

		                        	//display radio button
				                    case 'radio-group': ?>
				                      	<div class="col-md-12">
				                      		<div class="form-group">
				                      			<?php $radioquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
				                      			<label class="english">
				                      				<?php echo ($value['field_count'] == 1) ? $radioquestion.". ".$value['label'] : $value['label']; echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
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
					                      		<div class="form-checkbox row">
					                      			<?php foreach ($value['options'] as $key => $option) { ?>
					                      				<div class="col-md-4">
					                      					<label class="<?php if($value['inline'] == 'true' || $value['inline'] == 'TRUE'){ echo "checkbox-inline"; } ?>" >
					                      						<?php if($option['selected'] == 'true' || $option['selected'] == 'TRUE'){ 
					                      							$checkbox_value = "checked"; 
						                      					}else{
						                      						$checkbox_value = '';
						                      					} ?>
					                      						<input type="checkbox" name="field_<?php echo $value['field_id']; ?>[]"  value = "<?php echo $option['value']; ?>" data-field_id = "<?php echo $value['field_id']; ?>" style = "margin-right: 5px;" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>" <?php echo $checkbox_value; ?> ><?php echo $option['label'] ?>
					                      					</label>
					                      				</div>
					                      			<?php } ?>
					                      		</div>
					                      		<p class="error red-800"></p>
					                      	</div>
					                  	</div>

					                  	<div class="col-md-12">
					                  		<div class="row childfields childof<?php echo $value['field_id']; ?>">
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
				                  				<textarea name="field_<?php echo $value['field_id']; ?>" rows="8" class="<?php echo $value['className']; ?>" data-subtype="<?php echo $value['subtype']; ?>" data-maxlength = "<?php echo $value['maxlength']; ?>" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>"><?php echo (isset($record_details)) ? $record_details[$formfield]  : ''; ?></textarea>
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
	                              				<label class="english">
	                              					<?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label']; echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
	                              				</label>
	                              				<?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
	                            				<?php if($value['multiple'] == 'true' || $value['multiple'] == 'TRUE'){ ?>
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
		                          		<div class="col-md-12">
		                          			<div class="row childfields childof<?php echo $value['field_id']; ?>">

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

			                          			default: ?>
			                          				<h5 style="margin-top: 0px; margin-bottom: 20px;" class="title">
			                          					<?php echo $value['label']; ?>
			                          				</h5>
			                          				<?php break;

		                          			} ?>
		                          		</div>
	                          			<?php break;

	                          		case 'lkp_title': ?>
		                          		<div class="col-md-12">
		                          			<div class="form-group">
		                          				<?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
		                          				<label class="english"><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
		                          					echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
		                          				</label>
		                          				<?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
	                          					<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required = "<?php echo  ($value['required'] == 1) ? "required" : "notrequired"; ?>" data-field_id = "<?php echo $value['field_id']; ?>">
	                          						<option value="">Select an option</option>
	                          						<?php foreach ($value['options'] as $key => $option) { ?>
	                          							<option value = "<?php echo $option['title_id']; ?>" ><?php echo $option['title_name']; ?></option>
	                          						<?php } ?>
	                          					</select>
	                          					<p class="error red-800"></p>
			                          		</div>
			                          	</div>
			                          	<?php break;

			                        case 'lkp_title': ?>
		                          		<div class="col-md-12">
		                          			<div class="form-group">
		                          				<?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
		                          				<label class="english"><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
		                          					echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
		                          				</label>
		                          				<?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
	                          					<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required = "<?php echo  ($value['required'] == 1) ? "required" : "notrequired"; ?>" data-field_id = "<?php echo $value['field_id']; ?>">
	                          						<option value="">Select an option</option>
	                          						<?php foreach ($value['options'] as $key => $option) { ?>
	                          							<option value = "<?php echo $option['title_id']; ?>" ><?php echo $option['title_name']; ?></option>
	                          						<?php } ?>
	                          					</select>
	                          					<p class="error red-800"></p>
			                          		</div>
			                          	</div>
			                          	<?php break;

			                        case 'lkp_gender': ?>
		                          		<div class="col-md-12">
		                          			<div class="form-group">
		                          				<?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
		                          				<label class="english"><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
		                          					echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
		                          				</label>
		                          				<?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
	                          					<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required = "<?php echo  ($value['required'] == 1) ? "required" : "notrequired"; ?>" data-field_id = "<?php echo $value['field_id']; ?>">
	                          						<option value="">Select an option</option>
	                          						<?php foreach ($value['options'] as $key => $option) { ?>
	                          							<option value = "<?php echo $option['GENDER_ID']; ?>" ><?php echo $option['GENDER_DESC']; ?></option>
	                          						<?php } ?>
	                          					</select>
	                          					<p class="error red-800"></p>
			                          		</div>
			                          	</div>
			                          	<?php break;

			                        case 'lkp_village': ?>
		                          		<div class="col-md-12">
		                          			<div class="form-group">
		                          				<?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
		                          				<label class="english"><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
		                          					echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
		                          				</label>
		                          				<?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
	                          					<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required = "<?php echo  ($value['required'] == 1) ? "required" : "notrequired"; ?>" data-field_id = "<?php echo $value['field_id']; ?>">
	                          						<option value="">Select an option</option>
	                          						<?php foreach ($value['options'] as $key => $option) { ?>
	                          							<option value = "<?php echo $option['VILLAGE_ID']; ?>" ><?php echo $option['VNAME']; ?></option>
	                          						<?php } ?>
	                          					</select>
	                          					<p class="error red-800"></p>
			                          		</div>
			                          	</div>
			                          	<?php break;

			                        case 'lkp_ifsc': ?>
		                          		<div class="col-md-12">
		                          			<div class="form-group">
		                          				<?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
		                          				<label class="english"><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
		                          					echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
		                          				</label>
		                          				<?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
	                          					<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required = "<?php echo  ($value['required'] == 1) ? "required" : "notrequired"; ?>" data-field_id = "<?php echo $value['field_id']; ?>">
	                          						<option value="">Select an option</option>
	                          						<?php foreach ($value['options'] as $key => $option) { ?>
	                          							<option value = "<?php echo $option['IFSC_ID']; ?>" ><?php echo $option['IFSC_CODE']; ?></option>
	                          						<?php } ?>
	                          					</select>
	                          					<p class="error red-800"></p>
			                          		</div>
			                          	</div>
			                          	<?php break;

			                        case 'lkp_planting_season': ?>
		                          		<div class="col-md-12">
		                          			<div class="form-group">
		                          				<?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
		                          				<label class="english"><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
		                          					echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
		                          				</label>
		                          				<?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
	                          					<select name="field_<?php echo $value['field_id']; ?>" class="form-control lkp_planting_season" data-required = "<?php echo  ($value['required'] == 1) ? "required" : "notrequired"; ?>" data-field_id = "<?php echo $value['field_id']; ?>">
	                          						<?php foreach ($value['options'] as $key => $option) { ?>
	                          							<option value = "<?php echo $option['PLANTSEA_ID']; ?>" ><?php echo $option['PLANTSEA']; ?></option>
	                          						<?php } ?>
	                          					</select>
	                          					<p class="error red-800"></p>
			                          		</div>
			                          	</div>
			                          	<?php break;

			                        case 'lkp_crushing_season': 
			                        	$crushing_season_list = json_encode($value['options']); ?>
		                          		<div class="col-md-12">
		                          			<div class="form-group">
		                          				<?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
		                          				<label class="english"><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
		                          					echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
		                          				</label>
		                          				<?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
	                          					<select name="field_<?php echo $value['field_id']; ?>" class="form-control lkp_crushing_season" data-required = "<?php echo  ($value['required'] == 1) ? "required" : "notrequired"; ?>" data-field_id = "<?php echo $value['field_id']; ?>">
	                          						<?php foreach ($value['options'] as $key => $option) { 
	                          							if($key == 0){	?>
	                          								<option value = "<?php echo $option['ZYEAR_ID']; ?>" ><?php echo $option['ZYEAR']; ?></option>
	                          							<?php }
	                          						} ?>
	                          					</select>
	                          					<p class="error red-800"></p>
			                          		</div>
			                          	</div>
			                          	<?php break;

			                        case 'lkp_category': ?>
		                          		<div class="col-md-12">
		                          			<div class="form-group">
		                          				<?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
		                          				<label class="english"><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
		                          					echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
		                          				</label>
		                          				<?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
	                          					<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required = "<?php echo  ($value['required'] == 1) ? "required" : "notrequired"; ?>" data-field_id = "<?php echo $value['field_id']; ?>">
	                          						<option value="">Select an option</option>
	                          						<?php foreach ($value['options'] as $key => $option) { ?>
	                          							<option value = "<?php echo $option['CATEGORY_ID']; ?>" ><?php echo $option['Category_Name']; ?></option>
	                          						<?php } ?>
	                          					</select>
	                          					<p class="error red-800"></p>
			                          		</div>
			                          	</div>
			                          	<?php break;

			                        case 'lkp_variety': ?>
		                          		<div class="col-md-12">
		                          			<div class="form-group">
		                          				<?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
		                          				<label class="english"><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
		                          					echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
		                          				</label>
		                          				<?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
	                          					<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required = "<?php echo  ($value['required'] == 1) ? "required" : "notrequired"; ?>" data-field_id = "<?php echo $value['field_id']; ?>">
	                          						<option value="">Select an option</option>
	                          						<?php foreach ($value['options'] as $key => $option) { ?>
	                          							<option value = "<?php echo $option['VARIETY_ID']; ?>" ><?php echo $option['VARDESC']; ?> - <?php echo $option['VARIETY']; ?></option>
	                          						<?php } ?>
	                          					</select>
	                          					<p class="error red-800"></p>
			                          		</div>
			                          	</div>
			                          	<?php break;

			                        case 'lkp_plot_type': ?>
		                          		<div class="col-md-12">
		                          			<div class="form-group">
		                          				<?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
		                          				<label class="english"><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
		                          					echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
		                          				</label>
		                          				<?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
	                          					<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required = "<?php echo  ($value['required'] == 1) ? "required" : "notrequired"; ?>" data-field_id = "<?php echo $value['field_id']; ?>">
	                          						<option value="">Select an option</option>
	                          						<?php foreach ($value['options'] as $key => $option) { ?>
	                          							<option value = "<?php echo $option['PLOT_TYPE_ID']; ?>" ><?php echo $option['PLOTDESC']; ?></option>
	                          						<?php } ?>
	                          					</select>
	                          					<p class="error red-800"></p>
			                          		</div>
			                          	</div>
			                          	<?php break;

			                        case 'lkp_irrigation_source': ?>
		                          		<div class="col-md-12">
		                          			<div class="form-group">
		                          				<?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
		                          				<label class="english"><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
		                          					echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
		                          				</label>
		                          				<?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
	                          					<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required = "<?php echo  ($value['required'] == 1) ? "required" : "notrequired"; ?>" data-field_id = "<?php echo $value['field_id']; ?>">
	                          						<option value="">Select an option</option>
	                          						<?php foreach ($value['options'] as $key => $option) { ?>
	                          							<option value = "<?php echo $option['IRR_SOURCE_ID']; ?>" ><?php echo $option['IRRSDESC']; ?></option>
	                          						<?php } ?>
	                          					</select>
	                          					<p class="error red-800"></p>
			                          		</div>
			                          	</div>
			                          	<?php break;

			                        case 'lkp_spacing_code': ?>
		                          		<div class="col-md-12">
		                          			<div class="form-group">
		                          				<?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
		                          				<label class="english"><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
		                          					echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
		                          				</label>
		                          				<?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
	                          					<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required = "<?php echo  ($value['required'] == 1) ? "required" : "notrequired"; ?>" data-field_id = "<?php echo $value['field_id']; ?>">
	                          						<option value="">Select an option</option>
	                          						<?php foreach ($value['options'] as $key => $option) { ?>
	                          							<option value = "<?php echo $option['SPACE_CODE_ID']; ?>" ><?php echo $option['SPACE_NAME']; ?></option>
	                          						<?php } ?>
	                          					</select>
	                          					<p class="error red-800"></p>
			                          		</div>
			                          	</div>
			                          	<?php break;

			                        case 'lkp_crop_type': ?>
		                          		<div class="col-md-12">
		                          			<div class="form-group">
		                          				<?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
		                          				<label class="english"><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
		                          					echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
		                          				</label>
		                          				<?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
	                          					<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required = "<?php echo  ($value['required'] == 1) ? "required" : "notrequired"; ?>" data-field_id = "<?php echo $value['field_id']; ?>">
	                          						<option value="">Select an option</option>
	                          						<?php foreach ($value['options'] as $key => $option) { ?>
	                          							<option value = "<?php echo $option['CROP_TYPE_ID']; ?>" ><?php echo $option['PLANTDESC']; ?></option>
	                          						<?php } ?>
	                          					</select>
	                          					<p class="error red-800"></p>
			                          		</div>
			                          	</div>
			                          	<?php break;

			                        case 'lkp_irrogation_method': ?>
		                          		<div class="col-md-12">
		                          			<div class="form-group">
		                          				<?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
		                          				<label class="english"><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
		                          					echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
		                          				</label>
		                          				<?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
	                          					<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required = "<?php echo  ($value['required'] == 1) ? "required" : "notrequired"; ?>" data-field_id = "<?php echo $value['field_id']; ?>">
	                          						<option value="">Select an option</option>
	                          						<?php foreach ($value['options'] as $key => $option) { ?>
	                          							<option value = "<?php echo $option['IRR_METHOD_ID']; ?>" ><?php echo $option['IRRDESC']; ?></option>
	                          						<?php } ?>
	                          					</select>
	                          					<p class="error red-800"></p>
			                          		</div>
			                          	</div>
			                          	<?php break;

			                        case 'lkp_soil_type': ?>
		                          		<div class="col-md-12">
		                          			<div class="form-group">
		                          				<?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
		                          				<label class="english"><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
		                          					echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
		                          				</label>
		                          				<?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
	                          					<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required = "<?php echo  ($value['required'] == 1) ? "required" : "notrequired"; ?>" data-field_id = "<?php echo $value['field_id']; ?>">
	                          						<option value="">Select an option</option>
	                          						<?php foreach ($value['options'] as $key => $option) { ?>
	                          							<option value = "<?php echo $option['SOIL_TYPE_ID']; ?>" ><?php echo $option['SOIL_TYPE_DESC']; ?></option>
	                          						<?php } ?>
	                          					</select>
	                          					<p class="error red-800"></p>
			                          		</div>
			                          	</div>
			                          	<?php break;

			                       	case 'lkp_plantation_method': ?>
		                          		<div class="col-md-12">
		                          			<div class="form-group">
		                          				<?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
		                          				<label class="english"><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
		                          					echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
		                          				</label>
		                          				<?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
	                          					<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required = "<?php echo  ($value['required'] == 1) ? "required" : "notrequired"; ?>" data-field_id = "<?php echo $value['field_id']; ?>">
	                          						<option value="">Select an option</option>
	                          						<?php foreach ($value['options'] as $key => $option) { ?>
	                          							<option value = "<?php echo $option['PLANTATION_ID']; ?>" ><?php echo $option['PLADESC']; ?></option>
	                          						<?php } ?>
	                          					</select>
	                          					<p class="error red-800"></p>
			                          		</div>
			                          	</div>
			                          	<?php break;
			                          	

			                        //display date field
			                      	case 'document_date': ?>
			                      		<div class="col-md-12">
			                      			<div class="form-group">
			                      				<?php $datequestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
			                      				<label class="english">
			                      					<?php echo ($value['field_count'] == 1) ? $datequestion.". ".$value['label'] : $value['label'];
			                      					echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
			                      				</label>
			                      				<?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
	                      						<input type="text" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?>" onkeydown="return false" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>" value="<?php echo date('Y-m-d'); ?>" readonly>
	                      						<p class="error red-800"></p>
			                      			</div>
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
	                </div>	            
	            </div>
	        </div>

	        <div class="row">
	        	<div class="col-md-12">
					<div class="text-right">
						<button type="submit" class="btn btn-sm btn-success">Collect Survey Data</button>
					</div>
				</div>
			</div>
	    <?php echo form_close(); ?>
    </div>
</div>

<script src="<?php echo base_url(); ?>include/plugins/daterangepicker/daterangepicker.js"></script>

<script type="text/javascript">
	$(function(){
		$('.datepicker').daterangepicker({
			singleDatePicker: true,
			showDropdowns: true,
			minYear: 2001,
			locale:{
				format:'YYYY-MM-DD',
			},
			maxYear: parseInt(moment().format('YYYY'),10)
		});

		<?php if($this->uri->segment(3) == 2){ ?>
			$('.lkp_planting_season').on('change', function(){
				var crushing_season_list = <?php echo $crushing_season_list; ?>;

				var planting_val = $(this).val();

				var options = '';
				crushing_season_list.forEach(function(season, index){
					if(planting_val == season.ZYEAR_ID){
						options += `<option value="`+season.ZYEAR_ID+`">`+season.ZYEAR+`</option>`;
					}
				});

				$('.lkp_crushing_season').html(options);
			});
		<?php } ?>

		$("#search_info").select2({
			ajax: {
				url: "<?php echo base_url(); ?>survey/get_ryotinfo",
				type: "post",
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
              			searchTerm: params.term, // search term
              			searchby: $('input[name="searchby"]:checked').val()
              		};
              	},
              	processResults: function (response) {
              		return {
              			results: response
              		};
              	},
              	cache: true
            }
        });

		//to check the value is number or not
    	$('body').on('keyup', '.numberfield', function(){
			$(this).closest('.form-group').find('.error').html('');
			if($(this).val().length > 0){
				if (!/^(\+|-)?(\d*\.?\d*)$/.test(this.value)) { // a non–digit was entered
					$(this).closest('.form-group').find('.error').html('This field contains only numbers and perfect decimals.');
					$(this).val('');
				}else{
					$(this).closest('.form-group').find('.error').empty();
				}
			}
		});

    	//to check value is perfect decimal number or not
		$('body').on('keyup', '.desimal', function(){
			$(this).closest('.form-group').find('.error').html('');
			if($(this).val().length > 0){
				if(!/^(\d*\.?\d*)$/.test($(this).val())){
					$(this).closest('.form-group').find('.error').html('Please! Enter only number');
	            }else if (!/^[0-9]+(\.\d{1,2})?$/.test($(this).val())) {
	            	$(this).closest('.form-group').find('.error').html('Field can contain only proper decimal number.');
	            }
	        }
		});

		//to check value is perfect decimal number or not
		$('body').on('keyup', '.latlong', function(){
			$(this).closest('.form-group').find('.error').html('');
		    if($(this).val().length > 0 && ($(this).val() != 0)){
		        if (/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}$/.test($(this).val())) {
		          	$(this).closest('.form-group').find('.error').empty();
		        } else {
		          	$(this).closest('.form-group').find('.error').html('Please provide a valid number.');
		        }
		    }
		});

		//to check value is perfect number
		$('body').on('keyup', '.number', function(){
			$(this).closest('.form-group').find('.error').html('');
			if($(this).val().length > 0){
				if (/^\d+$/.test($(this).val())) {
					$(this).closest('.form-group').find('.error').empty();
				} else {
					$(this).val('');
					$(this).closest('.form-group').find('.error').html('Please provide a valid number.');
				}
			}
		});

		// on blur: validate
		$('body').on('blur', '.phone', function(){
			phonenumbererror = 0;
			var telInput = $(this);
			if ($.trim(telInput.val())) {
				if (telInput.intlTelInput("isValidNumber")) {
					$('.error').html('');
					$('.phonenumber').html('<span id="valid-msg" style="color: #00C900;">✓ Valid</span>');
				} else {
					$('.error').html('');
					$('.phonenumber').html('<span id="error-msg">Invalid number</span>');
					phonenumbererror++;
				}
			}
		});

		//to get child fields on change of radio field 
    	$('body').on('change', 'input[type=radio]', function() {
	        var field_id = $(this).attr("data-field_id");
	        var field_value = $(this).attr("data-field_value");
	        var fieldtype = $(this).attr("data-fieldtype");
	        var groupcount = $(this).attr("data-groupcount");

	        var fieldtype = $(this).attr("data-fieldtype");

			if(fieldtype == 'groupfield' && typeof fieldtype !== 'undefined'){
				var classname = 'childof'+field_id+'_'+groupcount;
			}else{
				var classname = 'childof'+field_id;
			}

	        $('.'+classname).html('');

	        var calltype = 'onchange';

	        console.log(classname);

	        getchild_field(field_id, field_value, calltype, classname, fieldtype, groupcount);
	    });

    	//to get child fields on change of checkbox field 
	    $('body').on('change', 'input[type=checkbox]', function() {
	        var field_id = $(this).attr("data-field_id");
	        var name = $(this).attr("name");
	        var calltype = 'onchange';

	        var fieldtype = $(this).attr("data-fieldtype");
	        var groupcount = $(this).attr("data-groupcount");

			if(fieldtype == 'groupfield' && typeof fieldtype !== 'undefined'){
				var classname = 'childof'+field_id+'_'+groupcount;
			}else{
				var classname = 'childof'+field_id;
			}
	        $('.'+classname).html('');

	        var checkedvalues = [];
            $.each($("input[name='"+name+"']:checked"), function(){
                checkedvalues.push($(this).val());
            });

            var field_value = checkedvalues;

            if(field_value != ''){
	            getchild_field(field_id, field_value, calltype);
            }
	    });

	    //to get child fields on change of selectbox field 
	    $('body').on('change', 'select', function() {
	        var field_id = $(this).attr("data-field_id");
	        var name = $(this).attr("name");
	        var calltype = 'onchange';

	        var fieldtype = $(this).attr("data-fieldtype");
	        var groupcount = $(this).attr("data-groupcount");

			if(fieldtype == 'groupfield' && typeof fieldtype !== 'undefined'){
				var classname = 'childof'+field_id+'_'+groupcount;
			}else{
				var classname = 'childof'+field_id;
			}
	        $('.'+classname).html('');

	        var checkedvalues = [];
            $.each($("option:selected", this) , function(){
                checkedvalues.push($(this).val());
            });

            var field_value = checkedvalues;

            if(field_value != ''){
	            getchild_field(field_id, field_value, calltype, classname, fieldtype, groupcount);
            }
	    });

	    $('body').on('submit', '#surveyForm', function(event) {
			event.preventDefault();
			$form = $(this);
			$('.error').html('');
			$form.find('button[type="submit"]').prop('disabled', true);

			var surveycount = 0;      		

			$('input[type=file]', '#surveyForm').each(function() {
				var fieldtype = $(this).data("fieldtype");
				var fieldsubtype = $(this).data("fieldsubtype");
				var requiredvalue = $(this).data("required");

				if(fieldsubtype == 'document'){
					if(fieldtype == 'uploadfile' && typeof fieldtype !== 'undefined'){
						if(requiredvalue == 'required'){
							if($.trim($(this).val()).length === 0){
								$(this).closest('.form-group').find('.error').html('This field is required');
								surveycount++;
							}
						}

						if($(this).val() != ''){
							var fileUpload = $(this)[0].files[0];
							var fileTypes = ['pdf'];
							var extension = fileUpload.name.split('.').pop().toLowerCase();
							var error = [];

							if(fileTypes.indexOf(extension) == '-1') {
								error.push('Please upload a valid pdf file.');
								surveycount++;
							}
							if(fileUpload.size > 5242880) {
								error.push('Upload file size should be less than 5MB');
								surveycount++;
							}
							$(this).closest('.form-group').find('.error').html(error.join('<br/>'));
						}
					}
				}

				if(fieldsubtype == 'excel'){
					if(fieldtype == 'uploadfile' && typeof fieldtype !== 'undefined'){
						if(requiredvalue == 'required'){
							if($.trim($(this).val()).length === 0){
								$(this).closest('.form-group').find('.error').html('This field is required');
								surveycount++;
							}
						}

						if($(this).val() != ''){
							var fileUpload = $(this)[0].files[0];
							var fileTypes = ['xlsx', 'xls'];
							var extension = fileUpload.name.split('.').pop().toLowerCase();
							var error = [];

							if(fileTypes.indexOf(extension) == '-1') {
								error.push('Please upload a valid excel file.');
								surveycount++;
							}
							if(fileUpload.size > 5242880) {
								error.push('Upload file size should be less than 5MB');
								surveycount++;
							}
							$(this).closest('.form-group').find('.error').html(error.join('<br/>'));
						}
					}
				}
			});

			$('input[type=text]', '#surveyForm').each(function() {
				var requiredvalue = $(this).data("required");
				var subtypevalue = $(this).data("subtype");
				var maxvalue = $(this).data("maxlength");
				if(requiredvalue == 'required'){
					if($.trim($(this).val()).length === 0){
						$(this).closest('.form-group').find('.error').html('This field is required');
						surveycount++;
					}
				}
				if(subtypevalue == 'number' || subtypevalue == 'phone' || subtypevalue == 'numberfield' || subtypevalue == 'desimal' || subtypevalue == 'latitude' || subtypevalue == 'longitude'){
					switch (subtypevalue){
						case 'numberfield':
						if($.trim($(this).val()).length > 0){
				                if (!/^(\+|-)?(\d*\.?\d*)$/.test(this.value)) { // a non–digit was entered
				                	$(this).closest('.form-group').find('.error').html('This field contains only numbers and perfect decimals.');
				                	surveycount++;
				                }else{
				                	$(this).closest('.form-group').find('.error').empty();
				                }
				            }
				            break;

				            case 'phone':
				            case 'number':
					            if($.trim($(this).val()).length > 0){
					            	if (/^\d+$/.test($(this).val())) {
					            		$(this).closest('.form-group').find('.error').empty();
					            	} else {
					            		$(this).val('');
					            		$(this).closest('.form-group').find('.error').html('Please provide a valid number.');
					            		surveycount++;
					            	}
					            }
					            break;

				            case 'latitude':
					            if($.trim($(this).val()).length > 0){
					            	if (/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}$/.test($(this).val())) {
					            		$(this).closest('.form-group').find('.error').empty();
					            	} else {
					            		$(this).closest('.form-group').find('.error').html('Please provide a valid number.');
					            		surveycount++;
					            	}
					            }
					            break;

				            case 'longitude':
					            if($.trim($(this).val()).length > 0){
					            	if (/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}$/.test($(this).val())) {
					            		$(this).closest('.form-group').find('.error').empty();
					            	} else {
					            		$(this).closest('.form-group').find('.error').html('Please provide a valid number.');
					            		surveycount++;
					            	}
					            }
					            break;

				            case 'desimal':
				            if($.trim($(this).val()).length > 0){
				            	if(!/^(\d*\.?\d*)$/.test($(this).val())){
				            		$(this).closest('.form-group').find('.error').html('Please! Enter only number');
				            		surveycount++;
				            	}else if (!/^[0-9]+(\.\d{1,2})?$/.test($(this).val())) {
				            		$(this).closest('.form-group').find('.error').html('Field can contain only proper decimal number.');
				            		surveycount++;
				            	}
				            }
				            break;
				        }
				    }

				    if(subtypevalue == 'email' && $(this).val().length > 0){
				    	if( !isValidEmailAddress( $(this).val())) { 
				    		$(this).closest('.form-group').find('.error').html('Invalid email id');
				    		surveycount++; 
				    	}
				    }

				    if($.trim($(this).val()).length > maxvalue){
				    	$(this).closest('.form-group').find('.error').html('Please! Enter upto '+maxvalue+' character/number');
				    	surveycount++;
				    }
				});

			$('textarea', '#surveyForm').each(function() {
				var requiredvalue = $(this).data("required");
				var subtypevalue = $(this).data("subtype");
				var maxvalue = $(this).data("maxlength");

				if(requiredvalue == 'required'){
					if($.trim($(this).val()).length === 0){
						$(this).closest('.form-group').find('.error').html('This field is required');
						surveycount++;
					}
				}
				if($.trim($(this).val()).length > maxvalue){
					$(this).closest('.form-group').find('.error').html('Please! Enter upto '+maxvalue+' character/number');
					surveycount++;
				}
			});

			$('input[type=radio]', '#surveyForm').each(function() {
				var requiredvalue = $(this).data("required");
				var subtypevalue = $(this).data("subtype");
				if(requiredvalue == 'required'){
					var name = $(this).attr("name");
					if($("input:radio[name="+name+"]:checked").length == 0){
						$(this).closest('.form-group').find('.error').html('This field is required');
						surveycount++;
					}
				}
			});

			$('select', '#surveyForm').each(function() {
				var requiredvalue = $(this).data("required");
				var subtypevalue = $(this).data("subtype");

				if(requiredvalue == 'required'){
					if($.trim($(this).val()).length == 0){
						$(this).closest('.form-group').find('.error').html('This field is required');
						surveycount++;
					}
				}
			});

			$('input[type=checkbox]', '#surveyForm').each(function() {
				var requiredvalue = $(this).data("required");
				var subtypevalue = $(this).data("subtype");
				if(requiredvalue == 'required'){
					var name = $(this).attr("name");
					if($("input:checkbox[name='"+name+"']:checked").length == 0){
						$(this).closest('.form-group').find('.error').html('This field is required');
						surveycount++;
					}
				}
			});

			// var images_count = $("#surv_images")[0].files.length
			// if (images_count > maxpics){
			// 	$("input[type='file']").closest('.form-group').find('.error').html('Maximum files to be choose is '+maxpics+'');
			// 	surveycount++;
			// }

			if(surveycount > 0) {
				swal({
					title: "Warning!",
					text: "Please clear all the errors!",
					type: "error"
				}, function() {
					$('html,body').animate({
						scrollTop: $(".card-body").offset().top - 300
					}, 500);
				});
				$form.find('button[type="submit"]').prop('disabled', false);
				return false;
			}

			var survey_id = '<?php echo $this->uri->segment(3); ?>';

			switch(survey_id){
				case '1':
					var data_url = '<?php echo base_url(); ?>survey/ryot_regitration';
					break;

				case '2':
					var data_url = '<?php echo base_url(); ?>survey/plot_regitsration';
					break;

				case '3':
					var data_url = '<?php echo base_url(); ?>survey/plot_agreement';
					break;

				case '4':
					var data_url = '<?php echo base_url(); ?>survey/plot_kml';
					break;

				default :
					var data_url = '<?php echo base_url(); ?>survey/survey_insert';
					break;

			}
			
			var formdata = new FormData($('#surveyForm')[0]);
			formdata.append('survey_id', <?php echo $this->uri->segment(3); ?>);
			$.ajax({
				url: data_url,
				type: 'POST',
				dataType : 'json',
				data: formdata,
				processData: false,
				contentType: false,
				complete: function(data) {
					var csrfData = JSON.parse(data.responseText);
					ajaxData[csrfData.csrfName] = csrfData.csrfHash;
					if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
						$('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
					}
				},
				error: function() {
					swal({
						title: "Network Error!",
						text: "Could not establish connection to server. Please refresh the page and try again.",
						type: "error"
					}, function() {
						$('html,body').animate({
							scrollTop: $(".card-body").offset().top - 300
						}, 500);
						$form.find('button[type="submit"]').prop('disabled', false);
					});				        
				},
				success : function(response){
					if(response.status == 1){
						swal({
							title: "Success!",
							text: ""+response.msg+"!",
							type: "success"
						}, function() {
							$('html,body').animate({
								scrollTop: $(".card-body").offset().top - 300
							}, 500);

							$('#surveyForm input[type="tel"]').val('');
							$('#surveyForm input[type="text"]').val('');
							$('#surveyForm input[type="email"]').val('');
							$('#surveyForm select').val('');
							$('#surveyForm input[type="file"]').val('');
							$('#surveyForm textarea').val('');
							$('#surveyForm input[type="checkbox"]').each(function() {
								this.checked = false;
							});
							$('#surveyForm input[type="radio"]').each(function() {
								this.checked = false;
							});
							window.location.reload();
						});
					}else{
						swal({
							title: "Error!",
							text: ""+response.msg+"!",
							type: "error"
						}, function() {
							$('html,body').animate({
								scrollTop: $(".card-body").offset().top - 300
							}, 500);
							$form.find('button[type="submit"]').prop('disabled', false);
						});
					}
				}
			});
		});
	});
</script>