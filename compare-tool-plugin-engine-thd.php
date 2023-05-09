<?php
/*
 * THD code
 * Widget Name: compare-tool-plugin-engine-thd
 * Version: 1.0
 * Developed By: Ernesto Saborio Cordoba
 * Collaborators:
 * Date of Create: 05/04/23
 */ 


$action = $_POST['action'];

switch($action) {
    case 'get_fields':
        $ignore_fields = ["user_id", "subscription_id", "filename", "password", "active", "token", "ref_code", "signup_date", "cookie", "last_login", "gmap", "lat", "lon", "parent_id", "no_geo", "stripe_account_id", "first_name", "last_name", "email" ,"company" ,"state_ln", "country_ln", "modtime", "bitly", "profession_id", "facebook_id", "google_id", "user_consent"];
        $content_type = $_POST['content_type'];
        $html = "";
        $htmlDisabledFields = "";
        if($content_type === "members"){
            
            /* GET ALL THE FIELDS SAVED FOR MEMBER CONTENT TYPE */
            $select = "SELECT * FROM `compare-tool-content-settings` WHERE database_table_key='users_data';" ;
            $sql = mysql(brilliantDirectories::getDatabaseConfiguration('database'), $select);
            $fields_saved = [];
            $member_specialities = null;
            while ($data = mysql_fetch_assoc($sql)) {                
                if($data['comparison_variable_name'] == "Member Specialities"){
                    $member_specialities =  $data;
                }else{
                    $fields_saved[] = $data;
                }             
            }  
            
            /* CREATE THE HTML FOR THE SPECIALITIES FIELD */
            if($member_specialities){                
                if($member_specialities['enable']){
                    $html .= get_field_html($member_specialities['enable'], "Member Specialities", $member_specialities['comparison_variable_title'], $member_specialities['comparison_variable_description'] );
                }else{
                    $htmlDisabledFields .= get_field_html($member_specialities['enable'], "Member Specialities", $member_specialities['comparison_variable_title'], $member_specialities['comparison_variable_description'] );
                }
            }else{
                $htmlDisabledFields .= get_field_html(false, "Member Specialities", "", "" );
            }
            
            /* GET ALL THE FIELDS FROM THE USER_DATA TABLE */
            $select = "SELECT `column_name` 
            FROM `INFORMATION_SCHEMA`.`COLUMNS` 
            WHERE `TABLE_NAME`='users_data';";
            $sql = mysql(brilliantDirectories::getDatabaseConfiguration('database'), $select);
            $column_names  = [];

            /* CREATE THE HTML WITH THE FIELDS ENABLED AND DISABLED */
            while ($data = mysql_fetch_assoc($sql)) {
                if(!in_array($data['column_name'],$ignore_fields, true)){
                    $fieldSavedValues = isSavedValues($data['column_name'],$fields_saved);
                    if($fieldSavedValues[0]){
                        $html .= get_field_html($fieldSavedValues[0], $data['column_name'], $fieldSavedValues[1], $fieldSavedValues[2]);
                    }else{
                        $htmlDisabledFields .= get_field_html($fieldSavedValues[0], $data['column_name'], $fieldSavedValues[1], $fieldSavedValues[2]);
                    }
                    
                }                
            }

            $html = $html . $htmlDisabledFields;
          
        }


        if($content_type === "posts"){
            
        }        

        echo $html;

    break;

    case 'save_settings':
        $content_type = $_POST['content_type'];
        if($content_type === "members"){
            $db_table = "users_data";
        }
        
        $settings = $_POST['settings'];
        $db_values = [];
        /* CREATE THE SQL QUERY WITH THE POST PARAMETERS */
        foreach($settings as $setting){
            $enable = $setting["enable"] ? 1 : 0;
            $db_values[] = "('".$setting["field_name"]."','".$db_table."','".$setting["field_title"]."','".$setting["field_description"]."', '".$content_type."', ". $enable .")";
        }
        

        $update = "INSERT INTO `compare-tool-content-settings`(`comparison_variable_name`, `database_table_key`, `comparison_variable_title`, `comparison_variable_description`, `content_type`, `enable`) VALUES ";
        $update .= implode(",", $db_values);
        $update .= " ON DUPLICATE KEY UPDATE `comparison_variable_title` = VALUES(`comparison_variable_title`),";
        $update .= " `comparison_variable_description` = VALUES(`comparison_variable_description`),";
        $update .= " `content_type` = VALUES(`content_type`),";
        $update .= " `enable` = VALUES(`enable`);";

        
        mysql(brilliantDirectories::getDatabaseConfiguration('database'), $update);

        echo "ok";

    break;    

    default:
        echo json_encode("error");
    break;
}

function get_field_html($enabled, $name, $title, $description ){
    $checked = ($enabled) ? "checked" : "";
    return "<div class='col-xs-12 col-sm-3  m-t-15'>
                <div class='label_head'>
                    <span>" . $name . "</span>
                </div>
                <div class='label_body'>
                    <div class='row field-box' data-field='" . $name . "'>
                        <div class='col-md-12 p-l-r-25'>
							<label>Enable:</label>
                            <label class='switch pull-right'>
                                <input type='checkbox'  " . $checked . " class='field_enabled'>
                                <span class='slider'></span>
                            </label>                        
                            
                        </div>
                        <div class='col-md-12 p-l-r-25'>
                            <label>Comparison title:</label>
                            <textarea class='field_title' rows='2' style='width: 100%;'>".$title."</textarea>
                        </div>
                        <div class='col-md-12 p-l-r-25'>
                            <label>Tip description:</label>
                            <textarea class='field_description' rows='2' style='width: 100%;'>".$description."</textarea>
                        </div>                                                
                    </div>
                </div>            
            </div>";
}

function isSavedValues($value, $arr){
    foreach($arr as $data){
        if($data['comparison_variable_name'] === $value){
            return [$data['enable'], $data['comparison_variable_title'],$data['comparison_variable_description']];
        }
    }
    return [false, "",""];
}
?>
