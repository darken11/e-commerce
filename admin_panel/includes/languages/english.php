<?php 
function lang($phrase){
	static $lang=array(
		//Nav bars
		'HOME'				=>'Admin Area',
		'CAT'				=>'Categories',
		'ITEMS'				=>'Products',
		'MEMBERS'			=>'Members',
		'STAT'				=>'Statistics',
		'LOGS'				=>'Logs',
		'UPDAT_USER_PROFILE'=>'Edit Profile',
		'SETING'			=>'Setting',
		'COMMENT'           =>'Comments',
		'VIEW-SHOP'         => 'Shop',
	);
	return $lang[$phrase];
}