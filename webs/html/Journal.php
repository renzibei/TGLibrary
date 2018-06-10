<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/9
 * Time: 17:10
 */

namespace tg;


class Journal extends Document
{
    protected $creationYear;
    protected $ISSNs;
    protected $format;


    /**
     * Journal constructor.
     * @param $title
     * @param $authors
     * @param string $language
     * @param null $creationYear
     * @param null $subjects
     * @param string $publisher
     * @param null $urls
     * @param string $source
     * @param string $description
     * @param string $ISSNs
     * @param string $format
     * @param null $docId
     * @throws \Exception
     */
    public function __construct($title, $authors , $language = 'Chinese', $creationYear = NULL, $subjects = null, $publisher = '', $urls = null, $source = '', $description = '', $ISSNs = '', $format = '',  $docId = NULL)
    {

        $this->docType = 'Journal';
        $this->title = $title;
        if(!empty($authors)) {
            if (!is_array($authors))
                $this->authors = array($this->authors);
            else
                $this->authors = $authors;
        }
        if(!empty($creationYear)) {
            $this->creationYear = $creationYear;
        }
        $this->language = $language;
        if(!empty($subjects)) {
            if(!is_array($subjects))
                $this->subjects = array($subjects);
            else
                $this->subjects = $subjects;
        }
        $this->publisher = $publisher;
        if(!empty($urls)) {
            if(!is_array($urls))
                $this->urls = array($urls);
            else
                $this->urls = $urls;
        }
        $this->source = $source;
        $this->description = $description;
        if(!empty($ISSNs)) {
            if(!is_array($ISSNs))
                $this->ISSNs = array($ISSNs);
            else
                $this->ISSNs = $ISSNs;
        }
        $this->format = $format;
        $this->docID = $docId;
        $this->updateData();
    }


    public function updateData()
    {
        if($this->isInDatabase()) {
            $conn = SystemFrame::instance()->getConnection();
            $updateSql = "UPDATE " . systemConfig\config['docTable'] . " SET ";
            if(!empty($this->title))
                $updateSql .= " title = '$this->title' ";
            if(isset($this->docType))
                $updateSql .= (", typeId = " . \tg\docTypeArray[$this->docType]);
            if(!empty($this->publisher))
                $updateSql .= ", publisher = '$this->publisher' ";
            if(!empty($this->format))
                $updateSql .= ", format = '$this->format' ";
            if(isset($this->creationYearYear))
                $updateSql .= ", publicationYear = $this->creationYear ";
            if(!empty($this->source))
                $updateSql .= ", source = '$this->source' ";
            if(!empty($this->description))
                $updateSql .= ", description = '$this->description' ";
            $updateSql .= " WHERE docId = $this->docID ";
            /*
            if(!empty($this->language))
                $updateSql .= ", languageId =  ( SELECT languageId FROM "
                    . systemConfig\config['languageTable'] . " WHERE lanName = '$this->language' )";
            */
            $result = $conn->query($updateSql);
            if($result === false)
                throw new \Exception("Fail to update Book Data " . $conn->error, errorCode\UpdateTableError);
            if(!empty($this->language))
                $this->updateLanguage();
            if(!empty($this->authors))
                $this->updateAuthor();
            if(!empty($this->subjects))
                $this->updateSubject();
            if(!empty($this->urls))
                $this->updateUrl();
            if(!empty($this->ISSNs))
                $this->updateISSN();

        }
    }

    /**
     * @throws \Exception
     */
    protected function updateISSN()
    {
        if($this->isInDatabase())
            if(!empty($this->ISSNs)) {
                $conn = SystemFrame::instance()->getConnection();
                foreach ($this->ISSNs as $ISSN) {
                    $insertIssnSql = "INSERT INTO " . \tg\systemConfig\config['identifierTable'] . " ( docId, identifierNum, identifierType ) 
                                    SELECT * FROM ( SELECT $this->docID AS a, '$ISSN' AS b," .  identifierTypeArray["ISSN"] . " AS c ) AS tmp 
                                     WHERE NOT EXISTS (SELECT identifierId FROM " . systemConfig\config['identifierTable'] . " WHERE docId = $this->docID AND identifierNum = '$ISSN' AND identifierType = " . identifierTypeArray["ISSN"]. ")";
                    $result = $conn->query($insertIssnSql);
                    if($result === false)
                        throw new \Exception("Fail to update ISSN " . $conn->error, errorCode\InsertIntoTableError);
                }
            }
    }

}