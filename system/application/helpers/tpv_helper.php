<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

function form_creator($type, $data)
{
	switch($type) {
		case 'sermepa':
			return form_creator_sermepa($data);
		break;
		
		case 'ceca':
			return form_creator_ceca($data);
		break;
		
		
		case 'redsys':
			return form_creator_redsys($data);
		break;
		
		default:
			return NULL;
		break;
	}
}


function form_creator_sermepa($data)
{

	$output = '';
	
	$attributes = array('class' => 'frmPay', 'id' => 'frmPay', 'method' => 'post', 'target' => 'tpv');			
	echo form_open($data['tpv_payment_url'], $attributes);
	
	/*
	$amount='1235';
	$order='29292929';
	$terminal='1';
	$code='201920191';
	$currency=$this->config->item('tpv_moneda');
	$transactionType=$this->config->item('tpv_transaction_type');
	$urlMerchant='';
	$clave='h2u282kMks01923kmqpo';
	*/
	
	echo '<input type="hidden" name="Ds_Merchant_version" value="1">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_Amount" value="'.$data['Ds_Merchant_Amount'].'">'."\r\n";
	//echo '<input type="hidden" name="Ds_Merchant_SumTotal" value="'.$data['Ds_Merchant_Amount'].'">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_Currency" value="'.$data['Ds_Merchant_Currency'].'">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_Order"  value="'.$data['Ds_Merchant_Order'].'">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_MerchantCode" value="'.$data['Ds_Merchant_MerchantCode'].'">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_Terminal" value="'.$data['Ds_Merchant_Terminal'].'">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_TransactionType" value="'.$data['Ds_Merchant_TransactionType'].'">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_MerchantURL" value="'.$data['Ds_Merchant_MerchantURL'].'">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_ProductDescription" value="'.$data['Ds_Merchant_ProductDescription'].'">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_Titular" value="'.$data['Ds_Merchant_Titular'].'">'."\r\n";
	//echo '<input type="hidden" name="Ds_Merchant_ConsumerLanguage" value="001">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_UrlOK" value="'.$data['Ds_Merchant_UrlOK'].'">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_UrlKO" value="'.$data['Ds_Merchant_UrlKO'].'">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_MerchantName" value="'.$data['Ds_Merchant_MerchantName'].'">'."\r\n";
	
	# Calculo de la firma
	$message = $data['Ds_Merchant_Amount'].$data['Ds_Merchant_Order'].$data['Ds_Merchant_MerchantCode'].$data['Ds_Merchant_Currency'].$data['Ds_Merchant_TransactionType'].$data['Ds_Merchant_MerchantURL'].$data['Ds_Merchant_SecretWord'];
	//$message = $amount.$order.$code.$currency.$clave;
	//$signature = strtolower(sha1($message));			
	$signature = sha1($message);			
	echo '<input type="hidden" name="Ds_Merchant_MerchantSignature" value="'.$signature.'">'."\r\n";
	
	echo form_close();
	
	echo '<script type="text/javascript">'."\r\n";
	echo 'function pagar_tpv() {'."\r\n";
	//echo 'alert("'.$message.'");'."\r\n";
	echo "vent=window.open('','tpv','width=700,height=650,scrollbars=no,resizable=yes,status=yes,menubar=no,location=no');"."\r\n";
	echo "document.getElementById('frmPay').submit();"."\r\n";
	echo '}'."\r\n";
	echo '</script>'."\r\n";	
	
	return;
}


function form_creator_redsys($data)
{

	$output = '';
	
	$attributes = array('class' => 'frmPay', 'id' => 'frmPay2', 'method' => 'post', 'target' => 'tpv');			
	echo form_open($data['tpv_payment_url'], $attributes);
	
	/*
	$amount='1235';
	$order='29292929';
	$terminal='1';
	$code='201920191';
	$currency=$this->config->item('tpv_moneda');
	$transactionType=$this->config->item('tpv_transaction_type');
	$urlMerchant='';
	$clave='h2u282kMks01923kmqpo';
	*/
	
	echo '<input type="hidden" name="Ds_Merchant_version" value="1">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_Amount" value="'.$data['Ds_Merchant_Amount'].'">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_Currency" value="'.$data['Ds_Merchant_Currency'].'">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_Order"  value="'.$data['Ds_Merchant_Order'].'">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_MerchantCode" value="'.$data['Ds_Merchant_MerchantCode'].'">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_Terminal" value="'.$data['Ds_Merchant_Terminal'].'">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_TransactionType" value="'.$data['Ds_Merchant_TransactionType'].'">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_MerchantURL" value="'.$data['Ds_Merchant_MerchantURL'].'">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_ProductDescription" value="'.$data['Ds_Merchant_ProductDescription'].'">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_Titular" value="'.$data['Ds_Merchant_Titular'].'">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_UrlOK" value="'.$data['Ds_Merchant_UrlOK'].'">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_UrlKO" value="'.$data['Ds_Merchant_UrlKO'].'">'."\r\n";
	echo '<input type="hidden" name="Ds_Merchant_MerchantName" value="'.$data['Ds_Merchant_MerchantName'].'">'."\r\n";
	
	# Calculo de la firma
	$message = $data['Ds_Merchant_Amount'].$data['Ds_Merchant_Order'].$data['Ds_Merchant_MerchantCode'].$data['Ds_Merchant_Currency'].$data['Ds_Merchant_TransactionType'].$data['Ds_Merchant_MerchantURL'].$data['Ds_Merchant_SecretWord'];
	//$message = $amount.$order.$code.$currency.$clave;
	//$signature = strtolower(sha1($message));			
	$signature = strtoupper(sha1($message));			
	echo '<input type="hidden" name="Ds_Merchant_MerchantSignature" value="'.$signature.'">'."\r\n";
	
	echo form_close();
	
	echo '<script type="text/javascript">'."\r\n";
	echo 'function pagar_tpv() {'."\r\n";
	//echo 'alert("'.$message.'");'."\r\n";
	echo "vent=window.open('','tpv','width=700,height=650,scrollbars=no,resizable=yes,status=yes,menubar=no,location=no');"."\r\n";
	echo "document.getElementById('frmPay2').submit();"."\r\n";
	echo '}'."\r\n";
	echo '</script>'."\r\n";	

}
?>