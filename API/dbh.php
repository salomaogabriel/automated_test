<?php 
    class Dbh {

        private $host = "localhost";
        private $user ="root";
        private $pwd = "";
        private $dbName ="automatizacaoteste";
        private $pdo;

        function createPDO() {
            $dsn  = 'mysql:host=' . $this->host .';dbname=' .$this->dbName;
            $this->pdo = new PDO($dsn,$this->user,$this->pwd);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        }
        public function connect() {
            return $this->pdo;     
        }
        public function closeConnectionPDO(){
            $this->pdo = null;
            return;
        }
    }

?>
