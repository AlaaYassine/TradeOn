<html>
<meta charset="utf-8" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
   <link rel="stylesheet" href="style.css">
<link rel="stylesheet" type="text/css" href="styles.css"/>

	</html>

<?php
class Validation {
    private $_passed = false,
            $_errors = array(),
            $_db     = null;
			
    public function __construct(){
        $this->_db = DB::getInstance();
    }
    public function check($source, $items = array()){
        foreach($items as $item => $rules){
            foreach($rules as $rule => $rule_value){
                $value = trim($source[$item]);
                $item = escape($item); //sanitizing
				?>
				
				<?php 
				
                if($rule === 'required' && empty($value)){
                    $this->addError("{$item} is required.");
                }else if(!empty($value)){
                    switch($rule){ //checking for rules with in each field
                        case 'min':
                            if(strlen($value) < $rule_value){
                                $this->addError("{$item} must be a minimum of {$rule_value} characters.");
                            }
                            break;
                        case 'max':
                            if(strlen($value) > $rule_value){
                                $this->addError("{$item} should not exceed {$rule_value} characters.");
                            }
                            break;
                        case 'matches':
                            if($value != $source[$rule_value]){
                                $this->addError("{$rule_value} must match {$item}");
                            }
                            break;
                        case 'unique':
                            $check = $this->_db->get($rule_value, array($item, '=', $value));
                            if($check->count()){
                                $this->addError("{$item} already exists.");
                            }
                            break;
						case 'num':
                             if(!is_numeric($value)){
                                $this->addError("{$item} must be numbers only");
                            }
                            break;
                    }
                }
            }
        }
        if(empty($this->_errors)){
            $this->_passed = true;
        }
        return $this;
    }
    private function addError($error){
        $this->_errors[] = $error;
    }
    public function errors(){
        return $this->_errors;
    }
    public function passed(){
        return $this->_passed;
    }
	
	public function fieldError($type)
    {   
        $fieldError = null;
        foreach ($this->_errors as $error)
        {
            $word = explode(' ', $error ,-2);            
            if($word[0] === $type)
            {  $fieldError = $error;   }            
        }        
    return $fieldError;
    }
    }
 

    