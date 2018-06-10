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
    require_once dirname(dirname(dirname(__FILE__))) . '/dbusers/docInfo.php';

    class Document implements \JsonSerializable
    {
        protected $docID;
        protected $title;
        protected $authors;
        protected $publisher;
        protected $source;
        protected $urls;
        protected $subjects;
        protected $description;
        protected $language;
        protected $docType;

        public function jsonSerialize()
        {
            $vars = get_object_vars($this);
            return $vars;
        }


        /*
        public function __construct($title, array $authors ,$docType = 'Book', $language = 'Chinese', array $subjects = null, $publisher = '', array $urls = null, $source = '', $description = '', $docId = NULL)
        {

            $this->docID = $docId;
            $this->title = $title;
            $this->publisher = $publisher;
            $this->source = $source;
            $this->description = $description;
            $this->language = $language;
            $this->docType = $docType;
            $this->authors = $authors;
            $this->subjects = $subjects;
            $this->urls = $urls;

            $this->updateData();

            /*
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
            */





       // }


        /**
         * @throws \Exception
         */
        public function updateData()
        {

            if($this->isInDatabase()) {
                $conn = SystemFrame::instance()->getConnection();
                $updateSql = "UPDATE " . systemConfig\config['docTable'] . " SET ";
                if(isset($this->title))
                    $updateSql .= " title = '$this->title' ";
                if(isset($this->docType))
                    $updateSql .= (", docId = " . \tg\docTypeArray[$this->docType]);
                if(isset($this->publisher))
                    $updateSql .= ", publisher = '$this->publisher' ";
                if(isset($this->source))
                    $updateSql .= ", source = '$this->source' ";
                if(isset($this->description))
                    $updateSql .= ", description = '$this->description' ";
                if(isset($this->language))
                    $updateSql .= ", languageId =  ( SELECT languageId FROM "
                        . systemConfig\config['languageTable'] . " WHERE lanName = $this->language )";
                $result = $conn->query($updateSql);
                if($result === false)
                    throw new \Exception("Fail to update Book Data " . $conn->error, errorCode\UpdateTableError);

                if(isset($this->authors))
                    $this->updateAuthor();
                if(isset($this->subjects))
                    $this->updateSubject();
                if(isset($this->urls))
                    $this->updateUrl();

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
         * @param $authors
         * @param int $mode
         * @throws \Exception
         */
        public function setAuthors($authors, $mode = 1)
        {
            $this->authors = $authors;
            if($mode === 1)
                $this->updateAuthor();
        }

        /**
         * @param int $docID
         * @throws \Exception
         */
        public function setDocID($docID)
        {

		    $this->docID = $docID;
        }

        /**
         * @param $urls
         * @param int $mode
         * @throws \Exception
         */
        public function setUrls($urls, $mode = 1)
        {
            $this->urls = $urls;
            if($mode === 1)
                $this->updateUrl();
        }


        /**
         * @param $subjects
         * @param int $mode
         * @throws \Exception
         */
        public function setSubjects($subjects, $mode = 1)
        {
            $this->subjects = $subjects;
            if($mode === 1)
                $this->updateSubject();
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
                $insertIntoTableSql = "UPDATE " . systemConfig\config['docTable'] . " SET title = '" .   $this->title . "' WHERE docId = $this->docID";
                $conn = SystemFrame::instance()->getConnection();
                $result = $conn->query($insertIntoTableSql);
                if ($result === false)
                    throw new \Exception("Fail to update title in Table " . systemConfig\config['docTable'] . $conn->error,
                        errorCode\InsertIntoTableError);
            }
        }

        /**
         * @param string $title
         * @throws \Exception
         */
        public function setTitle($title)
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
                $insertIntoTableSql = "UPDATE " . systemConfig\config['docTable'] . " SET publisher = '" .  $this->publisher . "' WHERE docId = $this->docID";
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
        public function setPublisher($publisher)
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
                $insertIntoTableSql = "UPDATE " . systemConfig\config['docTable'] . " SET source = " . " '$this->source' " . " WHERE docId = $this->docID";
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
        public function setSource($source)
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
                $insertIntoTableSql = "UPDATE " . systemConfig\config['docTable'] . " SET description = " .  " '$this->description' " . " WHERE docId = $this->docID ";
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
        public function setDescription($description)
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
            $querySql = "SELECT * FROM " . systemConfig\config['languageTable'] ." WHERE lanName = '$language' ";
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
            $insertSql = "INSERT INTO " . systemConfig\config['languageTable'] . " ( lanName ) " ." VALUES ( '$language' )  ";
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
                $insertIntoTableSql = "UPDATE " . systemConfig\config['docTable'] . " SET languageId = " .  "( " . " SELECT languageId FROM "
                    . systemConfig\config['languageTable'] . " WHERE lanName = '$this->language' )" . " WHERE docId = $this->docID";
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
        public function setLanguage($language)
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
                $insertIntoTableSql = "UPDATE " . systemConfig\config['docTable'] . " SET typeId = " .  "( " . " SELECT typeId FROM "
                    . systemConfig\config['docType'] . " WHERE typeName = '$this->docType' )" . " WHERE docId = $this->docID";
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
        public function setDocType($docType)
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
            $querySql = "SELECT * FROM " . systemConfig\config['authorTable'] ." WHERE name = '". "$author" . "'";
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
            $insertSql = "INSERT INTO " . systemConfig\config['authorTable'] . " ( name ) " ." VALUES ( '$author' )  ";
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
            $querySql = "SELECT * FROM " . systemConfig\config['writingTable'] ." WHERE  authorId = (SELECT authorId FROM authorTable WHERE name  = '$author' ) AND docId = $this->docID";
            $result = $conn->query($querySql);
            if($result === false)
                throw new \Exception("Fail to Query author " . $conn->error, errorCode\QueryTableError);
            return $result->num_rows > 0;
        }

        /**
         * @param $author
         * @throws \Exception
         * There is a bug here. The author.
         */
        protected function addWriting($author)
        {
            $conn = SystemFrame::instance()->getConnection();
            $insertIntoTableSql = "INSERT INTO " . systemConfig\config['writingTable'] . "( authorId , docId )" . " VALUES " . "( ( " . " SELECT authorId FROM "
                . systemConfig\config['authorTable'] . " WHERE name = '$author' ) , $this->docID )";
            $result = $conn->query($insertIntoTableSql);
            if($result === false)
                throw new \Exception("Fail to insert new writing author " . $conn->error, errorCode\InsertIntoTableError);
        }

        /**
         * @throws \Exception
         */
        public function updateAuthor()
        {

            if(!empty($this->authors) && $this->isInDatabase()) {
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
        public function addAuthor($author)
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
            $querySql = "SELECT * FROM " . systemConfig\config['urlTable'] ." WHERE  docId = $this->docID AND url = '$url' ";
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
            if (!empty($this->urls) && $this->isInDatabase()) {
                foreach ($this->urls as &$url) {
                    if($this->existUrl($url) === false) {
                        $insertIntoTableSql = "INSERT INTO " . systemConfig\config['urlTable'] . "( docId , url )" . " VALUES " . "( $this->docID,  '$url' )";
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
        public function addUrl($url)
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
            $querySql = "SELECT * FROM " . systemConfig\config['subjectTable'] ." WHERE subjectName = '$subject'";
            $result = $conn->query($querySql);
            if($result === false)
                throw new \Exception("Fail to Query subject " . $conn->error, errorCode\QueryTableError);
            return $result->num_rows > 0;
        }

        /**
         * @param $subject
         * @throws \Exception
         */
        protected function createSubject($subject)
        {
            //global systemConfig\config;
            $conn = SystemFrame::instance()->getConnection();
            $insertSql = "INSERT INTO " . systemConfig\config['subjectTable'] . " ( subjectName ) " ." VALUES ( '$subject' )  ";
            $result = $conn->query($insertSql);
            if($result === false)
                throw new \Exception("Fail to create new subject " . $conn->error. errorCode\InsertIntoTableError);
        }

        /**
         * @throws \Exception
         */
        public function updateSubject()
        {

            if ($this->isInDatabase() && !empty($this->subjects)) {
                $conn = SystemFrame::instance()->getConnection();
                foreach($this->subjects as $subject) {
                    if($this->existSubject($subject) === false)
                        $this->createSubject($subject);
                    $querySubjectIdSql = "SELECT subjectId FROM " .
                                        \tg\systemConfig\config['subjectTable'] . " WHERE subjectName = '" . "$subject' ";
                    $result = $conn->query($querySubjectIdSql);
                    if($result === false)
                        throw new \Exception("Fail to query recordId " . $conn->error);
                    $subjectId = $result->fetch_assoc()['subjectId'];
                    $insertIntoTableSql = "INSERT INTO " . systemConfig\config['subjectRecord'] . "( subjectId, docId )"
                        . "SELECT * FROM ( SELECT '" . $subjectId . "' as a, '". $this->docID . "' as b ) AS tmp WHERE NOT EXISTS ( SELECT subjectId FROM "
                        . \tg\systemConfig\config['subjectRecord']
                        .  " WHERE (subjectId = $subjectId AND docId = $this->docID)  ) " ;

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
        public function addSubject($subject)
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
                $queryDocSql = "SELECT docId FROM " . systemConfig\config['docTable'] . " WHERE docId = $this->docID";
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
