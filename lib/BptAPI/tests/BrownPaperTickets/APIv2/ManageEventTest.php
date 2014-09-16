<?php

namespace BrownPaperTickets\APIv2;

use PHPUnit_Framework_TestCase;

class BrownPaperTicketsCreateEventTest extends \PHPUnit_Framework_TestCase
{

    public function __construct()
    {
        $this->bptApi = new ManageEvent('p9ny29gi5h');
    }

    // public function testCreateEvent()
    // {
    //     $bpt = $this->bptApi;

    //     $eventParams = array(
    //         'name' => 'Chandler PHP API Wrapper Test - Please Delete',
    //         'city' => 'Seattle',
    //         'state' => 'WA',
    //         'shortDescription' => 'This is a short description.',
    //         'fullDescription' => 'This is a Full Description. So long.'
    //     );

    //     $createEvent = $bpt->createEvent('chandler_api', $eventParams);

    //     $this->assertArrayHasKey('id', $createEvent);
    // }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage No username was passed.
     */
    public function testChangeEventInvalidArgumentUsername()
    {
        $bpt = $this->bptApi;

        $fail = $bpt->changeEvent(null, null);
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage No event parameters passed.
     */
    public function testChangeEventInvalidArgumentEventParams()
    {
        $bpt = $this->bptApi;

        $fail = $bpt->changeEvent('chandler_api', null);
    }


    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage No Event ID was passed.
     */
    public function testChangeEventInvalidArgumentEventID()
    {
        $bpt = $this->bptApi;

        $eventParams = array(
            'name' => 'Test Event'
        );

        $fail = $bpt->changeEvent('chandler_api', $eventParams);
    }

    public function testChangeEvent()
    {
        $bpt = $this->bptApi;

        $originalEventParams = array(
            'id' => 445143,
            'name' => 'Another Test Event!',
            'city' => 'Seattle',
            'state' => 'WA',
            'shortDescription' => 'Unicorn Origami',
            'fullDescription' => 'I\'ve... seen things you people wouldn\'t believe... [laughs] Attack ships on fire off the shoulder of Orion. I watched c-beams glitter in the dark near the Tannhäuser Gate. All those... moments... will be lost in time, like [coughs] tears... in... rain. Time... to die...
<img width="100%" src="http://upload.wikimedia.org/wikipedia/en/1/1f/Tears_In_Rain.png" />',
            'address1' => 'Brown Paper Tickets',
            'address2' => '220 Nickerson St',
            'zip' => 98102,
            'phone' => '1.800.838.3006',
            'web' => 'http://www.brownpapertickets.com',
            'endOfEventMessage' => 'Some message at the end of event',
            'endOfSaleMessage' => 'Some end of sale message.',
            'dateNotes' => 'Date Notes',
            'notes' => 'Notes for the event',
            'keywords' => 'Test, API',
            'contactName' => 'Chandler',
            'contactEmail' => 'chandler@brownpapertickets.com',
            'contactPhone' => '1.800.838.3006',
            'contactFax' => '',
            'contactAddress1' => '220 Nickerson Street',
            'contactAddress2' => '',
            'contactCity' => 'Seattle',
            'contactState' => 'WA',
            'contactZip' => 98107,
            'contactCountry' => 'United States'
        );

        $newEventParams = array(
            'id' => 445143,
            'name' => 'API Test Event',
            'shortDescription' => 'A New Event Description!',
            'fullDescription' => 'Changing the event description!',
            'address1' => 'TREEHOUSE',
            'address2' => 'Across from 711',
            'zip' => 98107,
            'phone' => '1-800-838-3006',
            'web' => 'http://www.brownpapertickets.com/event/153529',
            'endOfEventMessage' => 'Some NEW message at the end of event',
            'endOfSaleMessage' => 'Some NEW end of sale message.',
            'dateNotes' => 'NEW Date Notes',
            'notes' => 'NEW Notes for the event',
            'keywords' => 'NEW, Test, API',
            'contactName' => 'New Chandler',
            'contactEmail' => 'chandler@brownpapertickets.com',
            'contactPhone' => '1-800-838-3006',
            'contactFax' => 'Fax No',
            'contactAddress1' => 'TREEHOUSE',
            'contactAddress2' => '',
            'contactCity' => 'Boston',
            'contactState' => 'MA',
            'contactZip' =>  01950,
            'contactCountry' => 'US',
            //'activated' => 'f'
        );

        $changeEvent = $bpt->changeEvent('chandler_api', $newEventParams);

        $this->assertArrayHasKey('result', $changeEvent);
        $this->assertContains('success', $changeEvent['result']);

        $changeEvent = $bpt->changeEvent('chandler_api', $originalEventParams);
        $this->assertArrayHasKey('result', $changeEvent);
        $this->assertContains('success', $changeEvent['result']);

    }
}
