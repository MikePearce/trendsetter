<?php
namespace Application\DefaultBundle\Lib;

use Application\DefaultBundle\Lib\Googlevis;

/**
 *
 * @author Mike Pearce <mike@mikepearce.net>
 */
class Trac {
        
    /**
     * When instantiated, see if we need to refresh the stats
     */
    public function __construct($memcached) {
        $this->memcached = $memcached;
    }
    
    /**
     *  Get all the counts of tickets by priority.
     * @return array
     */
    public function getTicketByPriority() {
        $priorityTypes = array(
            'Year/Month'    => 'string', 
            'Critical'      => 'number',
            'Major'         => 'number',
            'Minor'         => 'number',
            'Blocker'       => 'number',
            'Trivial'       => 'number',
            'Waiting'       => 'number',
            'Nicetohave'    => 'number',
        );
        $googleVis = new Googlevis();
        $columns = $googleVis->createColumns($priorityTypes);
        

        $tracData = $this->getTracData();
        $defects = array();
        // Get rid of year/month
        array_shift($priorityTypes);
        foreach($tracData AS $date => $tickets) {
            
            // Setup the index
            $defects[$date] = array();
            foreach ($priorityTypes AS $label => $type) {
                $defects[$date][strtolower($label)] = 0;
            }
            
            // Pump in teh data
            foreach($tickets AS $ticket) {
                $defects[$date][strtolower($ticket['priority'])] += 1;
            }
        }
        
        // Now, create the google vis
        $rows = array();
        foreach($defects AS $date => $priority) {
            $rows[] = $googleVis->createDataRow($date, $priority);
        }
        
        return array('cols' => $columns, 'rows' => $rows);
    }
    
    /**
     * Get the current top level stats
     * @return array
     */
    public function getAllTickets() {
        $googleVis = new Googlevis();
        $columns = $googleVis->createColumns(
            array('Year/Month' => 'string', 'No. of Tickets' => 'number')
        );
        $rows = array();
        $tracData = $this->getTracData();

        foreach($tracData AS $date => $tickets) {
            $rows[] = $googleVis->createDataRow(
                  $date,
                  count($tickets)
                );
        }
        return array('cols' => $columns, 'rows' => $rows);
        
    }
    
    /**
     * Get last months and this months ticket score
     * @return array
     */
    public function getLastThisMonth() {
       $tracData = $this->getTracData();
      
       // Then get the stats
       $thisMonth = array_pop($tracData);
       $lastMonth = array_pop($tracData);
       return array('lastMonth' => count($lastMonth), 'thisMonth' => count($thisMonth));
    }
    
    private function getTracData() {
         // Get it from memcache and see if it's older than 24 hours
        $json = $this->memcached->get(md5('ticketdata'));
        $data = json_decode($json, true);
        if (!isset($data['date']) OR $data['date'] <= strtotime('-24 hours')) {
            $data = $this->getRawTracData();
            $data['date'] = time();
            $this->memcached->set(md5('ticketdata'), json_encode($data));
        }
        
        // Remove the date.
        array_pop($data);
        return $data;
    }
    
    
    /**
     * Get the csv data from Trac
     * @return array
     */
    private function getRawTracData() {
        
        $filename = "/tmp/trendsetter-trac-csv.csv";
        $handle = fopen($filename, 'w+');
        fwrite(
            $handle, 
            file_get_contents('https://mike.pearce:marmaset@dtrac.affiliatewindow.com/report/65?format=csv&USER=mike.pearce')
        );
        fclose($handle);
        $firstRow = FALSE;
        $new_data = array();
        $cols = array(
            'ticket', 
            'status', 
            'priority', 
            'owner', 
            'date_created', 
            'summary', 
            'type', 
            'changetimes'
        );
        
        if (($handle = fopen($filename, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (!$firstRow) {
                    $firstRow = TRUE;
                    continue;
                }
                for($i = 0; $i < count($cols); $i++ ) {
                    list($year, $month, $day) = explode('-', $data[4]);
                    $new_data[$year .'/'. $month][$data[0]][$cols[$i]] = $data[$i];
                }
            }
            fclose($handle);
        }
        
        ksort($new_data);
        
        return $new_data;
    }
}

