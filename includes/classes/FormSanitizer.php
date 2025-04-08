<?php
class FormSanitizer {

    public static function sanitizeFormString($inputText) {
        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ", "", $inputText);
        //$inputText = trim($inputText);
        $inputText = strtolower($inputText);
        $inputText = ucfirst($inputText);
        return $inputText;
    }

    public static function sanitizeFormUsername($inputText) {
        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ", "", $inputText);
        return $inputText;
    }

    public static function sanitizeFormPassword($inputText) {
        $inputText = strip_tags($inputText);
        return $inputText;
    }

    public static function sanitizeFormEmail($inputText) {
        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ", "", $inputText);
        return $inputText;
    }



    public static function containsSqlInjection($inputs) {

        $sqlInjectionKeywords = array("SELECT", "UPDATE", "DELETE", "DROP", "INSERT", "ALTER", "UNION", "FROM", "WHERE", "AND", "OR", "JOIN", "HAVING", "INTO", "VALUES", "UNION ALL","=",' ',"+","-","*");
    

        foreach ($inputs as $input) {
            foreach ($sqlInjectionKeywords as $keyword) {
                if (stripos($input, $keyword) !== false) {
                    return true;
                }
            }
        }
    
        return false; 
    }
    

    public static function containsNonEnglishCharacters($input) {
        return preg_match('/[^A-Za-z0-9@$!%*?&]/', $input);
    }

}
?>