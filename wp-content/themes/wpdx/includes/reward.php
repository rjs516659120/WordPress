<?php
$reward_tips = cmp_get_option('reward_tips') ? cmp_get_option('reward_tips') : __('If the article is helpful to you, please click on the button to reward the author','wpdx');
$alipay_img = cmp_get_option('alipay_img') ? cmp_get_option('alipay_img') : '';
$alipay_tips = cmp_get_option('alipay_tips') ? cmp_get_option('alipay_tips') : __('Alipay reward','wpdx');
$wechatpay_img = cmp_get_option('wechatpay_img') ? cmp_get_option('wechatpay_img') : '';
$wechatpay_tips = cmp_get_option('wechatpay_tips') ? cmp_get_option('wechatpay_tips') : __('Wechatpay reward','wpdx');
$reward_class = ($alipay_img =='' || $wechatpay_img =='') ? 'reward-code one' : 'reward-code';
?>
<div class="reward">
    <div class="reward-button"><?php _e('$','wpdx'); ?>
        <span class="<?php echo $reward_class; ?>">
        <?php if($alipay_img && $alipay_img !=''): ?>
            <span class="alipay-code">
                <img class="alipay-img" alt="<?php echo $alipay_tips; ?>" src="<?php echo $alipay_img; ?>"><b><?php echo $alipay_tips; ?></b>
            </span>
        <?php endif; ?>
        <?php if( $wechatpay_img && $wechatpay_img !=''): ?>
            <span class="wechat-code">
                <img class="wechat-img" alt="<?php echo $wechatpay_tips; ?>" src="<?php echo $wechatpay_img; ?>"><b><?php echo $wechatpay_tips; ?></b>
            </span>
            <?php endif; ?>
        </span>
    </div>
    <p class="reward-notice"><?php echo $reward_tips; ?></p>
</div>