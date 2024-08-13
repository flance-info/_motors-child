<?php
add_filter('tablepress_table_raw_render_data', function($table, $render_options){
    if(isset($table['data']) && count($table['data'])){

        foreach ($table['data'] as $t_key => $table_value) {

            if(!empty($table_value[0])){

                $id_vehicle = htmlentities($table_value[0]);

                $id_vehicle = str_replace('&nbsp;', '', $id_vehicle);

                $id_vehicle = trim($id_vehicle);

                $vehicle_ids = stm_get_product_by_ident($id_vehicle);

                if($vehicle_ids){

                    foreach ($vehicle_ids as $VID) {

                        if(get_post_meta($VID['ID'],'new_product', true)){

                            $table['data'][$t_key][0] = $table_value[0] . '<span class="product_new">new</span>';

                        }

                    }

                }







                if(isset($table_value[1]) && !empty($table_value[1]) && stripos($table_value[1], '<a') === false){

                    if($vehicle_ids){

                        foreach ($vehicle_ids as $VID) {

                            $link = get_permalink($VID['ID']);

                            $table_cell = '<a href="'.$link.'">'.$table_value[1].'</a>';

                            $table['data'][$t_key][1] = $table_cell;



                        }

                    }

                }

            }

        }

    }

    return $table;

},10, 2);

function stm_get_product_by_ident($ident = 0){

    if(!$ident) return;

    global $wpdb;



    $sql = $wpdb->prepare("SELECT ID FROM `Qta_posts` WHERE `post_title` LIKE %s && `post_type`='product'", '%'.$ident.' %');

    $res = $wpdb->get_results($sql, ARRAY_A);
    

    if(count($res)) return $res;

    else return false;

}