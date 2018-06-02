<?php
/**
 * User: fanqu
 * Date: 2018/6/2
 * Time: 17:22
 */


namespace tg {


    require_once 'SystemFrame.php';
    require_once dirname(dirname(dirname(__FILE__))) . '/dbusers/dbadmin.php';
    require_once 'errorTable.php';

    class Document
    {
        private $docID;
        private $title;
        private $authors;
        private $publisher;
        private $source;
        private $urls;
        private $subjects;
        private $description;
        private $language;
        private $docType;

        /**
         * Document constructor.
         * @param $docID
         * @param $title
         * @param array $authors
         * @param $publisher
         * @param $source
         * @param $description
         * @param $language
         * @param array $subjects
         * @param array $urls
         * @param $docType
         * @throws \Exception
         */
        public function __construct($docID, $title, array $authors ,$docType = 'Book', $language = 'Chinese', array $subjects = null, $publisher = '', array $urls = null, $source = '', $description = '')
        {
            /*
            $this->docID = $docID;
            $this->title = $title;
            $this->publisher = $publisher;
            $this->source = $source;
            $this->description = $description;
            $this->language = $language;
            $this->docType = $docType;
            */
            $this->setDocID($docID);
            $this->setTitle($title);
            $this->setDocType($docType);
            $this->setLanguage($language);
            foreach ($authors as &$author)
                $this->addAuthor($author);
            if(isset($subjects))
                foreach ($subjects as &$subject)
                    $this->addSubject($subject);
            if(isset($urls))
                foreach ($urls as &$url)
                    $this->addUrl($url);
            if(isset($publisher)) {
                $this->setPublisher($publisher);
                if(isset($source)) {
                    $this->setSource($source);
                    if(isset($description))
                        $this->setDescription($description);
                }
            }




        }


        /**
         * @return int
         */
        public function getDocID()
        {
            return $this->docID;
        }

        /**
         * @param int $docID
         * @throws \Exception
         */
        public function setDocID($docID): void
        {
            global $config;
            $this->docID = $docID;
            if($this->isInDatabase() === true) {
                $insertIntoTableSql = "INSERT INTO " . $config['docTable'] . "( docId )" . " VALUES " . "( $docID )";
                $conn = \SystemFrame::instance()->getConnection();
                $result = $conn->query($insertIntoTableSql);
                if($result === false)
                    throw new \Exception("Fail to insert docId into Table " . $config['docTable'] . $conn->error,
                                            \errorCode\InsertIntoTableError);
            }

        }

        /**
         * @return string
         */
        public function getTitle()
        {
            return $this->title;
        }

        /**
         * @param string $title
         * @throws \Exception
         */
        public function setTitle($title): void
        {
            global $config;
            $this->title = $title;
            if ($this->isInDatabase()) {
                $insertIntoTableSql = "INSERT INTO " . $config['docTable'] . "( title )" . " VALUES " . "( $title )";
                $conn = \SystemFrame::instance()->getConnection();
                $result = $conn->query($insertIntoTableSql);
                if ($result === false)
                    throw new \Exception("Fail to insert title into Table " . $config['docTable'] . $conn->error,
                        \errorCode\InsertIntoTableError);
            }
        }

        /**
         * @return string
         */
        public function getPublisher()
        {
            return $this->publisher;
        }

        /**
         * @param string $publisher
         * @throws \Exception
         */
        public function setPublisher($publisher): void
        {
            $this->publisher = $publisher;
            global $config;
            if ($this->isInDatabase()) {
                $insertIntoTableSql = "INSERT INTO " . $config['docTable'] . "( publisher )" . " VALUES " . "( $publisher )";
                $conn = \SystemFrame::instance()->getConnection();
                $result = $conn->query($insertIntoTableSql);
                if ($result === false)
                    throw new \Exception("Fail to insert publisher into Table " . $config['docTable'] . $conn->error,
                        \errorCode\InsertIntoTableError);
            }
        }

        /**
         * @return string
         */
        public function getSource()
        {
            return $this->source;
        }

        /**
         * @param string $source
         * @throws \Exception
         */
        public function setSource($source): void
        {
            $this->source = $source;
            global $config;
            if ($this->isInDatabase()) {
                $insertIntoTableSql = "INSERT INTO " . $config['docTable'] . "( source )" . " VALUES " . "( $source )";
                $conn = \SystemFrame::instance()->getConnection();
                $result = $conn->query($insertIntoTableSql);
                if ($result === false)
                    throw new \Exception("Fail to insert source into Table " . $config['docTable'] . $conn->error,
                        \errorCode\InsertIntoTableError);
            }
        }

        /**
         * @return string
         */
        public function getDescription()
        {
            return $this->description;
        }

        /**
         * @param string $description
         * @throws \Exception
         */
        public function setDescription($description): void
        {
            $this->description = $description;
            global $config;
            if ($this->isInDatabase()) {
                $insertIntoTableSql = "INSERT INTO " . $config['docTable'] . "( description )" . " VALUES " . "( $description )";
                $conn = \SystemFrame::instance()->getConnection();
                $result = $conn->query($insertIntoTableSql);
                if ($result === false)
                    throw new \Exception("Fail to insert description into Table " . $config['docTable'] . $conn->error,
                        \errorCode\InsertIntoTableError);
            }
        }

        /**
         * @return mixed
         */
        public function getLanguage()
        {
            return $this->language;
        }

        /**
         * @param $language
         * @return bool
         * @throws \Exception
         */
        protected function existLanguage($language)
        {
            global $config;
            $conn = \SystemFrame::instance()->getConnection();
            $querySql = "SELECT * FROM " . $config['languageTable'] ." WHERE lanName = $language";
            $result = $conn->query($querySql);
            if($result === false)
                throw new \Exception("Fail to Query language " . $conn->error, \errorCode\QueryTableError);
            return $result->num_rows > 0;
        }

        /**
         * @param $language
         * @throws \Exception
         */
        protected function createLanguage($language)
        {
            global $config;
            $conn = \SystemFrame::instance()->getConnection();
            $insertSql = "INSERT INTO " . $config['languageTable'] . " ( lanName ) " ." VALUES ( $language )  ";
            $result = $conn->query($insertSql);
            if($result === false)
                throw new \Exception("Fail to insert new language " . $conn->error. \errorCode\InsertIntoTableError);
        }

        /**
         * @param mixed $language
         * @throws \Exception
         */
        public function setLanguage($language): void
        {
            $this->language = $language;
            global $config;
            if ($this->isInDatabase()) {
                if($this->existLanguage($language) === false)
                    $this->createLanguage($language);
                $insertIntoTableSql = "INSERT INTO " . $config['docTable'] . "( language )" . " VALUES " . "( " . " SELECT languageId FROM "
                                                . $config['languageTable'] . " WHERE lanName = $language )";
                $conn = \SystemFrame::instance()->getConnection();
                $result = $conn->query($insertIntoTableSql);
                if ($result === false)
                    throw new \Exception("Fail to insert language into Table " . $config['docTable'] . $conn->error,
                        \errorCode\InsertIntoTableError);
            }
        }

        /**
         * @return mixed
         */
        public function getDocType()
        {
            return $this->docType;
        }

        /**
         * @param string $docType
         * @throws \Exception
         */
        public function setDocType($docType): void
        {
            $this->docType = $docType;
            global $config;
            if ($this->isInDatabase()) {
                $insertIntoTableSql = "INSERT INTO " . $config['docTable'] . "( typeId )" . " VALUES " . "( " . " SELECT typeId FROM "
                    . $config['docType'] . " WHERE typeName = $docType )";
                $conn = \SystemFrame::instance()->getConnection();
                $result = $conn->query($insertIntoTableSql);
                if ($result === false)
                    throw new \Exception("Fail to insert docType into Table " . $config['docTable'] . $conn->error,
                        \errorCode\InsertIntoTableError);
            }
        }

        /**
         * @param $author
         * @return bool
         * @throws \Exception
         */
        protected function existAuthor($author)
        {
            global $config;
            $conn = \SystemFrame::instance()->getConnection();
            $querySql = "SELECT * FROM " . $config['authorTable'] ." WHERE lanName = $author";
            $result = $conn->query($querySql);
            if($result === false)
                throw new \Exception("Fail to Query author " . $conn->error, \errorCode\QueryTableError);
            return $result->num_rows > 0;
        }

        /**
         * @param $author
         * @throws \Exception
         */
        protected function createAuthor($author)
        {
            global $config;
            $conn = \SystemFrame::instance()->getConnection();
            $insertSql = "INSERT INTO " . $config['authorTable'] . " ( name ) " ." VALUES ( $author )  ";
            $result = $conn->query($insertSql);
            if($result === false)
                throw new \Exception("Fail to insert new author " . $conn->error, \errorCode\InsertIntoTableError);
        }

        /**
         * @param $author
         * @throws \Exception
         */
        public function addAuthor($author): void
        {
            if($this->isInDatabase()) {
                if ($this->existAuthor($author) === false)
                    $this->createAuthor($author);
            }
            $this->authors[] = $author;
        }

        /**
         * @param $url
         * @throws \Exception
         */
        public function addUrl($url): void
        {
            $this->urls[] = $url;
            global $config;
            if ($this->isInDatabase()) {
                $insertIntoTableSql = "INSERT INTO " . $config['d'] . "( docId , url )" . " VALUES " . "( $this->docID,  $url )";
                $conn = \SystemFrame::instance()->getConnection();
                $result = $conn->query($insertIntoTableSql);
                if ($result === false)
                    throw new \Exception("Fail to insert url into Table " . $config['urlTable'] . $conn->error,
                        \errorCode\InsertIntoTableError);
            }

        }

        /**
         * @param $subject
         * @return bool
         * @throws \Exception
         */
        protected function existSubject($subject)
        {
            global $config;
            $conn = \SystemFrame::instance()->getConnection();
            $querySql = "SELECT * FROM " . $config['subjectTable'] ." WHERE subjectName = $subject";
            $result = $conn->query($querySql);
            if($result === false)
                throw new \Exception("Fail to Query subject " . $conn->error, \errorCode\QueryTableError);
            return $result->num_rows > 0;
        }

        /**
         * @param $subject
         * @throws \Exception
         */
        protected function createSubject($subject): void
        {
            global $config;
            $conn = \SystemFrame::instance()->getConnection();
            $insertSql = "INSERT INTO " . $config['subjectTable'] . " ( subjectName ) " ." VALUES ( $subject )  ";
            $result = $conn->query($insertSql);
            if($result === false)
                throw new \Exception("Fail to create new subject " . $conn->error. \errorCode\InsertIntoTableError);
        }

        /**
         * @param $subject
         * @throws \Exception
         */
        public function addSubject($subject): void
        {
            global $config;
            if ($this->isInDatabase()) {
                $insertIntoTableSql = "INSERT INTO " . $config['subjectRecord'] . "( subjectId, docId )" . " VALUES " . "( ( " . " SELECT subjectId FROM "
                    . $config['subjectTable'] . " WHERE subjectName = $subject ), $this->docID )";
                $conn = \SystemFrame::instance()->getConnection();
                $result = $conn->query($insertIntoTableSql);
                if ($result === false)
                    throw new \Exception("Fail to insert subjectRecord into Table " . $config['subjectRecord'] . $conn->error,
                        \errorCode\InsertIntoTableError);
            }
            $this->subjects[] = $subject;
        }

        public function &getAuthors()
        {
            return $this->authors;
        }

        public function &getUrls()
        {
            return $this->urls;
        }

        public function &getSubjects()
        {
            return $this->subjects;
        }

        /**
         * @throws \Exception
         * @return bool
         */
        protected function isInDatabase()
        {
            global $config;
            $conn = \SystemFrame::instance()->getConnection();
            if(isset($this->docID)) {
                $queryDocSql = "SELECT * FROM " . $config['docTable'] . " WHERE docId = $this->docID";
                $result = $conn->query($queryDocSql);
                if($result === false)
                    throw new \Exception("Fail to query from table " . $config['docTable'] . $conn->error, \errorCode\QueryTableError);
                if($result->num_rows > 0)
                    return true;
                else return false;
            }
            else return false;
        }



    }

}