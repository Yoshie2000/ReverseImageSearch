<?php


namespace Application\Repository;


use Application\Model\ImageModel;
use Application\Service\HammingDistanceServiceInterface;
use Application\Service\HashServiceInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Adapter\Driver\StatementInterface;
use Zend\Db\ResultSet\ResultSet;

class ImageRepository implements ImageRepositoryInterface
{

    /** @var AdapterInterface */
    private $adapter;
    /** @var HashServiceInterface */
    private $hashService;
    /** @var HammingDistanceServiceInterface */
    private $hammingDistanceService;

    /**
     * ImageRepository constructor.
     * @param AdapterInterface $adapter
     * @param HashServiceInterface $hashService
     * @param HammingDistanceServiceInterface $hammingDistanceService
     */
    public function __construct(
        AdapterInterface $adapter,
        HashServiceInterface $hashService,
        HammingDistanceServiceInterface $hammingDistanceService
    ) {
        $this->adapter = $adapter;
        $this->hashService = $hashService;
        $this->hammingDistanceService = $hammingDistanceService;
    }

    /** ${@inheritDoc} */
    public function saveImage(int $urlID, string $imageURL, string $imageHash)
    {
        // Check if its an image
        $headers = get_headers($imageURL, 1);
        if (strpos($headers['Content-Type'], 'image/') === false) {
            return ImageRepositoryInterface::IMAGE_EXISTS;
        }

        $imageModel = $this->getImageModelByURL($imageURL);
        if ($imageModel !== null ) {
            return ImageRepositoryInterface::IMAGE_EXISTS;
        }

        /** @var AdapterInterface $adapter */
        $adapter = $this->adapter;

        $sql = "INSERT INTO Images (ID, URLID, ImageURL, ImageHash) VALUES(null, :urlID, :imageURL, :imageHash);";

        /** @var StatementInterface $statement */
        $statement = $adapter->createStatement($sql);

        /** @var ResultInterface $result */
        $statement->execute([
            ":urlID"     => $urlID,
            ":imageURL"  => $imageURL,
            ":imageHash" => $imageHash
        ]);

        return ImageRepositoryInterface::IMAGE_DOESNT_EXIST;
    }

    /** ${@inheritDoc} */
    public function getImageModelByURL(string $url)
    {
        $imageModel = null;

        /** @var AdapterInterface $adapter */
        $adapter = $this->adapter;

        $sql = "SELECT * FROM Images WHERE ImageURL = :url;";

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
                $imageModel = new ImageModel();
                $imageModel->setId($row["ID"]);
                $imageModel->setUrlID($row["URLID"]);
                $imageModel->setUrl($row["ImageURL"]);
                $imageModel->setHash($row["ImageHash"]);
                break;
            }
        }

        return $imageModel;
    }

    /** ${@inheritDoc} */
    public function getImageModel(string $imageHash)
    {
        $imageModel = null;

        /** @var AdapterInterface $adapter */
        $adapter = $this->adapter;

        $sql = "SELECT * FROM Images WHERE ImageHash = :imageHash;";

        /** @var StatementInterface $statement */
        $statement = $adapter->createStatement($sql);
        /** @var ResultInterface $result */
        $result = $statement->execute([
            ":imageHash" => $imageHash,
        ]);

        if ($result->isQueryResult()) {
            $resultSet = new ResultSet;
            $resultSet->initialize($result);

            foreach ($resultSet as $row) {
                $imageModel = new ImageModel();
                $imageModel->setId($row["ID"]);
                $imageModel->setUrlID($row["URLID"]);
                $imageModel->setUrl($row["ImageURL"]);
                $imageModel->setHash($row["ImageHash"]);
                break;
            }
        }

        return $imageModel;
    }

    /** ${@inheritDoc} */
    public function getImagesInDistance(string $imagePath, int $distance): array
    {
        $imageHash = $this->hashService->hashImageLocal($imagePath);

        $allImages = [];

        /** @var AdapterInterface $adapter */
        $adapter = $this->adapter;

        $sql = "SELECT *, BIT_COUNT(ImageHash ^ :otherImageHash) AS hamming FROM Images HAVING hamming <= :distance ORDER BY hamming ASC LIMIT 50;";

        /** @var StatementInterface $statement */
        $statement = $adapter->createStatement($sql);
        /** @var ResultInterface $result */
        $result = $statement->execute([
            ":otherImageHash" => $imageHash,
            ":distance"       => $distance,
        ]);

        if ($result->isQueryResult()) {
            $resultSet = new ResultSet;
            $resultSet->initialize($result);

            foreach ($resultSet as $row) {
                $imageModel = new ImageModel();
                $imageModel->setId($row["ID"]);
                $imageModel->setUrlID($row["URLID"]);
                $imageModel->setUrl($row["ImageURL"]);
                $imageModel->setHash($row["ImageHash"]);
                $allImages[] = $imageModel;
            }
        }

        return $allImages;
    }

    /** ${@inheritDoc} */
    public function getAllImages(): array
    {
        $allImages = [];

        /** @var AdapterInterface $adapter */
        $adapter = $this->adapter;

        $sql = "SELECT * FROM Images ORDER BY ID DESC LIMIT 100;";
        //$sql = "CREATE TABLE Images (ID INT NOT NULL AUTO_INCREMENT, URLID INT NOT NULL, ImageURL VARCHAR(500), ImageHash VARCHAR(500) NOT NULL, PRIMARY KEY(ID));";
        //$sql = "DROP TABLE Images;";

        /** @var StatementInterface $statement */
        $statement = $adapter->createStatement($sql);
        /** @var ResultInterface $result */
        $result = $statement->execute();

        if ($result->isQueryResult()) {
            $resultSet = new ResultSet;
            $resultSet->initialize($result);

            foreach ($resultSet as $row) {
                $imageModel = new ImageModel();
                $imageModel->setId($row["ID"]);
                $imageModel->setUrlID($row["URLID"]);
                $imageModel->setUrl($row["ImageURL"]);
                $imageModel->setHash($row["ImageHash"]);
                $allImages[] = $imageModel;
            }
        }

        return $allImages;
    }
}