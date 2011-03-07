<?

function validate_eft($user)
{
  global $braintree_settings;
  
  $should_validate_eft = ($user->eft_required && !$user->eft_vault_id);
  $field_names = array(
    'eft_firstname',
    'eft_lastname',
    'eft_routing_number',
    'eft_account_number',
    'eft_account_type',
    'eft_street',
    'eft_city',
    'eft_state_id',
    'eft_zip'
  );
  foreach($field_names as $name)
  {
    if(trim($user->$name)!='')
    {
      $should_validate_eft = true;
      break;
    }
  }
  
  if ($should_validate_eft)
  {
    $fields = $braintree_settings['eft_fields'];array(
      'eft_firstname'=>array('required'=>true, 'type'=>'text'),
      'eft_lastname'=>array('required'=>true, 'type'=>'text'),
      'eft_street'=>array('required'=>true, 'type'=>'text'),
      'eft_city'=>array('required'=>true, 'type'=>'text'),
      'eft_state_id'=>array('required'=>true, 'type'=>'select', 'item_array'=>'states'),
      'eft_zip'=>array('required'=>true, 'type'=>'text', 'validators'=>array(array('type'=>'regex', 'method'=>"/^([0-9]{5})(-[0-9]{4})?$/i", 'message'=>'is not a valid zip code.'))),
      'eft_account_type'=>array('required'=>true, 'type'=>'select', 'item_array'=>array('checking'=>'Checking', 'savings'=>'Savings')),
      'eft_account_number'=>array('required'=>true, 'type'=>'text', 'validators'=>array(array('type'=>'regex', 'method'=>'/^\d+$/', 'message'=>'must be numeric.'))),
      'eft_routing_number'=>array('required'=>true, 'type'=>'text', 'validators'=>array(array('type'=>'regex', 'method'=>'/^\d{9}$/', 'message'=>'must be 9 digits.'))),
    );
    superform_validate_fields($user, $fields);
    
    if(count($user->errors)==0)
    {
      $should_store = true;
      foreach($field_names as $name)
      {
        $should_store &= !array_key_exists($name, $user->errors);
      }
      
      if($should_store)
      {
        $state = State::find_by_id($user->eft_state_id);
        $params = array(
          'payment'=>'check',
          'checkname'=>$user->eft_firstname . ' ' . $user->eft_lastname,
          'checkaba'=>$user->eft_routing_number,
          'checkaccount'=>$user->eft_account_number,
          'account_type'=>$user->eft_account_type,
          'firstname'=>$user->eft_firstname,
          'lastname'=>$user->eft_lastname,
          'address1'=>$user->eft_street,
          'city'=>$user->eft_city,
          'state'=>$state->abbreviation,
          'zip'=>$user->eft_zip,
          'country'=>'US',
          'phone'=>$user->phone_number,
          'email'=>$user->email
        );
        if($user->eft_vault_id)
        {
          $res = bt_update_vault($user->eft_vault_id, $params);
        } else {
          $res = bt_add_vault($params);
        }
        if($res['response_code']!=100)
        {
          $user->errors['eft_gateway_status'] = "Gateway responded: " . $res['responsetext'];
        } else {
          $user->eft_vault_id = $res['customer_vault_id'];
          $user->eft_account_last4 = substr($user->eft_account_number, -4);
        }
      }
    }
  }
  
  if(count($user->errors)==0 && $user->eft_vault_id)
  {
    $user->eft_gateway_status = "Securely stored account x".$user->eft_account_last4;
  } else {
    $user->eft_gateway_status = "Unknown";
  }

}

function validate_cc($user)
{
  
  $should_validate_cc = ($user->cc_required && !$user->cc_vault_id);
  $field_names = array(
    'cc_number',
    'cc_expiration',
    'cc_firstname',
    'cc_lastname',
    'cc_street',
    'cc_city',
    'cc_state_id',
    'cc_zip'
  );
  foreach($field_names as $name)
  {
    if(trim($user->$name)!='')
    {
      $should_validate_cc = true;
      break;
    }
  }
  
  if ($should_validate_cc)
  {
    $fields = array(
      'cc_number'=>array('required'=>true, 'type'=>'text', 'validators'=>array(array('type'=>'function', 'method'=>'is_cc_format', 'message'=>'does not appear to be a valid number.'))),
      'cc_expiration'=>array('required'=>true, 'type'=>'text', 'validators'=>array(array('type'=>'regex', 'method'=>'/^[01][0-9]20[01][0-9]$/', 'message'=>'must be in the format MMYYYY.'))),
      'cc_type'=>array('required'=>true, 'type'=>'select', 'item_array'=>array('Visa', 'MasterCard', 'Discover')),
      'cc_firstname'=>array('required'=>true, 'type'=>'text'),
      'cc_lastname'=>array('required'=>true, 'type'=>'text'),
      'cc_street'=>array('required'=>true, 'type'=>'text'),
      'cc_city'=>array('required'=>true, 'type'=>'text'),
      'cc_state_id'=>array('required'=>true, 'type'=>'select'),
      'cc_zip'=>array('required'=>true, 'type'=>'text', 'validators'=>array(array('type'=>'regex', 'method'=>"/^([0-9]{5})(-[0-9]{4})?$/i", 'message'=>'is not a valid zip code.'))),
    );
    superform_validate_fields($user, $fields);
    
  
    if(count($user->errors)==0)
    {
      $should_store = true;
      foreach($field_names as $name)
      {
        $should_store &= !array_key_exists($name, $user->errors);
      }
      
      if($should_store)
      {
        $state = State::find_by_id($user->cc_state_id);
        $params = array(
          'payment'=>'creditcard',
          'ccnumber'=>$user->cc_number,
          'ccexp'=>$user->cc_expiration,
          'firstname'=>$user->cc_firstname,
          'lastname'=>$user->cc_lastname,
          'address1'=>$user->cc_street,
          'city'=>$user->cc_city,
          'state'=>$state->abbreviation,
          'zip'=>$user->cc_zip,
          'country'=>'US',
          'phone'=>$user->phone_number,
          'email'=>$user->email
        );
        if($user->cc_vault_id)
        {
          $res = bt_update_vault($user->cc_vault_id, $params);
        } else {
          $res = bt_add_vault($params);
        }
        if($res['response_code']!=100)
        {
          $user->errors['cc_gateway_status'] = "Gateway responded: " . $res['responsetext'];
        } else {
          $user->cc_vault_id = $res['customer_vault_id'];
          $user->cc_account_last4 = substr($user->cc_number, -4);
        }
      }
    }
  }
  
  if(count($user->errors)==0 && $user->cc_vault_id)
  {
    $user->cc_gateway_status = "Securely stored account x".$user->cc_account_last4;
  } else {
    $user->cc_gateway_status = "Unknown";
  }
}