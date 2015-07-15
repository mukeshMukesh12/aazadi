<?php
define('DRUPAL_ROOT', getcwd());
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
/////////////////////////////////////////////////
// The imagefield field that you have already created and configured as per Prerequisites.
$field_name = ' field_image';

// The content type that you have already created as per Prerequisites.
$type_name = 'image_field';

// ***** END CONFIGURATION *******
// Populate the imagefield table for every image node.
$table = 'content_'. $field_name;
$fid = $field_name. '_fid';
$title = $field_name. '_title';
$alt = $field_name. '_alt';

$sql = "INSERT INTO $table (vid,delta,nid,$fid, $title, $alt) SELECT n.vid, 0 as delta, n.nid,f.fid,n.title,n.title FROM node n, files f WHERE n.nid = f.nid AND n.type = 'image' AND f.filename = '_original'";
if (db_query($sql)) {
  echo "- $table populated.<br />\n";
}

// Set the needed filename in the files table.
$image_path = file_create_path(variable_get('image_default_path', 'images'));
$length = strlen($image_path)+2;
$sql = "UPDATE files SET filename = SUBSTRING(filepath, $length) WHERE filename = '_original'";
if (db_query($sql)) {
  echo "- files table updated<br />\n";
}

// Change the content type from 'image' to the configured type.
$sql = "UPDATE node SET type = '%s' WHERE type = 'image'";
db_query($sql, $type_name);

// Loop over the image_attach records
if (module_exists('image_attach')) {
  $sql = "SELECT n.nid, n.vid, ia.iid FROM {image_attach} ia INNER JOIN {node} n ON ia.nid=n.nid";
  $result = db_query($sql);
  if ($num = db_num_rows($result)) {
    while ($row = db_fetch_object($result)) {
      // UPDATE the imagefield to point to the attached node, not the standalone node
      $sql = "UPDATE $table SET nid = $row->nid, vid = $row->vid WHERE nid = $row->iid";
      if (db_query($sql)) {
        // Successful update. Now unpublish the standalone node that we just made.
        $sql = "UPDATE {node} SET status = 0 WHERE nid = $row->iid";
        db_query($sql);
      }
      else {
        echo "update $table failed for $row->iid<br />\n";
      }
    }
    echo "- $num image_attach relationships were migrated.<br />\n";
  }
}

if (module_exists('video_image')) {
  // Loop over the video.module nodes. Migrate video_image thumbnails to imagefield.
  $sql = "SELECT nid, vid FROM {node} WHERE type = 'video'";
  $result = db_query($sql);
  if ($num = db_num_rows($result)) {
    while ($row = db_fetch_object($result)) {
      $node = node_load($row->nid);
      if ($iid = $node->iid) {
        $sql = "UPDATE $table SET nid = $row->nid, vid = $row->vid WHERE nid = $iid";
        if (db_query($sql)) {
          // Successful update. Now unpublish the standalone node that we just made.
          $sql = "UPDATE {node} SET status = 0 WHERE nid = $iid";
          db_query($sql);
        }
        else {
          echo "update $table failed for $iid<br />\n";
        }
      };
    }
    echo "- $num video.module thumbnails were migrated.<br />\n";
  }
}

// Clear CCK cache.
$sql = "DELETE FROM cache_content";
db_query($sql);
?>