<?php // $Id: block.tpl.php,v 1.1 2009/05/15 07:28:05 agileware Exp $ ?>
<div class="Block">
  <div class="Block-tl"></div>
  <div class="Block-tr"></div>
  <div class="Block-bl"></div>
  <div class="Block-br"></div>
  <div class="Block-tc"></div>
  <div class="Block-bc"></div>
  <div class="Block-cl"></div>
  <div class="Block-cr"></div>
  <div class="Block-cc"></div>
  <div class="Block-body">
    <?php if ($block->subject): ?>
      <div class="BlockHeader">
        <div class="l"></div>
        <div class="r"></div>
        <div class="header-tag-icon">
          <div class="t">
            <?php echo $block->subject; ?>
          </div>
        </div>
      </div>
    <?php endif; ?>
    <div class="BlockContent">
      <div class="BlockContent-body">
        <?php echo $block->content; ?>
      </div>
     </div>
  </div>
</div>
