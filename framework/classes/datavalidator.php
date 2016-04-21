<?php
/**
 * Helper functions for data validation in models
 *
 */

class DataValidator {

    public function validateData($data, $rule_list) {
        // default values
        $error_bag = array();

        // loop through the rule list
        foreach($rule_list as $key => $line) {
            $rules = explode('|', $line);
            foreach($rules as $rule) {
                // explode for rule:value
                $arr = explode(':', $rule);

                // required validate, go to next field iteration incase empty equals true
                if($arr[0] == 'required') {
                    if(empty($data[$key])) {
                        $error_bag[$key]['msg'] = $key . ' is required.';
                        continue 2;
                    }
                }

                // email validate
                if($arr[0] == 'email')
                    if(isset($data[$key]) && !DataValidator::isEmail($data[$key]))
                        $error_bag[$key]['msg'] = $key . ' is not valid.';

                // min length validate
                if($arr[0] == 'min')
                    if(isset($data[$key]) && strlen($data[$key]) < $arr[1])
                        $error_bag[$key]['msg'] = $key . ' requires atleast ' . $arr[1] . ' characters.';

                // max length validate
                if($arr[0] == 'max')
                    if(isset($data[$key]) && strlen($data[$key]) > $arr[1])
                        $error_bag[$key]['msg'] = $key . ' only allows up to ' . $arr[1] . ' characters.';
            }
        }

        // return if the data has validated succesfully, otherwise throw the error bag
        if(empty($error_bag))
            return false;
        return $error_bag;
    }

    /**
     * Check if the given value is a email address
     */
    private function isEmail($val) {
        return filter_var($val, FILTER_VALIDATE_EMAIL);
    }
}