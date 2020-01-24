<?php


namespace Application\Repository;


use Application\Model\ImageModel;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Adapter\Driver\StatementInterface;
use Zend\Db\ResultSet\ResultSet;

class ImageRepository implements ImageRepositoryInterface
{

    /** @var AdapterInterface */
    private $adapter;

    /**
     * ImageRepository constructor.
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /** ${@inheritDoc} */
    public function saveImage(int $urlID, string $imageHash)
    {
        /** @var AdapterInterface $adapter */
        $adapter = $this->adapter;

        $sql = "INSERT INTO Images (ID, URLID, ImageHash) VALUES(null, :urlID, :imageHash);";

        /** @var StatementInterface $statement */
        $statement = $adapter->createStatement($sql);

        /** @var ResultInterface $result */
        $statement->execute([
            ":urlID"     => $urlID,
            ":imageHash" => $imageHash
        ]);
    }

    /** ${@inheritDoc} */
    public function getImageModel(string $imageHash)
    {
        $imageModel = null;

        /** @var AdapterInterface $adapter */
        $adapter = $this->adapter;

        $sql = "SELECT * FROM Images WHERE ImageHash = ':imageHash';";

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
                $imageModel->setHash($row["ImageHash"]);
                break;
            }
        }

        return $imageModel;
    }

    /** ${@inheritDoc} */
    public function getAllImages(): array
    {
        $allImages = [];

        /** @var AdapterInterface $adapter */
        $adapter = $this->adapter;

        $sql = "SELECT * FROM Images ORDER BY ID DESC LIMIT 100;";
        //$sql = "CREATE TABLE Images (ID INT NOT NULL AUTO_INCREMENT, URLID INT NOT NULL, ImageHash VARCHAR(32) NOT NULL, PRIMARY KEY(ID));";
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
                $imageModel->setHash($row["ImageHash"]);
                $allImages[] = $imageModel;
            }
        }

        return $allImages;
    }
}