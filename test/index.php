<?

require_once("../../../kernel/bootstrap.php");



$bv = array(
  'ccnumber'=>'4111111111111111',
  'ccexp'=>'102010',
  'payment'=>'creditcard',
  'firstname'=>'Bob',
  'lastname'=>'Jones',
  'address1'=>'123 Apple St.',
  'city'=>'Woodland',
  'state'=>'CA',
  'zip'=>'95695',
  'country'=>'US',
  'phone'=>'805-555-1212',
  'email'=>'bob@apple.com'
);
  
  
$res = bt_add_vault($bv);
dprint($res);

bt_update_vault(1, array(
  'ccnumber'=>'4111111111111111',
  'ccexp'=>'102010',
  'payment'=>'creditcard',
  'firstname'=>'Bob',
  'lastname'=>'Jones',
  'address1'=>'123 Apple St.',
  'city'=>'Woodland',
  'state'=>'CA',
  'zip'=>'95695',
  'country'=>'US',
  'phone'=>'805-555-1212',
  'email'=>'bob@apple.com'
));

$res = bt_debit(1, 5.00);
$res = bt_debit(1, 0.50);

bt_add_vault(2,array(
  'payment'=>'check',
  'checkname'=>'Bob Jones',
  'checkaba'=>'123123123',
  'checkaccount'=>'123123123',
  'account_type'=>'checking',
  'firstname'=>'Bob',
  'lastname'=>'Jones',
  'address1'=>'123 Apple St.',
  'city'=>'Woodland',
  'state'=>'CA',
  'zip'=>'95695',
  'country'=>'US',
  'phone'=>'805-555-1212',
  'email'=>'bob@apple.com'
));

bt_update_vault(2,array(
  'payment'=>'check',
  'checkname'=>'Bob Jones',
  'checkaba'=>'123123123',
  'checkaccount'=>'123123123',
  'account_type'=>'checking',
  'firstname'=>'Bob',
  'lastname'=>'Jones',
  'address1'=>'123 Apple St.',
  'city'=>'Woodland',
  'state'=>'CA',
  'zip'=>'95695',
  'country'=>'US',
  'phone'=>'805-555-1212',
  'email'=>'bob@apple.com'
));

bt_credit(1,20);
bt_credit(1,0.50);