<?php

namespace Application\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Application\DefaultBundle\Lib\Observer;
use Application\DefaultBundle\Lib\FullScreen;

use Zend\XmlRpc\Client;
use Zend\Http\Client AS HTTPClient;
class DefaultController extends Controller
{    
    public function indexAction()
    {     
        $filename = "/tmp/trendsetter-trac-csv.csv";
        $handle = fopen($filename, 'w+');
        fwrite(
            $handle, 
            file_get_contents('https://mike.pearce:marmaset@dtrac.affiliatewindow.com/report/65?format=csv&USER=mike.pearce')
        );
        fclose($handle);
        $firstRow = FALSE;
        $new_data = array();
        $cols = array('ticket', 'status', 'priority', 'owner', 'date_created', 'summary', 'type', 'changetimes');
        if (($handle = fopen($filename, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (!$firstRow) {
                    $firstRow = TRUE;
                    continue;
                }
                for($i = 0; $i < count($cols); $i++ ) {
                    $new_data[$data[0]][$cols[$i]] = $data[$i];
                }
            }
            fclose($handle);
        }
        var_dump($new_data);
//        foreach($csv AS $row) {
//            if (!$firstrow){
//                $firstrow = $row;
//            }
//            else {
//                foreach($row AS $id => $column) {
//                    
//                }
//            }
//        }
//        var_dump($result);
       
        
        return $this->render(
        'ApplicationDefaultBundle:Default:index.html.twig'
        );
    }

    public function fullscreenAction()
    {       
        $fullscreen = new FullScreen($this->get('memcached'));
        $fullscreen->getAwinBlog();
        
        return $this->render(
            'ApplicationDefaultBundle:Default:fullscreen.html.twig'
        );
    }

    /**
     * Generic data endpoint
     * @return response object
     **/
    public function dataAction($type, $backlog = false, $storyid = 0) {
        $easybacklogClient = $this->get('mikepearce_easybacklog_api');
        $easybacklogClient->setAccountId('477')
                          ->setBacklog($backlog);
        
        // Get the data
        $observer = new Observer(
            $easybacklogClient, 
            $this->get('memcached'), 
            $this->container->getParameter('teams')
        );

        $response = new JsonResponse();
        $response->setData($observer->getData($type, $storyid));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}
