jQuery(document).ready(function(){

	if(jQuery('.toplevel_page_dufw_admin_menu_settings_page #message').length > 0)
	{
		jQuery('body').addClass('dufw-bg-overlay');
	}
   	jQuery(document).on("click",".toplevel_page_dufw_admin_menu_settings_page #message button.notice-dismiss",function() {
    	jQuery('body').removeClass('dufw-bg-overlay');
    });

	/*check box*/
    jQuery('#dufw-setting-container .toggle input[type="checkbox"]').click(function(){
        jQuery(this).parent().toggleClass('on');

        if (jQuery(this).parent().hasClass('on')) {
            jQuery(this).parent().children('.label').text('On')
        } else {
            jQuery(this).parent().children('.label').text('Off')
        }
    });


    jQuery('#dufw-setting-container input').focusin (function() {
        jQuery(this).parent().addClass('focus');
    });
    jQuery('#dufw-setting-container input').focusout (function() {
        jQuery(this).parent().removeClass('focus');
    });

    /* check all plugins */
    jQuery(".checkedAllplugin").change(function(){
	    if(this.checked){
	      jQuery(".checkSingleplugin").each(function(){
	        this.checked=true;
	      	jQuery(this).parent().children('.label').text('On');
	        jQuery(this).parent().addClass('on');
	        
	      })              
	    }else{
	      jQuery(".checkSingleplugin").each(function(){
	        this.checked=false;
	        jQuery(this).parent().children('.label').text('Off');
	        jQuery(this).parent().removeClass('on');
	      })              
	    }
  });
  jQuery(".checkSingleplugin").click(function () {
    if (jQuery(this).is(":checked")){
      var isAllChecked = 0;
      jQuery(".checkSingleplugin").each(function(){
        if(!this.checked)
           isAllChecked = 1;
      })              
      if(isAllChecked == 0){ 
      	jQuery(".checkedAllplugin").prop("checked", true); 
      	jQuery(".checkedAllplugin").parent().children('.label').text('On');
      	jQuery(".checkedAllplugin").parent().addClass('on');
      }     
    }else {
      jQuery(".checkedAllplugin").prop("checked", false);
      jQuery(".checkedAllplugin").parent().removeClass('on');
      jQuery(".checkedAllplugin").parent().children('.label').text('Off');
    }
  });

  /* check all Themes */
   jQuery(".checkedAlltheme").change(function(){
	    if(this.checked){
	      jQuery(".checkSingletheme").each(function(){
	        this.checked=true;
	      	jQuery(this).parent().children('.label').text('On');
	        jQuery(this).parent().addClass('on');
	        
	      })              
	    }else{
	      jQuery(".checkSingletheme").each(function(){
	        this.checked=false;
	        jQuery(this).parent().children('.label').text('Off');
	        jQuery(this).parent().removeClass('on');
	      })              
	    }
  });
  jQuery(".checkSingletheme").click(function () {
    if (jQuery(this).is(":checked")){
      var isAllChecked = 0;
      jQuery(".checkSingletheme").each(function(){
        if(!this.checked)
           isAllChecked = 1;
      })              
      if(isAllChecked == 0){ 
      	jQuery(".checkedAlltheme").prop("checked", true); 
      	jQuery(".checkedAlltheme").parent().children('.label').text('On');
      	jQuery(".checkedAlltheme").parent().addClass('on');
      }     
    }else {
      jQuery(".checkedAlltheme").prop("checked", false);
      jQuery(".checkedAlltheme").parent().removeClass('on');
      jQuery(".checkedAlltheme").parent().children('.label').text('Off');
    }
  });

});