<?php

class Model_Utils
{
    public function initSession()
    {
        $session = new Zend_Session_Namespace(Session_Namespace);
//        $session->unsetAll();
        return $session;
    }
    
    /**
     * returns the current mysql date
     */
    public function getDateFromMysql()
    {
        $db = Zend_Registry::get('db');
        $result = $db->query('SELECT NOW() AS date');
        $row = $result->fetchAll();
        return $row[0]['date'];
    }
    /**
     * returns microsecond part of current time; used to monitor application speed
     */
    public function myMicrotime()
    {
        $microtime = microtime(true);
        return $microtime;
    }

    public function checkLogin()
    {
        $this->session = $this->initSession();
        // check for logged in user and send user data to the view if so
        if (isset($this->session->user)) {
            return true;
        }
        return false;
    }
    
    public static function generateHash($password)
    {
        if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
            $salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
            return crypt($password, $salt);
        }
    }
    
    public static function verifyHash($password, $hashedPassword)
    {
        return crypt($password, $hashedPassword) == $hashedPassword;
    }
    /**
     * gets data for the logged in employee from session and prepares it to be sent to the view
     */
    public function getUser()
    {
        $this->session = $this->initSession();
        if (isset($this->session->user)) {
            $agent = array(
                'id' => $this->session->user['userID'],
                'name' => $this->session->user['userLogin'],
                'fullname' => $this->session->user['userFullname'],
                'email' => $this->session->user['userEmail'],
                'admin' => $this->session->user['userIsAdmin'],
                'canDelete' => $this->session->user['userCanDelete'],
            );
            return $agent;
        }
        return false;
    }

    public static function formatDate($mysqldate, $withtime = true)
    {
        if (empty($mysqldate) || strstr($mysqldate, '0000-00-00') !== false) {
            return 'N/A';
        }
        $aux = strtotime($mysqldate);
        if (false === $aux) {
            // The valid range of a timestamp is typically from Fri, 13 Dec 1901 20:45:54 UTC to Tue, 19 Jan 2038 03:14:07 UTC
            return 'N/A';
        }
        if ($withtime) {
            return date('d.m.Y H:i', $aux);
        } else {
            return date('d.m.Y', $aux);
        }
    }
}