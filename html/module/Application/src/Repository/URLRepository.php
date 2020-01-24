<?php

namespace Application\Repository;

use Application\Model\URLModel;
use Application\Service\HTMLServiceInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Adapter\Driver\StatementInterface;
use Zend\Db\ResultSet\ResultSet;

class URLRepository implements URLRepositoryInterface
{

    /** @var AdapterInterface */
    private $adapter;
    /** @var HTMLServiceInterface */
    private $htmlService;

    /**
     * URLRepository constructor.
     * @param AdapterInterface $adapter
     * @param HTMLServiceInterface $htmlService
     */
    public function __construct(
        AdapterInterface $adapter,
        HTMLServiceInterface $htmlService
    ) {
        $this->adapter = $adapter;
        $this->htmlService = $htmlService;
    }

    /** ${@inheritDoc} */
    public function getAllURLs(): array
    {
        $allUrls = [];

        /** @var AdapterInterface $adapter */
        $adapter = $this->adapter;

        $sql = "SELECT * FROM URLS ORDER BY ID DESC;";
        //$sql = "CREATE TABLE URLS (ID INT NOT NULL AUTO_INCREMENT, URL VARCHAR(500) NOT NULL, ContentHash VARCHAR(32) NOT NULL, ImageCount INT NOT NULL, PRIMARY KEY(ID));";
        //$sql = "DROP TABLE URLS;";

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

    /** ${@inheritDoc} */
    public function getURLInfo(string $url)
    {
        $urlModel = null;

        /** @var AdapterInterface $adapter */
        $adapter = $this->adapter;

        $sql = "SELECT * FROM URLS WHERE URL = ':url';";

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

    /** ${@inheritDoc} */
    public function saveURL(string $url): int
    {
        $url = trim($url);

        $urlData = $this->getURLInfo($url);
        $contentHash = $this->htmlService->getHash($url);

        // URL exists and hashes are the same => nothing changed, no need to crawl again
        if ($urlData !== null && $urlData->getContentHash() === $contentHash) {
            return URLRepositoryInterface::URL_NOT_CHANGED;
        }

        // URL exists and hashes are not the same => simply update the hash stored in the database
        if ($urlData !== null) {
            $this->updateURLHash($url);
            return URLRepositoryInterface::URL_CHANGED;
        }

        // URL doesnt exist yet

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

        return URLRepositoryInterface::URL_CREATED;
    }

    public function updateURLHash(string $url): void
    {
        $urlHash = $this->htmlService->getHash($url);

        /** @var AdapterInterface $adapter */
        $adapter = $this->adapter;

        $sql = "UPDATE URLS SET ContentHash = :contentHash WHERE URL = :url";

        /** @var StatementInterface $statement */
        $statement = $adapter->createStatement($sql);

        /** @var ResultInterface $result */
        $statement->execute([
            ":url"         => $url,
            ":contentHash" => $urlHash
        ]);

    }
}