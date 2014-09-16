<?php

namespace BrownPaperTickets\APIv2;

use PHPUnit_Framework_TestCase;

class BrownPaperTicketsClassTest extends \PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        $this->bpt = new BptAPI('notneeded');
    }

    public function testCheckDateFormat()
    {
        $badDate = '1-14-1986 7:24';

        $goodDate = 'JAN-14-1986 07:00';

        $expectFalse = $this->bpt->checkDateFormat($badDate);

        $expectTrue = $this->bpt->checkDateFormat($goodDate);

        $this->assertInternalType('boolean', $expectFalse);

    }
}
