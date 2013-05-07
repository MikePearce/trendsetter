<?php

namespace Application\DefaultBundle\Lib;

/**
 * Tired of creating the Google Visualisation json by hand, this helper
 * can do it for you.
 **/
class Googlevis {

    /**
     * Create the column JSON
     * @param $columns array - Contains the column name and type
     * @return array
     **/
    public function createColumns($columns) {
        $vis_columns = array();
        foreach($columns AS $field => $type) {
            $vis_columns[] = array('id' => '', 'label' => $field, 'pattern' => '', 'type' => $type);
        }
        return $vis_columns;
    }

    /**
     * Create the data ROW
     * @param $label string - Label Name
     * @param mixed string/array - the data
     * @return array
     **/
    public function createDataRow($label, $data) {

        $row_label = array(array('v' => $label, 'f' => null));
        if (is_array($data)) {
            $row_data = array();
            foreach($data AS $value) {
                $row_data[] = array('v' => $value , 'f' => null);
            }
        }
        else {
            $row_data = array(array('v' => $data , 'f' => null));    
        }
        return array('c' => array_merge($row_label, $row_data));
    }
}