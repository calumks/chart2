<?php

function getFooter(){

$form = "<p>Any bugs, please let us know at a rehearsal, or create an issue at  <a href='https://github.com/owen-kellie-smith/chart2'>Github</a>.</p>";
$form .= "<p><a href='.?action=logout'>Logout</a></p>";
$form .= "<div>
            <strong>Other links</strong>
          <p><a href='http://thornburyswingband.weebly.com/'>Thornbury Swing Band website</a></p>
            <p><a href='https://padlet.com/andyh/TSB_Tunes'>TSB_Tunes (Andy's padlet)</a></p>
          <p><a href='https://www.youtube.com/channel/UCX2K1BCZ6PR3AsjbFsi88og'>You tube (Thornbury Swing Band)</a></p>
          </div>";
return $form;
}
