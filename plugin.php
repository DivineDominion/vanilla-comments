<?php defined('BLUDIT') or die('Bludit CMS.');

class VanillaCommentsPlugin extends Plugin {

  /** Plugin init hook. */
  public function init(){
    $this->dbFields = array(
      "position" => "pageEnd",
      "forum_url" => "",
    );
  }

  private function commentPosition() {
    return $this->getValue('position');
  }

  private function forumURL() {
    $result = $this->getValue('forum_url');
    if (trim($result) === '') {
      return false;
    }
    return $result;
  }

  /** Callback for the admin page's head, adding form elements. */
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

  /** Method called on the settings of the plugin on the admin area. */
  public function form()
  {
    global $L;

    $html  = '<div class="alert alert-primary" role="alert">';
    $html .= $this->description();
    $html .= '</div>';

    $html .= '<div>';
    $html .= '<label>'.$L->get('Forum URL').'</label>';
    $html .= '<input name="forum_url" value="'.$this->getValue('forum_url').'">';
    $html .= '<span class="tip">'.$L->get('The full URL to your Vanilla Forum.').'</span>';
    $html .= '</div>';

    $html .= '<div>';
    $html .= '<label>'.$L->get('Position').'</label>';
    $html .= '<select name="position">';
    $html .= '<option value="disabled" '.     ($this->getValue('position')==='disabled'?'selected':'').'>'.$L->get('Disabled').'</option>';
    $html .= '<option value="siteBodyBegin" '.($this->getValue('position')==='siteBodyBegin'?'selected':'').'>'.$L->get('siteBodyBegin').'</option>';
    $html .= '<option value="pageBegin" '.    ($this->getValue('position')==='pageBegin'?'selected':'').'>'.$L->get('pageBegin').'</option>';
    $html .= '<option value="pageEnd" '.      ($this->getValue('position')==='pageEnd'?'selected':'').'>'.$L->get('pageEnd').'</option>';
    $html .= '<option value="siteBodyEnd" '.  ($this->getValue('position')==='siteBodyEnd'?'selected':'').'>'.$L->get('siteBodyEnd').'</option>';
    $html .= '</select>';
    $html .= '<span class="tip">'.$L->get('Where to show the page comments, if enabled.').'</span>';
    $html .= '</div>';

    return $html;
  }

}