<?php

namespace Overland\Tests\Traits;

trait DatabaseTransactions {
    /**
     * @before
     */
    public function setUpTransactionsBeforeTest() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->wpdb->query('SET autocommit=0');
        $this->wpdb->query('START TRANSACTION');
    }

    /**
     * @after
     */
    public function rollBackAfterTest() {
        $this->wpdb->query('ROLLBACK');
        $this->wpdb->flush();
    }
}
