<?php

namespace Chayka\WP\Helpers {

    interface DbReady{

        /**
         * Get db table name for the instance storage.
         *
         * @return string
         */
        public static function getDbTable();

        /**
         * Get id column name in db table
         *
         * @return mixed
         */
        public static function getDbIdColumn();

        /**
         * Unpacks db result object into this instance
         *
         * @param array|object $dbRecord
         * @return self
         */
        public static function unpackDbRecord($dbRecord);

        /**
         * Packs this instance for db insert/update
         *
         * @param bool $forUpdate
         * @return array
         */
        public function packDbRecord($forUpdate = false);

        /**
         * Insert current instance to db and return object id
         *
         * @return integer
         */
        public function insert();

        /**
         * Update corresponding db row in db and return object id.
         *
         * @return integer
         */
        public function update();

        /**
         * Delete corresponding db row from db.
         *
         * @return boolean
         */
        public function delete();

        /**
         * Select instance from db by id.
         *
         * @param $id
         * @param bool $useCache
         * @return mixed
         */
        public static function selectById($id, $useCache = true);

        /**
         * Get instance id
         * @return mixed
         */
        public function getId();

        /**
         * Set instance id
         * @param $id
         * @return $this
         */
        public function setId($id);
    }
}
