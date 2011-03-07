<?

$braintree_settings = array(
  'username'=>'clientpoint',
  'password'=>'ops1224#',
  'enabled'=>true,
  'role_caps'=>array(
    'worker'=>array(
      'eft_enabled'=>true
    ),
    'client'=>array(
      'cc_enabeld'=>true
    ),
  ),
  'cc_fields'=>array(
    'cc_gateway_status'=>array('type'=>'status'),
    'cc_type'=>array('type'=>'select', 'item_array'=>array('Visa', 'MasterCard', 'Discover', 'AmEx')),
    'cc_number'=>array('type'=>'text', 'validators'=>array(array('type'=>'function', 'method'=>'is_cc_format', 'message'=>'does not appear to be a valid number.'))),
    'cc_expiration'=>array('type'=>'text', 'validators'=>array(array('type'=>'regex', 'method'=>'/^[01][0-9]20[01][0-9]$/', 'message'=>'must be in the format MMYYYY.'))),
    'cc_firstname',
    'cc_lastname',
    'cc_street',
    'cc_city',
    'cc_state_id',
    'cc_zip',
  ),
  'eft_fields'=>array(
    'eft_gateway_status'=>array('type'=>'status'),
    'use_eft',
    'eft_firstname',
    'eft_lastname',
    'eft_street',
    'eft_city',
    'eft_state_id',
    'eft_zip',
    'eft_account_type'=>array('type'=>'select', 'item_array'=>array('checking'=>'Checking', 'savings'=>'Savings')),
    'eft_account_number'=>array('type'=>'text',
      'help'=>array(
        'body'=>"You account number is located at the bottom of your check.",
        'img'=>BRAINTREE_VPATH.'/assets/images/check.gif'
      ),
    ),
    'eft_routing_number'=>array('type'=>'text',
      'help'=>array(
        'body'=>"You routing number is located at the bottom of your check.",
        'img'=>BRAINTREE_VPATH.'/assets/images/check.gif'
      ),
    ),
  ),
);