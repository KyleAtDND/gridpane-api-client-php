<?php

namespace KyleWLawrence\GridPane\API\UnitTests\Exceptions;

use KyleWLawrence\GridPane\API\Exceptions\ApiResponseException;
use KyleWLawrence\GridPane\API\UnitTests\BasicTest;

class ApiResponseExceptionTest extends BasicTest
{
    /**
     * Tests if previous exception was passed to ApiResponseException
     */
    public function testPreviousException()
    {
        $message = 'The previous exception was not passed to ApiResponseException';
        $mockException = $this
            ->getMockBuilder('GuzzleHttp\Exception\RequestException')
            ->disableOriginalConstructor()
            ->getMock();
        $mockException->method('hasResponse')->willReturn(true);
        $apiException = new ApiResponseException($mockException);
        $previousException = $apiException->getPrevious();

        $this->assertEquals($mockException, $previousException, $message);
    }
}
