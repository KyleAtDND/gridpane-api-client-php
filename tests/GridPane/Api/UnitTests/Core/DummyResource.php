<?php

namespace KyleWLawrence\GridPane\API\UnitTests\Core;

use KyleWLawrence\GridPane\API\Resources\ResourceAbstract;
use KyleWLawrence\GridPane\API\Traits\Resource\CreateMany;
use KyleWLawrence\GridPane\API\Traits\Resource\CreateOrUpdateMany;
use KyleWLawrence\GridPane\API\Traits\Resource\Defaults;
use KyleWLawrence\GridPane\API\Traits\Resource\DeleteMany;
use KyleWLawrence\GridPane\API\Traits\Resource\FindMany;
use KyleWLawrence\GridPane\API\Traits\Resource\MultipartUpload;
use KyleWLawrence\GridPane\API\Traits\Resource\UpdateMany;

/**
 * Class DummyResource
 */
class DummyResource extends ResourceAbstract
{
    use Defaults;
    use MultipartUpload;
    use FindMany;
    use CreateMany;
    use UpdateMany;
    use DeleteMany;
    use CreateOrUpdateMany;

    /**
     * {@inheritdoc}
     */
    protected $objectName = 'dummy';

    /**
     * {@inheritdoc}
     */
    protected $objectNamePlural = 'dummies';

    /**
     * The using resource should define the upload name to use when uploading the file.
     *
     * @return string
     */
    public function getUploadName()
    {
        return 'upload';
    }

    /**
     * The using resource should define the upload name to use when uploading the file.
     *
     * @return string
     */
    public function getUploadRequestMethod()
    {
        return 'POST';
    }
}
