<?php
/*
 * THD code
 * Widget Name: landing-page-compare-tool-plugin-thd
 * Version: 1.0
 * Developed By: Ernesto Saborio Cordoba
 * Collaborators:
 * Date of Create: 05/04/23
 */ 

 if(isset($_GET['members'])){
    /* GET ALL THE FIELDS ENABLED FOR MEMBER CONTENT TYPE */
    $select = "SELECT * FROM `compare-tool-content-settings` WHERE database_table_key = 'users_data' AND enable = 1;" ;
    $sql = mysql(brilliantDirectories::getDatabaseConfiguration('database'), $select);
    $fields_enabled = [];
    $member_specialities = null;
    while ($data = mysql_fetch_assoc($sql)) {                
        $fields_enabled[] = $data;       
    }

    $members_ids = implode(",", $_GET['members']);
    $data_html = "";
    foreach($members_ids as $id){
        $member_info = [];
        foreach($fields_enabled as $field){
            $select = "SELECT ".$field["comparison_variable_name"]." FROM `users_data` WHERE user_id = " . $id . ";" ;
            $sql = mysql(brilliantDirectories::getDatabaseConfiguration('database'), $select);
            $data = mysql_fetch_assoc($sql);
            $member_info[] = ["field_info" => $data, "field_settings" => $field];
        }
        $data_html .= get_member_html($member_info, $id);
    }
 }

 if(isset($_GET['posts'])){
    
 }




?>
<style>
    .hero_section_container {
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        position: relative;
    }

    .hero_section_container .hero_h1 {
        color: rgb(0, 60, 91);
        font-size: 45px;
        font-weight: 600;
    }
    .hero_section_container .container {
        padding-top: 70px;
        padding-bottom: 10px;
    }  
    .hero_section_container > .container > div {
        float: none!important;
        text-align: center;
    }     
    .center-block {
        display: block;
        margin-right: auto;
        margin-left: auto;
    }
</style>

<div class="hero_section_container">
    <div class="container">
        <div class="col-md-12 center-block">
            <h1 class="hero_h1">Compare</h1>                
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<?php
echo $data_html;
?>



<?php
function get_member_html($data, $id){
    $html = "";


    return $html;

}
?>