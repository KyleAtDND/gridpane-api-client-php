<?php

namespace KyleWLawrence\GridPane\API\LiveTests;

use KyleWLawrence\GridPane\API\HttpClient;

/**
 * Basic test class
 */
abstract class BasicTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $bearer;

    /**
     * @var array
     */
    protected $mockedTransactionsContainer = [];

    /**
     * {@inheritdoc}
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->bearer = getenv('BEARER');
        $this->authStrategy = getenv('AUTH_STRATEGY');

        parent::__construct($name, $data, $dataName);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->client = new HttpClient();

        if ($this->authStrategy === 'bearer') {
            $authOptions['bearer'] = $this->bearer;
        }

        $this->client->setAuth($this->authStrategy, $authOptions);
    }
}
