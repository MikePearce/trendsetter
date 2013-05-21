<?php
namespace Application\DefaultBundle\Lib;

use Application\DefaultBundle\Lib\Trac;

/**
 *
 * @author Mike Pearce <mike@mikepearce.net>
 */
class FullScreen {
    
    public $memcached;
    
    public function __construct($memcached) {
        $this->memcached = $memcached;
    }
    
    public function getData() {
        return array(
            'blog'      => $this->getAwinBlog(),
            'flickr'    => $this->getFlickr(),
            'tickets'   => $this->getTickets()
        );
    }
    
    private function getTickets() {
        $trac = new Trac($this->memcached);
        return $trac->getLastThisMonth();
    }
    
    public function getAwinBlog() {
        
        // Get it from memcache and see if it's older than 24 hours
        $json = $this->memcached->get(md5('blogpost'));
        $data = json_decode($json, true);
        if (isset($data['date']) AND $data['date'] <= strtotime('-24 hours')) {
            $xml = new \SimpleXMLElement('https://awindev.wordpress.com/feed/', null, true);
        
            $data = array();
            foreach($xml->channel->item as $item) {
                $data[] = array(
                    'title'         => (string) $item->title,
                    'link'          => (string) $item->link,
                    'pubDate'       => date('d-m-Y', strtotime($item->pubDate)),
                    'description'   => (string) $item->description,
                );
            }
            $data['date'] = time();
            $this->memcached->set(md5('blogpost'), json_encode($data));
        }
        
        return $data[0];
    }
    
    public function getFlickr() {
        // Get it from memcache and see if it's older than 24 hours
        $json = $this->memcached->get(md5('flickr'));
        $data = json_decode($json, true);
        
        if (isset($data['date']) AND $data['date'] <= strtotime('-1 second')) {
            
            $xml = new \SimpleXMLElement('http://api.flickr.com/services/feeds/photos_public.gne?id=72802667@N00&lang=en-us&format=rss_200', null, true);
            
            $data = array();
            foreach($xml->channel->item as $image) {
                $namespaces = $image->getNameSpaces(true);
                $media = $image->children($namespaces['media']);
                $attr = $media->content->attributes();
                $data[] = array(
                    'title'         => (string) $image->title,
                    'link'          => (string) $image->link,
                    'description'   => (string) $image->description,
                    'thumbnail'     => (string) $attr['url']
                );
            }
            $data['date'] = time();
            $this->memcached->set(md5('flickr'), json_encode($data));
        }
        
        return $data[0]['thumbnail'];
    
    }
}