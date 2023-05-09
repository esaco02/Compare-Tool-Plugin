<?
/*
 * THD code
 * Widget Name: compare-tool-plugin-settings-thd
 * Version: 1.0
 * Developed By: Ernesto Saborio Cordoba
 * Collaborators:
 * Date of Create: 05/04/23
 */ 
?>
<style>
    #compare_tool_plugin_settings h1 {
        font-size: 30px;
        font-weight: 800;
    }

    .compare-tool-settings-fields .label_head {
        border-radius: 4px 4px 0 0;
        background-color:rgb(24, 46, 69); 
        color:rgb(255, 255, 255);
        font-size: 16px;
        font-weight: 600;
        text-align: center;
    }   
    
    .compare-tool-settings-fields .label_body {
        width: 100%;
        padding: 15px 0;
        background-color:rgb(255, 255, 255);
        border-radius: 0 0 4px 4px;        
        border: solid 1px lightgray;
    }   
    .subtitle-header {
        clear: both;
        display: inline-block;
        background: #425b76;
        color: #fff;
        font-size: 16px;
        margin: 0;
        line-height: 1.25em;
        width: 100%;
        box-sizing: border-box;
        font-weight: 600;
        padding: 10px;
    }     

    .label_body label {
        font-size: 15px;
        cursor: pointer;
    }    

    .p-l-r-25{
        padding-left: 25px;
        padding-right: 25px;
    }

    .bg-white{
        background: white;
    }
    .select-content-type{
        width: 50%;
        margin: 15px;
    }
    .m-t-15{
        margin-top: 15px;
    }
    .field-boxes-container{
        padding: 0px 0px 15px 0px;
        margin-left: 0px !important; 
        margin-right: 0px !important;       
    }    
</style>
<div class="row" id ="compare_tool_plugin_settings">
	<div class="col-md-12">
        <div class="row">
            <div class="col-md-8">
                <p class="alert alert_info" style="margin:0;">
                <i class="fa fa-info-circle"></i><b>Compare tool settings</b>
                – Manage the filds to compare between the content type
                </p>
            </div>
            <div class="col-md-4">
                <button class="btn btn-success pull-right btn_save_settings">Save Changes</button>
            </div>
        </div>
	</div>
    <div class="col-md-12 m-t-15">
        <div class="bg-white">
            <h2 class="subtitle-header">Content Type</h2>          
            <select class="select-content-type">
				<option value="">Select an option</option>
                <option value="members">Members</option>
                <option value="posts">Posts - Coming Soon!</option>
            </select>
        </div>
    </div>     
    <div class="col-md-12 compare-tool-settings-fields  m-t-15">
        <h2 class="subtitle-header" >Establish Member Listing Comparison variables</h2>
        <div class="row bg-white field-boxes-container">            
                                                        
        </div>                                            
    </div>    
</div>
<script>   
    $(".select-content-type").on("change", function(){
        let content_type = $(this).val();
        if(content_type !== ""){
            
            $.ajax({
            url: '<? echo brilliantDirectories::getWebsiteUrl(); ?>/api/data/html/get/data_widgets/widget_name?name=compare-tool-plugin-engine-thd',
            type: 'post',
            dataType: 'html',
            data: {
                content_type: content_type,
                action: 'get_fields'
            },
            success: function(data) {
			        $(".field-boxes-container").html(data);
                }
            }).done();
        }
        
    });
    $(".btn_save_settings").on("click", function(){
        let params = [];
        $(".field-box").each(function(){
            let field_name = $(this).data('field');
            let isEnable = $(this).find('.field_enabled').prop("checked");
            let field_title = $(this).find('.field_title').val();
            let field_description = $(this).find('.field_description').val();

            params.push({
                "field_name": field_name, 
                "field_title" : field_title,
                "field_description" : field_description,
                "enable" : isEnable ? "1" : "0"
            });
            

        });


        $.ajax({
            url: '<? echo brilliantDirectories::getWebsiteUrl(); ?>/api/data/html/get/data_widgets/widget_name?name=compare-tool-plugin-engine-thd',
            type: 'post',
            dataType: 'html',
            data: {
                settings: params,
                action: 'save_settings',
                content_type: $(".select-content-type").val()
            },
            success: function(data) {
                    swal("Success", "Changes Saved", "success");
                    $(".select-content-type").trigger("change");
                }
            }).done();
    });

</script>