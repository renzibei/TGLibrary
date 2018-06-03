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
        public function __construct($title, array $authors ,$docType = 'Book', $language = 'Chinese', array $subjects = null, $publisher = '', array $urls = null, $source = '', $description = '')
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
            //$this->setDocID($docID);
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
         * @throws \Exception
         */
        public function updateData()
        {
            if(isset($this->authors))
                foreach ($this->authors as &$author)
                    $this->addAuthor($author);
            if(isset($this->subjects))
                foreach ($this->subjects as &$subject)
                    $this->addSubject($subject);
            if(isset($this->urls))
                foreach ($this->urls as &$url)
                    $this->addUrl($url);
            if(isset($this->publisher))
                $this->setPublisher($this->publisher);
            if(isset($this->source))
                $this->setSource($this->source);
            if(isset($this->description))
                $this->setDescription($this->description);


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
            //global systemConfig\config;
            $this->docID = $docID;
            /*
            if($this->isInDatabase() === true) {
                $insertIntoTableSql = "INSERT INTO " . systemConfig\config['docTable'] . "( docId )" . " VALUES " . "( $docID )";
                $conn = SystemFrame::instance()->getConnection();
                $result = $conn->query($insertIntoTableSql);
                if($result === false)
                    throw new \Exception("Fail to insert docId into Table " . systemConfig\config['docTable'] . $conn->error,
                                            errorCode\InsertIntoTableError);
            }
            */

        }

        /**
         * @return string
         */
        public function getTitle()
        {
            return $this->title;
        }

        /**
         * @throws \Exception
         */
        public function updateTitle()
        {
            if ($this->isInDatabase()) {
                $insertIntoTableSql = "INSERT INTO " . systemConfig\config['docTable'] . "( title )" . " VALUES " . "( $this->title )";
                $conn = SystemFrame::instance()->getConnection();
                $result = $conn->query($insertIntoTableSql);
                if ($result === false)
                    throw new \Exception("Fail to insert title into Table " . systemConfig\config['docTable'] . $conn->error,
                        errorCode\InsertIntoTableError);
            }
        }

        /**
         * @param string $title
         * @throws \Exception
         */
        public function setTitle($title): void
        {
            //global systemConfig\config;
            $this->title = $title;
            $this->updateTitle();
        }

        /**
         * @return string
         */
        public function getPublisher()
        {
            return $this->publisher;
        }

        /**
         * @throws \Exception
         */
        public function updatePublisher()
        {
            if ($this->isInDatabase()) {
                $insertIntoTableSql = "INSERT INTO " . systemConfig\config['docTable'] . "( publisher )" . " VALUES " . "( $this->publisher )";
                $conn = SystemFrame::instance()->getConnection();
                $result = $conn->query($insertIntoTableSql);
                if ($result === false)
                    throw new \Exception("Fail to insert publisher into Table " . systemConfig\config['docTable'] . $conn->error,
                        errorCode\InsertIntoTableError);
            }
        }
        /**
         * @param string $publisher
         * @throws \Exception
         */
        public function setPublisher($publisher): void
        {
            $this->publisher = $publisher;
            //global systemConfig\config;
            $this->updatePublisher();
        }

        /**
         * @return string
         */
        public function getSource()
        {
            return $this->source;
        }

        /**
         * @throws \Exception
         */
        public function updateSource()
        {
            if ($this->isInDatabase()) {
                $insertIntoTableSql = "INSERT INTO " . systemConfig\config['docTable'] . "( source )" . " VALUES " . "( $this->source )";
                $conn = SystemFrame::instance()->getConnection();
                $result = $conn->query($insertIntoTableSql);
                if ($result === false)
                    throw new \Exception("Fail to insert source into Table " . systemConfig\config['docTable'] . $conn->error,
                        errorCode\InsertIntoTableError);
            }
        }

        /**
         * @param string $source
         * @throws \Exception
         */
        public function setSource($source): void
        {
            $this->source = $source;
            //global systemConfig\config;
            $this->updateSource();
        }

        /**
         * @return string
         */
        public function getDescription()
        {
            return $this->description;
        }

        /**
         * @throws \Exception
         */
        public function updateDescription()
        {
            if ($this->isInDatabase()) {
                $insertIntoTableSql = "INSERT INTO " . systemConfig\config['docTable'] . "( description )" . " VALUES " . "( $this->description )";
                $conn = SystemFrame::instance()->getConnection();
                $result = $conn->query($insertIntoTableSql);
                if ($result === false)
                    throw new \Exception("Fail to insert description into Table " . systemConfig\config['docTable'] . $conn->error,
                        errorCode\InsertIntoTableError);
            }
        }

        /**
         * @param string $description
         * @throws \Exception
         */
        public function setDescription($description): void
        {
            $this->description = $description;
            //global systemConfig\config;
            $this->updateDescription();
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
            //global systemConfig\config;
            $conn = SystemFrame::instance()->getConnection();
            $querySql = "SELECT * FROM " . systemConfig\config['languageTable'] ." WHERE lanName = $language";
            $result = $conn->query($querySql);
            if($result === false)
                throw new \Exception("Fail to Query language " . $conn->error, errorCode\QueryTableError);
            return $result->num_rows > 0;
        }

        /**
         * @param $language
         * @throws \Exception
         */


        protected function createLanguage($language)
        {
            //global systemConfig\config;
            $conn = SystemFrame::instance()->getConnection();
            $insertSql = "INSERT INTO " . systemConfig\config['languageTable'] . " ( lanName ) " ." VALUES ( $language )  ";
            $result = $conn->query($insertSql);
            if($result === false)
                throw new \Exception("Fail to insert new language " . $conn->error. errorCode\InsertIntoTableError);
        }

        /**
         * @throws \Exception
         */
        public function updateLanguage()
        {
            if ($this->isInDatabase() && isset($this->language)) {
                if($this->existLanguage($this->language) === false)
                    $this->createLanguage($this->language);
                $insertIntoTableSql = "INSERT INTO " . systemConfig\config['docTable'] . "( language )" . " VALUES " . "( " . " SELECT languageId FROM "
                    . systemConfig\config['languageTable'] . " WHERE lanName = $this->language )";
                $conn = SystemFrame::instance()->getConnection();
                $result = $conn->query($insertIntoTableSql);
                if ($result === false)
                    throw new \Exception("Fail to insert language into Table " . systemConfig\config['docTable'] . $conn->error,
                        errorCode\InsertIntoTableError);
            }
        }

        /**
         * @param mixed $language
         * @throws \Exception
         */
        public function setLanguage($language): void
        {
            $this->language = $language;
            //global systemConfig\config;
            $this->updateLanguage();
        }

        /**
         * @return mixed
         */
        public function getDocType()
        {
            return $this->docType;
        }

        /**
         * @throws \Exception
         */
        public function updateDocType()
        {
            if ($this->isInDatabase()) {
                $insertIntoTableSql = "INSERT INTO " . systemConfig\config['docTable'] . "( typeId )" . " VALUES " . "( " . " SELECT typeId FROM "
                    . systemConfig\config['docType'] . " WHERE typeName = $this->docType )";
                $conn = SystemFrame::instance()->getConnection();
                $result = $conn->query($insertIntoTableSql);
                if ($result === false)
                    throw new \Exception("Fail to insert docType into Table " . systemConfig\config['docTable'] . $conn->error,
                        errorCode\InsertIntoTableError);
            }
        }

        /**
         * @param string $docType
         * @throws \Exception
         */
        public function setDocType($docType): void
        {
            $this->docType = $docType;
            //global systemConfig\config;
            $this->updateDocType();
        }

        /**
         * @param $author
         * @return bool
         * @throws \Exception
         */
        protected function existAuthor($author)
        {
            //global systemConfig\config;
            $conn = SystemFrame::instance()->getConnection();
            $querySql = "SELECT * FROM " . systemConfig\config['authorTable'] ." WHERE lanName = $author";
            $result = $conn->query($querySql);
            if($result === false)
                throw new \Exception("Fail to Query author " . $conn->error, errorCode\QueryTableError);
            return $result->num_rows > 0;
        }

        /**
         * @param $author
         * @throws \Exception
         */
        protected function createAuthor($author)
        {
            //global systemConfig\config;
            $conn = SystemFrame::instance()->getConnection();
            $insertSql = "INSERT INTO " . systemConfig\config['authorTable'] . " ( name ) " ." VALUES ( $author )  ";
            $result = $conn->query($insertSql);
            if($result === false)
                throw new \Exception("Fail to insert new author " . $conn->error, errorCode\InsertIntoTableError);
        }

        /**
         * @param $author
         * @return bool
         * @throws \Exception
         */
        protected function existWriting($author)
        {
            $conn = SystemFrame::instance()->getConnection();
            $querySql = "SELECT * FROM " . systemConfig\config['writingTable'] ." WHERE lanName = $author AND docId = $this->docID";
            $result = $conn->query($querySql);
            if($result === false)
                throw new \Exception("Fail to Query author " . $conn->error, errorCode\QueryTableError);
            return $result->num_rows > 0;
        }

        /**
         * @param $author
         * @throws \Exception
         */
        protected function addWriting($author)
        {
            $conn = SystemFrame::instance()->getConnection();
            $insertIntoTableSql = "INSERT INTO " . systemConfig\config['docTable'] . "( authorId , docId )" . " VALUES " . "( ( " . " SELECT authorId FROM "
                . systemConfig\config['docType'] . " WHERE typeName = $this->docType ) , $this->docID )";
            $result = $conn->query($insertIntoTableSql);
            if($result === false)
                throw new \Exception("Fail to insert new writing author " . $conn->error, errorCode\InsertIntoTableError);
        }

        /**
         * @throws \Exception
         */
        public function updateAuthor()
        {

            if($this->isInDatabase()) {
                foreach ($this->authors as &$author) {
                    if ($this->existAuthor($author) === false)
                        $this->createAuthor($author);
                    if($this->existWriting($author) === false)
                        $this->addWriting($author);
                }
            }
        }

        /**
         * @param $author
         * @throws \Exception
         */
        public function addAuthor($author): void
        {
            $this->authors[] = $author;
            $this->updateAuthor();
        }

        /**
         * @param $url
         * @return bool
         * @throws \Exception
         */
        protected function existUrl($url)
        {
            $conn = SystemFrame::instance()->getConnection();
            $querySql = "SELECT * FROM " . systemConfig\config['urlTable'] ." WHERE  docId = $this->docID AND url = $url ";
            $result = $conn->query($querySql);
            if($result === false)
                throw new \Exception("Fail to Query url " . $conn->error, errorCode\QueryTableError);
            return $result->num_rows > 0;
        }

        /**
         * @throws \Exception
         */
        public function updateUrl()
        {
            if ($this->isInDatabase()) {
                foreach ($this->urls as &$url) {
                    if($this->existUrl($url) === false) {
                        $insertIntoTableSql = "INSERT INTO " . systemConfig\config['urlTable'] . "( docId , url )" . " VALUES " . "( $this->docID,  $url )";
                        $conn = SystemFrame::instance()->getConnection();
                        $result = $conn->query($insertIntoTableSql);
                        if ($result === false)
                            throw new \Exception("Fail to insert url into Table " . systemConfig\config['urlTable'] . $conn->error,
                                errorCode\InsertIntoTableError);
                    }
                 }
             }
        }


        /**
         * @param $url
         * @throws \Exception
         */
        public function addUrl($url): void
        {
            $this->urls[] = $url;
            //global systemConfig\config;
            $this->updateUrl();

        }

        /**
         * @param $subject
         * @return bool
         * @throws \Exception
         */
        protected function existSubject($subject)
        {
            //global systemConfig\config;
            $conn = SystemFrame::instance()->getConnection();
            $querySql = "SELECT * FROM " . systemConfig\config['subjectTable'] ." WHERE subjectName = $subject";
            $result = $conn->query($querySql);
            if($result === false)
                throw new \Exception("Fail to Query subject " . $conn->error, errorCode\QueryTableError);
            return $result->num_rows > 0;
        }

        /**
         * @param $subject
         * @throws \Exception
         */
        protected function createSubject($subject): void
        {
            //global systemConfig\config;
            $conn = SystemFrame::instance()->getConnection();
            $insertSql = "INSERT INTO " . systemConfig\config['subjectTable'] . " ( subjectName ) " ." VALUES ( $subject )  ";
            $result = $conn->query($insertSql);
            if($result === false)
                throw new \Exception("Fail to create new subject " . $conn->error. errorCode\InsertIntoTableError);
        }

        /**
         * @throws \Exception
         */
        public function updateSubject()
        {
            if ($this->isInDatabase()) {
                foreach($this->subjects as $subject) {
                    $insertIntoTableSql = "INSERT INTO " . systemConfig\config['subjectRecord'] . "( subjectId, docId )" . " VALUES " . "( ( " . " SELECT subjectId FROM "
                        . systemConfig\config['subjectTable'] . " WHERE subjectName = $subject ), $this->docID )";
                    $conn = SystemFrame::instance()->getConnection();
                    $result = $conn->query($insertIntoTableSql);
                    if ($result === false)
                        throw new \Exception("Fail to insert subjectRecord into Table " . systemConfig\config['subjectRecord'] . $conn->error,
                            errorCode\InsertIntoTableError);
                }
            }
        }


        /**
         * @param $subject
         * @throws \Exception
         */
        public function addSubject($subject): void
        {
            //global systemConfig\config;

            $this->subjects[] = $subject;
            $this->updateSubject();
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
            //global systemConfig\config;
            $conn = SystemFrame::instance()->getConnection();
            if(isset($this->docID)) {
                $queryDocSql = "SELECT * FROM " . systemConfig\config['docTable'] . " WHERE docId = $this->docID";
                $result = $conn->query($queryDocSql);
                if($result === false)
                    throw new \Exception("Fail to query from table " . systemConfig\config['docTable'] . $conn->error, errorCode\QueryTableError);
                if($result->num_rows > 0)
                    return true;
                else return false;
            }
            else return false;
        }



    }

}