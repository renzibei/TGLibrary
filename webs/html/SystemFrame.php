<?php


namespace tg;

use \Exception;
use \mysqli;
//use systemConfig\config

require_once 'errorTable.php';
require_once dirname(dirname(dirname(__FILE__))) . '/dbusers/dbadmin.php';
require_once 'DocData.php';


/**
 * Class SystemFrame
 */
class SystemFrame{

    private static $__instance;
    protected $__docData;
    protected static  $__userData;

	protected $rootDirPath;
	protected $configFilePath;
	protected static $logFilePath;
	protected $sqlConn;




	protected function __construct()
	{
        echo "construct <br />";
	    $this->rootDirPath = dirname(dirname(dirname(__FILE__)));
		$this->configFilePath = $this->rootDirPath . '/dbusers/dbadmin.php';
        $this->__docData = new DocData();
	}

    /**
     * @return mixed
     * @throws Exception
     */
	public static function getLogFilePath()
    {
        if(!isset(self::$logFilePath))
            self::initLogFile();
        return self::$logFilePath;
    }

    /**
     * @throws \Exception
     *
     */
    public static function instance()
    {
        if(empty(self::$__instance)) {
            self::$__instance = new SystemFrame();
            self::$__instance->setTime();
            //self::$__instance->initLogFile();
        }
        return self::$__instance;
    }

    public function _docData()
    {
        return $this->__docData;
    }

    public static function userData()
    {
        if(empty(self::$__userData)) {
            self::$__userData = new UserData();
        }
        return self::$__userData;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public static function docData()
    {
        return self::instance()->_docData();
    }




    /**
     * @param string $info
     * @throws \Exception
     */
	public static function log_info($info)
	{
        //debug

	    error_log( $info . PHP_EOL,3, self::getLogFilePath());
	}

	protected function setTime()
	{
		date_default_timezone_set("Asia/Shanghai");
		
	}

    /**
     * @throws \Exception
     */
	protected static function initLogFile()
	{
		self::$logFilePath = dirname(dirname(__DIR__)) . '/log/info' . date("Y-m-d-H:i:s") . '.log';
		if(!file_exists(self::$logFilePath)) {
			$fileHandler = fopen(self::$logFilePath, "w");
			if($fileHandler === false)
				throw new Exception("CreateLogFileFailed", errorCode\CreateLogFileError);

			fclose($fileHandler);
		}
	}

    /**
     * @throws \Exception
     */
    protected function createDatabase()
	{
		//SystemFrame::log_info(__NAMESPACE__);
	    //// $config;

		if(file_exists($this->configFilePath)) {
			
			$conn = new \mysqli(systemConfig\config['db_serverhost'], systemConfig\config['db_username'], systemConfig\config['db_password']);
			if( $conn->connect_error) {
				throw new \Exception($conn->connect_error, errorCode\ConnectDBError);
			}
			if(!$conn->set_charset("utf8")) {
			    throw new \Exception("Fail to set mysqli charset to utf8" . $conn->error, errorCode\SetMysqlCharSetError);
            }
			$createDBSql = "CREATE DATABASE IF NOT EXISTS " . systemConfig\config['tg_database'] . " default character set utf8 COLLATE utf8_general_ci";
			if($conn->query($createDBSql) === false)
				throw new \Exception("Fail to create Database " . $conn->error, errorCode\CreateDBError);
			if($conn->select_db(systemConfig\config['tg_database']) === false)
					throw new \Exception("Fail to Choose Database " . $conn->error, errorCode\ChooseDBError);
					

			return $conn;
		}
		else throw new Exception("Config file does not exists", errorCode\FileNotExist);
		
	}


    /**
     * @throws Exception
     * @param string $tableName
     * @param mysqli $conn
     * @return BOOL
     */

	protected function tableExists(string $tableName, mysqli $conn)
	{
		$queryTableSql = 'SHOW TABLES LIKE \'' . $tableName . '\'';
		$result = $conn->query($queryTableSql);
		if($result === false)
			throw new Exception("Query for Table Error" . $conn->error, errorCode\QueryTableError);
		else {
			$rows = $result->fetch_all();
			if(count($rows) > 0)
				return true;
			else 
				return false;
		}
			
	}



    /**
     *
     * Tell whether the dataBases if prepared.
     * If prepared, return true, else false.
     * @throws Exception
     * @param mysqli $conn
     * @return BOOL
     */
    protected function isTablePrepared(mysqli $conn)
    {
        // $config;
        if($this->tableExists(systemConfig\config['lastTable'], $conn) === false)
            return false;
        $querySql = "SELECT * FROM " . systemConfig\config['lastTable'];
        $result = $conn->query($querySql);
        if($result->num_rows > 0)
            return 1;
        else return 0;
    }

    /**
     * @param mysqli $conn
     * @throws Exception
     */
    protected function initTables(mysqli $conn)
    {
        global $docTypeList;
        // $config;
        global $identifierTypeList;
        require_once dirname(dirname(dirname(__FILE__))) . '/dbusers/docInfo.php';

        $sqlQuery = "DELETE FROM " . systemConfig\config['docType'];
        if($conn->query($sqlQuery) === false )
            throw new Exception("Fail to delete data in" . systemConfig\config['docType'] . $conn->error, errorCode\DeleteFromTableError);


        $sqlQuery = "DELETE FROM " . systemConfig\config['identifierType'];
        if($conn->query($sqlQuery) === false )
            throw new Exception("Fail to delete data in" . systemConfig\config['identifierType'] . $conn->error, errorCode\DeleteFromTableError);

        for($i = 1; $i <= count($docTypeList); $i++ ) {
            $sqlQuery = "INSERT INTO " . systemConfig\config['docType'] . " ( " . 'typeId' . "," . " typeName " . ")  " . "VALUES " . "($i , '$docTypeList[$i]' )";
            if($conn->query($sqlQuery) === false)
                throw new Exception("Fail to insert Initial Data into Table" . $conn->error, errorCode\InsertIntoTableError);
        }
        for($i = 1; $i <= count($identifierTypeList); $i++) {
            $sqlQuery = "INSERT INTO " . systemConfig\config['identifierType'] . " ( typeId, typeName ) VALUES " . "($i, '$identifierTypeList[$i]' )";
            if($conn->query($sqlQuery) === false)
                throw new Exception("fail to insert Inital Data into Table" . $conn->error, errorCode\InsertIntoTableError);
        }

        $sqlQuery = "INSERT INTO " . systemConfig\config['lastTable'] . ' VALUES ()';

        if($conn->query($sqlQuery) === false)
            throw new Exception("Fail to insert initial Data into Table" . $conn->error, errorCode\InsertIntoTableError);

    }


    /**
     * @throws Exception
     * @param mysqli $conn
     */
    protected function createTables(mysqli $conn)
    {
            // $config;
            $createDocTableSql = 'CREATE TABLE IF NOT EXISTS ' . systemConfig\config['docTable'] ." (
                                    docId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                    PublicYear YEAR,
                                    title VARCHAR(1024) NOT NULL ,
                                    publisher VARCHAR(1024),
                                    volume INT,
                                    version INT,
                                    format VARCHAR(4096),
                                    source VARCHAR(4096),
                                    description TEXT,
                                    bookId INT,
                                    bookNum INT,
                                    typeId INT,
                                    onlineAccess BOOL 
                                     )";
            if($conn->query($createDocTableSql) === false)
                throw new Exception("Fail to create Table " . systemConfig\config['docTable'], errorCode\CreateDBTableError);

            $createBookTableSql = 'CREATE TABLE IF NOT EXISTS ' . systemConfig\config['bookTable'] . "(
                                    bookId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                    callNumber VARCHAR(256),
                                    docId INT,
                                    version INT,
                                    publicYear YEAR,
                                    isOnShelf BOOL,
                                    placeId INT,
                                    languageId INT
                                    )";
            if($conn->query($createBookTableSql) === false)
                throw new Exception("Fail to create Table " . systemConfig\config['bookTable'], errorCode\CreateDBTableError);

            $createAuthorTableSql = 'CREATE TABLE IF NOT EXISTS ' . systemConfig\config['authorTable'] . "(
                                        authorId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        name VARCHAR(1024),
                                        description TEXT
                                        )";
            if($conn->query($createAuthorTableSql) === false)
                throw new Exception("Fail to create Table " . systemConfig\config['authorTable'], errorCode\CreateDBTableError);

            $createAdminTableSql = "CREATE TABLE IF NOT EXISTS " . systemConfig\config['adminTable'] . "(
                                        adminId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        username VARCHAR(256) UNIQUE,
                                        password VARCHAR(256),
                                        name VARCHAR(256)
                                    )";
            if($conn->query($createAdminTableSql) === false)
                throw new Exception("Fail to create Table " . systemConfig\config['adminTable'], errorCode\CreateDBTableError);

            $createUserTableSql = "CREATE TABLE IF NOT EXISTS " . systemConfig\config['userTable'] . "(
                                        userId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        username VARCHAR(256) UNIQUE,
                                        password VARCHAR(256),
                                        uid VARCHAR(20) UNIQUE, 
                                        name VARCHAR(256)
                                    )";
            if($conn->query($createUserTableSql) === false)
                throw new Exception("Fail to create Table " . systemConfig\config['userTable'], errorCode\CreateDBTableError);

            $createBorrowRecordSql = "CREATE TABLE IF NOT EXISTS " . systemConfig\config['borrowRecord'] . "(
                                        recordId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        userId INT NOT NULL,
                                        bookId INT NOT NULL,
                                        docId INT,
                                        beginDate TIMESTAMP NOT NULL default CURRENT_TIMESTAMP,
                                        dueDate DATETIME,
                                        returnDate DATETIME,
                                        returned BOOL default FALSE
                                    )";

           // self::log_info($createBorrowRecordSql);
            $result = $conn->query($createBorrowRecordSql);
            if($result === false)
                throw new Exception("Fail to create Table " . systemConfig\config['borrowRecord'] ." $conn->error", errorCode\CreateDBTableError);

            $createReserveRecordSql = "CREATE TABLE IF NOT EXISTS " . systemConfig\config['reserveRecord'] . "(
                                        recordId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        userId INT NOT NULL,
                                        bookId INT NOT NULL,
                                        docId INT,
                                        beginDate TIMESTAMP NOT NULL default CURRENT_TIMESTAMP,
                                        dueDate TIMESTAMP NOT NULL default CURRENT_TIMESTAMP,
                                        checkedIn BOOL default FALSE
                                    )";
            if($conn->query($createReserveRecordSql) === false)
                throw new Exception("Fail to create Table " . systemConfig\config['reserveRecord'], errorCode\CreateDBTableError);

            $createDocTypeTableSql = "CREATE TABLE IF NOT EXISTS " . systemConfig\config['docType'] . "(
                                        typeId INT PRIMARY KEY,
                                        typeName VARCHAR(256)
                                    )";
            if($conn->query($createDocTypeTableSql) === false)
                throw new Exception("Fail to create Table " . systemConfig\config['docType'], errorCode\CreateDBTableError);



            $createWritingTableSql = "CREATE TABLE IF NOT EXISTS " . systemConfig\config['writingTable'] . "(
                                        recordId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        authorId INT ,
                                        docId INT
                                    )";
            if($conn->query($createWritingTableSql) === false)
                throw new Exception("Fail to create Table " . systemConfig\config['writingTable'], errorCode\CreateDBTableError);

            $createIdentifierTableSql = "CREATE TABLE IF NOT EXISTS " . systemConfig\config['identifierTable'] . "(
                                        identifierId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        docId INT,
                                        identifierNum VARCHAR(256),
                                        bookId INT,
                                        identifierType INT
                                    
                                    )";
            if($conn->query($createIdentifierTableSql) === false)
                throw new Exception("Fail to create Table " . systemConfig\config['identifierTable'], errorCode\CreateDBTableError);

            $createUrlTableSql = "CREATE TABLE IF NOT EXISTS " . systemConfig\config['urlTable'] . "(
                                        urlId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        docId INT ,
                                        url VARCHAR(4096)
                                    )";
            if($conn->query($createUrlTableSql) === false)
                throw new Exception("Fail to create Table " . systemConfig\config['urlTable'], errorCode\CreateDBTableError);

            $createLanguageTableSql = "CREATE TABLE IF NOT EXISTS " . systemConfig\config['languageTable'] . "(
                                        languageId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        lanName VARCHAR(256)
                                    )";
            if($conn->query($createLanguageTableSql) === false)
                throw new Exception("Fail to create Table " . systemConfig\config['languageTable'], errorCode\CreateDBTableError);

            $createSubjectTableSql = "CREATE TABLE IF NOT EXISTS " . systemConfig\config['subjectTable'] . "(
                                        subjectId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        subjectName VARCHAR(1024)
                                    )";
            if($conn->query($createSubjectTableSql) === false)
                throw new Exception("Fail to create Table " . systemConfig\config['subjectTable'], errorCode\CreateDBTableError);

            $createKeywordTableSql = "CREATE TABLE IF NOT EXISTS " . systemConfig\config['keywordTable'] . "(
                                        keywordId INT PRIMARY KEY,
                                        keyword VARCHAR(256)
                                    )";
            if($conn->query($createKeywordTableSql) === false)
                throw new Exception("Fail to create Table " . systemConfig\config['keywordTable'], errorCode\CreateDBTableError);

            $createSubjectRecord = "CREATE TABLE IF NOT EXISTS " . systemConfig\config['subjectRecord'] . "(
                                        recordId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        subjectId INT ,
                                        docId INT
                                    )";
            if($conn->query($createSubjectRecord) === false)
                throw new Exception("Fail to create Table " . systemConfig\config['subjectRecord'], errorCode\CreateDBTableError);

            $createDescriptionTable = "CREATE TABLE IF NOT EXISTS " . systemConfig\config['descriptionTable'] . "(
                                        descriptionId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        pages INT ,
                                        other VARCHAR(4096),
                                        bookId INT,
                                        docId INT
                                    )";
            if($conn->query($createDescriptionTable) === false)
                throw new Exception("Fail to create Table " . systemConfig\config['descriptionTable'], errorCode\CreateDBTableError);


            $createIdentifierTypeSql = "CREATE TABLE IF NOT EXISTS " . systemConfig\config['identifierType'] . "(
                                        typeId INT PRIMARY KEY,
                                        typeName VARCHAR(64)
                                    )";
            if($conn->query($createIdentifierTypeSql) === false)
                throw new Exception("Fail to create Table " . systemConfig\config['identifierType'], errorCode\CreateDBTableError);

            $createKeywordRecord = "CREATE TABLE IF NOT EXISTS " . systemConfig\config['keywordRecord'] . "(
                                        recordId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        keywordId INT NOT NULL,
                                        docId INT NOT NULL
                                    )";
            if($conn->query($createKeywordRecord) === false)
                throw new Exception("Fail to create Table " . systemConfig\config['keywordRecord'], errorCode\CreateDBTableError);

            $createBorrowRequestSql = "CREATE TABLE IF NOT EXISTS " . systemConfig\config['borrowRequest'] . "(
                                        recordId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        userId INT NOT NULL,
                                        bookId INT NOT NULL,
                                        docId INT NOT NULL,
                                        requestTime TIMESTAMP NOT NULL default CURRENT_TIMESTAMP,
                                        answerTime TIMESTAMP NOT NULL default CURRENT_TIMESTAMP,
                                        allow BOOL,
                                        isAnswered BOOL,
                                        adminId INT
                                    )";
            if($conn->query($createBorrowRequestSql) === false)
                throw new Exception("Fail to create Table " . systemConfig\config['borrowRecord'], errorCode\CreateDBTableError);


            $createPlaceTableSql = "CREATE TABLE IF NOT EXISTS " . systemConfig\config['placeTable'] . "(
                                            placeId INT PRIMARY KEY,
                                            placeName VARCHAR(4096)
                                        )";
            if($conn->query($createPlaceTableSql) === false)
                throw new Exception("Fail to create Table " . systemConfig\config['placeTable'], errorCode\CreateDBTableError);

            $createLastTableSql = "CREATE TABLE IF NOT EXISTS " . systemConfig\config['lastTable'] . "(
                                                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                                placeHolder INT
                                            )";
            if($conn->query($createLastTableSql) === false)
                throw new Exception("Fail to create Table " . systemConfig\config['lastTable'], errorCode\CreateDBTableError);

            $this->initTables($conn);

    }

    /**
     * @return mysqli
     */
    public function getConnection()
    {
        return $this->sqlConn;
    }


    /**
     * @throws Exception
     */
    public function initServer()
    {

            try {
                //$this->setTime();
                //$this->initLogFile();
                $conn = $this->createDatabase();
                $this->sqlConn = $conn;
                if(! $this->isTablePrepared($conn) )
                     $this->createTables($conn);


            } catch (Exception $e) {
                self::log_info($e->getMessage());
                throw $e;
            }

        }

    }






?>