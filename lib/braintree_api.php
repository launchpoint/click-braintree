<?

function bt_post($vars)
{
  global $braintree_settings;
  
  $vars['username'] = $braintree_settings['username'];
  $vars['password'] = $braintree_settings['password'];

  $qs = http_build_query($vars);

  $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://secure.braintreepaymentgateway.com/api/transact.php");
//	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $qs);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}
	
	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	return $httpParsedResponseAr;
}

function bt_add_vault($vars)
{
  $vars['customer_vault'] = 'add_customer';
  $res = bt_post($vars);
  return $res;
}

function bt_update_vault($vid, $vars)
{
  $vars['customer_vault'] = 'update_customer';
  $vars['customer_vault_id'] = $vid;
  $res = bt_post($vars);
  return $res;
}

function bt_debit($vid, $amt, $unique_id)
{
  $vars = array();
  $vars['type'] = 'sale';
  $vars['customer_vault_id'] = $vid;
  $vars['amount'] = $amt;
  $vars['orderid'] = $unique_id;
  $res = bt_post($vars);
  return $res;
}

function bt_credit($vid, $amt)
{
  $vars = array();
  $vars['type'] = 'credit';
  $vars['customer_vault_id'] = $vid;
  $vars['amount'] = $amt;
  $vars['payment'] = 'check';

  $res = bt_post($vars);

  return $res;
}

