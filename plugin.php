<?php defined('BLUDIT') or die('Bludit CMS.');

class VanillaCommentsPlugin extends Plugin {

  /** Plugin init hook. */
  public function init(){
    $this->dbFields = array(
      "position" => "pageEnd",
      "forum_url" => "",
      "category_id" => ""
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

  private function categoryID() {
    $result = $this->getValue('category_id');
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
    $html .= '<label>'.$L->get('Category ID').'</label>';
    $html .= '<input name="category_id" value="'.$this->getValue('category_id').'">';
    $html .= '<span class="tip">'.$L->get('Optional. Specify the forum category ID where new discussions should be posted to. You can create a "Blog" category in your forum and have all comments grouped there, for example. Copy the ID from your broeser\'s address bar when you edit a category in the Vanilla Forums dashboard, e.g. example-forum.com/vanilla/settings/editcategory?categoryid=6 yields the ID "6". Leave empty to use the forum default.').'</span>';
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

  public function siteBodyBegin() {
    if ($this->commentPosition() !== "siteBodyBegin") {
      return false;
    }
    $result = $this->renderComments();
    if ($result === false) {
      return false;
    }
    print($result);
  }

  public function pageBegin() {
    if ($this->commentPosition() !== "pageBegin") {
      return false;
    }
    $result = $this->renderComments();
    if ($result === false) {
      return false;
    }
    print($result);
  }

  public function pageEnd() {
    if ($this->commentPosition() !== "pageEnd") {
      return false;
    }
    $result = $this->renderComments();
    if ($result === false) {
      return false;
    }
    print($result);
  }

  public function siteBodyEnd() {
    if ($this->commentPosition() !== "siteBodyEnd") {
      return false;
    }
    $result = $this->renderComments();
    if ($result === false) {
      return false;
    }
    print($result);
  }

  private function renderComments() {
    global $page;
    global $url;

    if ($url->whereAmI() !== 'page') {
      return false;
    }

    if (!$page->allowComments() || $this->forumURL() === false) {
      return false;
    }

    ob_start();
    ?>
      <div id="vanilla-comments"></div>
      <script type="text/javascript">
          /*** Required Settings: Edit BEFORE pasting into your web page ***/
          var vanilla_forum_url = '<?= $this->forumURL();?>'; // The full http url & path to your vanilla forum
          var vanilla_identifier = '<?= $page->permalink() ?>'; // Your unique identifier for the content being commented on

          /*** Optional Settings: Ignore if you like ***/
          // var vanilla_discussion_id = ''; // Attach this page of comments to a specific Vanilla DiscussionID.
          <?php if ($this->categoryID() !== false): ?>
          // Create this discussion in a specific Vanilla CategoryID.
          var vanilla_category_id = '<?= $this->categoryID(); ?>';
          <?php endif; ?>

          /*** DON'T EDIT BELOW THIS LINE ***/
          (function() {
              var vanilla = document.createElement('script');
              vanilla.type = 'text/javascript';
              var timestamp = new Date().getTime();
              vanilla.src = vanilla_forum_url + '/js/embed.js';
              (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(vanilla);
          })();
      </script>
      <noscript>
          Please enable JavaScript to view the <a href="http://vanillaforums.com/?ref_noscript">comments powered by Vanilla.</a>
      </noscript>
    <?php
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
  }
}
