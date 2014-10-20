<?php

namespace Oneup\FacebookEvents;

use Contao\Database;
use Contao\File;
use Facebook\GraphObject;
use GuzzleHttp\Client;

class EventProcessor
{
    protected $guzzleClient;
    protected $database;

    // config options
    protected $calendarId;
    protected $imageSize;

    public function __construct($calendarId, $imageSize)
    {
        $this->guzzleClient = new Client();
        $this->database = Database::getInstance();

        // configuration
        $this->calendarId = $calendarId;
        $this->imageSize = $imageSize;
    }

    public function process(GraphObject $data, GraphObject $image)
    {
        $facebookId = $data->getProperty('id');

        // check if event exists
        if (null !== ($event = $this->checkIfEventExists($facebookId))) {
            $this->updateEvent($event, $data, $image);
            return;
        }

        $this->createEvent($data, $image);
        return;
     }

    protected function generateAlias($input)
    {
        $varValue = standardize(\String::restoreBasicEntities($input));

        $objAlias = $this->database->prepare("SELECT id FROM tl_calendar_events WHERE alias=?")
            ->execute($varValue)
        ;

        // Add ID to alias
        if ($objAlias->numRows)
        {
            $maxId = $this->database->prepare("SELECT MAX(id) FROM tl_calendar_events")
                ->execute()
            ;

            $varValue .= '-' . $maxId;
        }

        return $varValue;
    }

    protected function checkIfEventExists($facebookId)
    {
        $result = $this->database->prepare('SELECT * FROM tl_calendar_events WHERE facebook_id=? LIMIT 1')
            ->execute($facebookId)
        ;

        if ($result->numRows > 0) {
            return $result->first();
        }

        return null;
    }

    protected function createEvent(GraphObject $data, GraphObject $image)
    {
        $start = new \DateTime($data->getProperty('start_time'));

        $this->database->prepare('INSERT INTO tl_calendar_events (pid, tstamp, title, alias, author, startTime, endTime, startDate, published, facebook_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')
            ->execute(
                $this->calendarId,
                time(),
                $data->getProperty('name'),
                $this->generateAlias($data->getProperty('name')),
                1,
                $start->format('U'),
                time() + 86399,
                $start->format('U'),
                true,
                $data->getProperty('id')
            )
        ;

        // Get the Id of the inserted Event
        $insertedEvent = $this->database->prepare('SELECT id FROM tl_calendar_events WHERE facebook_id = ?')
            ->execute($data->getProperty('id'))
        ;

        $eventId = $insertedEvent->next()->id;

        // Insert ContentElement
        $this->database->prepare('INSERT INTO tl_content (pid, ptable, sorting, tstamp, type, headline, text, floating, sortOrder, perRow, cssID, space, com_order, com_template) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')
            ->execute(
                $eventId,
                'tl_calendar_events',
                128,
                time(),
                'text',
                serialize(array('unit' => 'h1', 'value' => $data->getProperty('name'))),
                sprintf('<p>%s</p>', $data->getProperty('description')),
                'above',
                'ascending',
                4,
                serialize(array('', '')),
                serialize(array('', '')),
                'ascending',
                'com_default'
            )
        ;

        // store image
        $file = $this->writePicture($data->getProperty('id'), $image);

        $this->database->prepare('INSERT INTO tl_content (pid, ptable, sorting, tstamp, type, headline, singleSRC, size, imagemargin, floating, sortOrder, perRow, cssID, space, com_order, com_template) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')
            ->execute(
                $eventId,
                'tl_calendar_events',
                64,
                time(),
                'image',
                serialize(array('unit' => 'h1', 'value' => '')),
                $file->uuid,
                $this->imageSize,
                serialize(array('bottom' => '', 'left' => '', 'right' => '', 'top' => '', 'unit' => '')),
                'above',
                'ascending',
                4,
                serialize(array('', '')),
                serialize(array('', '')),
                'ascending',
                'com_default'
            )
        ;
    }

    protected function updateEvent($eventObj, GraphObject $data, GraphObject $image)
    {
        $this->database->prepare('UPDATE tl_calendar_events SET title = ? WHERE facebook_id = ?')
            ->execute(
                $data->getProperty('name'),
                $data->getProperty('id')
            )
        ;

        // Update Text ContentElement
        $this->database->prepare('UPDATE tl_content SET headline = ?, text = ? WHERE type = ? AND pid = ? AND ptable = ?')
            ->execute(
                serialize(array('unit' => 'h1', 'value' => $data->getProperty('name'))),
                sprintf('<p>%s</p>', $data->getProperty('description')),
                'text',
                $eventObj->id,
                'tl_calendar_events'
            )
        ;

        $file = $this->writePicture($data->getProperty('id'), $image);

        // Is this really necessary? Or is updating the image already enough?
        // Update Image ContentElement
        $this->database->prepare('UPDATE tl_content SET singleSRC = ? WHERE type = ? AND pid = ? AND ptable = ?')
            ->execute(
                $file->uuid,
                'image',
                $eventObj->id,
                'tl_calendar_events'
            )
        ;
    }

    /**
     * Write a picture from a /event/picture request and
     * return the file model including the uuid.
     *
     * @param $id
     * @param GraphObject $image
     * @return \FilesModel
     */
    protected function writePicture($id, GraphObject $image)
    {
        // fetch file
        $content = $this->guzzleClient->get($image->getProperty('url'))->getBody();

        $file = new File(sprintf('files/facebook-events/%s.jpg', $id));
        $file->write($content);
        $file->close();

        return $file->getModel();
    }
}