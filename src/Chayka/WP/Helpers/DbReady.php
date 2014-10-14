<?php

namespace Chayka\WP\Helpers {

    interface DbReady{
        public static function unpackDbRecord($dbRecord);
        public function packDbRecord($forUpdate = false);
        public function insert();
        public function update();
        public function delete();
        public static function selectById($id, $useCache = true);
        public static function getDbTable();
        public static function getDbIdColumn();
        public function getId();
    }
}
