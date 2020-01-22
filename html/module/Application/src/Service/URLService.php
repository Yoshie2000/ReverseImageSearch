<?php


namespace Application\Service;


use Application\Model\URLModel;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Adapter\Driver\StatementInterface;
use Zend\Db\ResultSet\ResultSet;

class URLService implements URLServiceInterface
{

    /** @var AdapterInterface */
    private $adapter;

    /**
     * URLService constructor.
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /** ${@inheritDoc} */
    public function getAllURLs()
    {
        $allUrls = [];

        /** @var AdapterInterface $adapter */
        $adapter = $this->adapter;

        //$sql = "CREATE TABLE URLS (ID INT NOT NULL AUTO_INCREMENT, URL VARCHAR(500) NOT NULL, ContentHash VARCHAR(32) NOT NULL, ImageCount INT NOT NULL, PRIMARY KEY(ID));";
        //$sql = "DROP TABLE URLS;";
        $sql = "SELECT * FROM URLS ORDER BY ID DESC;";

        /** @var StatementInterface $statement */
        $statement = $adapter->createStatement($sql);
        /** @var ResultInterface $result */
        $result = $statement->execute();

        if ($result->isQueryResult()) {
            $resultSet = new ResultSet;
            $resultSet->initialize($result);

            foreach ($resultSet as $row) {
                $urlModel = new URLModel;
                $urlModel->setId($row["ID"]);
                $urlModel->setUrl($row["URL"]);
                $urlModel->setContentHash($row["ContentHash"]);
                $urlModel->setImageCount($row["ImageCount"]);
                $allUrls[] = $urlModel;
            }
        }

        return $allUrls;
    }

    public function saveURL($url)
    {
        // URL exists and hashes are the same => nothing changed, no need to crawl again
        $urlData = $this->getURLInfo($url);
        $contentHash = $this->getURLHash($url);
        if ($urlData !== null && $urlData->getContentHash() === $contentHash) {
            return;
        }

        /** @var AdapterInterface $adapter */
        $adapter = $this->adapter;

        $sql = "INSERT INTO URLS (ID, URL, ContentHash, ImageCount) VALUES(null, :url, :contentHash, 0);";

        /** @var StatementInterface $statement */
        $statement = $adapter->createStatement($sql);

        /** @var ResultInterface $result */
        $statement->execute([
            ":url"         => $url,
            ":contentHash" => $contentHash
        ]);
    }

    public function getURLInfo($url)
    {
        $urlModel = null;

        /** @var AdapterInterface $adapter */
        $adapter = $this->adapter;

        $sql = "SELECT * FROM URLS WHERE URL = :url;";

        /** @var StatementInterface $statement */
        $statement = $adapter->createStatement($sql);
        /** @var ResultInterface $result */
        $result = $statement->execute([
            ":url" => $url,
        ]);

        if ($result->isQueryResult()) {
            $resultSet = new ResultSet;
            $resultSet->initialize($result);

            foreach ($resultSet as $row) {
                $urlModel = new URLModel();
                $urlModel->setId($row["ID"]);
                $urlModel->setUrl($row["URL"]);
                $urlModel->setContentHash($row["ContentHash"]);
                $urlModel->setImageCount($row["ImageCount"]);
                break;
            }
        }

        return $urlModel;
    }

    public function getHTML($url)
    {
        return utf8_encode(file_get_contents($url));
    }

    public function getURLHash($url)
    {
        return hash("md5", $this->getHTML($url));
    }
}