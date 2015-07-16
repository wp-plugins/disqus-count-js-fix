<?php

/*
Plugin Name: Disqus Count JS Fix
Plugin URI: http://www.geektime.co.il
Description: Fix for Disqus count.js 'split' error.
Author: Avishay Bassa, Geektime <avishay@geektime.co.il>
Version: 1.00
Author URI: http://www.geektime.co.il
*/


if (is_plugin_active('disqus-comment-system/disqus.php')) {
	add_action( 'wp_enqueue_scripts', 'disqus_count_js_fix' );
}

function disqus_count_js_fix()
{
    if ( get_option('dsq_external_js') == '1' ) {
        $count_vars = array(
            'disqusShortname' => strtolower( get_option( 'disqus_forum_url' ) ),
        );

        wp_register_script( 'dsq_count_script', plugins_url( '/count.js', __FILE__ ) );
        wp_localize_script( 'dsq_count_script', 'countVars', $count_vars );
        wp_enqueue_script( 'dsq_count_script', plugins_url( '/count.js', __FILE__ ) );
    }
    else {
        ?>
        <script type="text/javascript">
        // <![CDATA[
		var disqus_shortname = countVars.disqusShortname;
		(function () {
		    var nodes = document.getElementsByTagName('span');
		    for (var i = 0, url; i < nodes.length; i++) {
		        if (nodes[i].className.indexOf('dsq-postid') != -1) {
		            nodes[i].parentNode.setAttribute('data-disqus-identifier', nodes[i].getAttribute('data-dsqidentifier'));
					if (nodes[i].parentNode.href) {
						url = nodes[i].parentNode.href.split('#', 1);
						if (url.length == 1) { url = url[0]; }
						else { url = url[1]; }
						nodes[i].parentNode.href = url + '#disqus_thread';
					}
		        }
		    }
		    var s = document.createElement('script'); s.async = true;
		    s.type = 'text/javascript';
		    s.src = '//' + disqus_shortname + '.disqus.com/count.js';
		    (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
		}());
        // ]]>
        </script>
        <?php
    }
}

?>