<?php defined('BLUDIT') or die('Bludit CMS.');

class VanillaCommentsPlugin extends Plugin {
  public function adminHead(){
    global $page, $url;
    global $L;

    $slug = explode("/", str_replace(HTML_PATH_ADMIN_ROOT, "", $url->uri()));

    ob_start();
    if ($slug[0] === "new-content" || $slug[0] === "edit-content") {
      ?>
      <script type="text/javascript">
        (function(){
          "use strict";
          var w = window, d = window.document;
          var HANDLE_COMMENTS_FIELD = '<?= Bootstrap::formSelectBlock(array(
            'name'      => 'allowComments',
            'label'     => $L->g('Page Comments'),
            'selected'  => (!$page)? '1': ($page->allowComments()? '1': '0'),
            'class'     => '',
            'options'   => array(
              '1' => $L->g('Allow Comments'),
              '0' => $L->g('Disallow Comments')
            )
          )); ?>';
          d.addEventListener("DOMContentLoaded", function(){
            if(d.querySelector("#jscategory")){
              var form = d.querySelector("#jscategory").parentElement;
              form.insertAdjacentHTML("afterend", HANDLE_COMMENTS_FIELD);
            }
          });
        }());
      </script>
      <?php
    }
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
  }
}