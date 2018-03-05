<?php
// 如果你要允许所有站外图库的域名，请设置为 true，否则设置为 false（默认）
if(!defined('ALLOW_ALL_EXTERNAL_SITES')) define ('ALLOW_ALL_EXTERNAL_SITES', false);
// 如果你只想允许某些指点的站外图库域名，请将上面的选项设置为 false，然后在下面添加图库域名
if(! isset($ALLOWED_SITES)){
	$ALLOWED_SITES = array (
		'wpdaxue.com',
		'cmhello.com',
        'wpdx.com',
        'wpdx2.com'
	);
}
?>