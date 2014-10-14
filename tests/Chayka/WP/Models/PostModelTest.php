<?php

use Chayka\WP\Models\PostModel;

class PostModelTest extends PHPUnit_Framework_TestCase {

    public function testGmtTime(){
        $post = new PostModel();
        $dbRecord = $post->packDbRecord(false);
        $dtCreated = $post->getDtCreated();
        $this->assertEqual($dbRecord['post_date'], $dbRecord['post_date_gmt']);
    }
}
 