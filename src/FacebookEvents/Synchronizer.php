<?php

namespace Oneup\Contao\FacebookEvents;

use Contao\System;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphObject;

class Synchronizer
{
    protected $config;
    protected $session;
    protected $processor;

    public function __construct(array $config)
    {
        $this->config = $config;

        // create a new session
        $session = new FacebookSession(sprintf('%s|%s', $this->config['id'], $this->config['secret']));
        $session::setDefaultApplication($this->config['id'], $this->config['secret']);

        $this->session = $session;

        $this->processor = new EventProcessor($config);
    }

    public function run()
    {
        $graphObject    = $this->call('events');

        if (count($graphObject->getProperty('data')) < 1) {
            System::log('A Facebook event update was performed. Number of synchronized events: 0', 'FacebookEvents\Synchronizer::run', 'CRON');

            return;
        }

        $data           = $graphObject->getProperty('data')->asArray();
        $paging         = $graphObject->getProperty('paging');

        /** @var GraphObject $cursors */
        $cursors        = $paging->getProperty('cursors');

        $cursorAfter    = $cursors->getProperty('after');
        $cursorBefore   = $cursors->getProperty('before');

        while ($cursorBefore) {
            $graphObject    = $this->call('events', $cursorBefore);
            $cursorBefore   = null;
            $dataNew        = $graphObject->getProperty('data');

            if (null === $dataNew) {
                break;
            }
            if ($this->isOlderThanConfigured($dataNew->getProperty(0))) {
                break;
            }

            /** @var GraphObject $paging */
            $paging         = $graphObject->getProperty('paging');
            $cursors        = $paging->getProperty('cursors');

            $cursorBefore   = $cursors->getProperty('before');
            $cursorBefore   = count($cursorBefore) ? $cursorBefore : null;
            $data           = array_merge($data, $dataNew->asArray());
        }

        while ($cursorAfter) {
            $graphObject    = $this->call('events', null, $cursorAfter);
            $cursorAfter    = null;
            $dataNew        = $graphObject->getProperty('data');

            if (null === $dataNew) {
                break;
            }
            if ($this->isOlderThanConfigured($dataNew->getProperty(0))) {
                break;
            }

            /** @var GraphObject $paging */
            $paging         = $graphObject->getProperty('paging');
            $cursors        = $paging->getProperty('cursors');

            $cursorAfter    = $cursors->getProperty('after');
            $cursorAfter    = count($cursorAfter) ? $cursorAfter : null;
            $data           = array_merge($data, $dataNew->asArray());
        }

        foreach ($data as $event) {
            $detail = $this->call($event->id, null, null, false);
            $image = $this->call(sprintf('%s?fields=cover', $event->id), null, null, false, array('redirect' => false, 'width' => 1920, 'height' => 1280));

            // process event
            $this->processor->process($detail, $image);
        }

        System::log('A Facebook event update was performed. Number of synchronized events: '.count($data), 'FacebookEvents\Synchronizer::run', 'CRON');
    }

    protected function call($namespace, $before = null, $after = null, $includePage = true, array $parameters = array())
    {
        $address = sprintf('/%s', $namespace);

        if ($includePage) {
            $address = sprintf('/%s/%s?', $this->config['page'], $namespace);
        }

        if (null !== $after) {
            $address = sprintf($address.'after=%s&', $after);
        }

        if (null !== $before) {
            $address = sprintf($address.'before=%s&', $before);
        }

        if ($this->config['token']) {
            $parameters['access_token'] = $this->config['token'];
        }


        // create the request object
        $request = new FacebookRequest($this->session, 'GET', $address, $parameters, $this->config['apiVersion']);
        $graphObject = $request->execute()->getGraphObject();

        return $graphObject;
    }

    /**
     * Check whether the provided data object is older than
     * the configured update period.
     *
     * @param  GraphObject $data
     * @return bool
     */
    protected function isOlderThanConfigured(GraphObject $data)
    {
        if ($this->config['updateTime'] < 0) {
            return false;
        }

        $start = new \DateTime($data->getProperty('start_time'));
        $until = new \DateTime(sprintf('-%d days', $this->config['updateTime']));

        return $start < $until;
    }
}
