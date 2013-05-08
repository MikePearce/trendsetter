<?php
namespace Application\DefaultBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
 
class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav')
             ->setChildrenAttribute('role', 'navigation');

        // Estimates
        $menu->addChild('Estimates')->setAttribute('dropdown', true);     
        $menu['Estimates']->setChildrenAttribute('role', 'menu');
        $menu['Estimates']->addChild('Overview', array('uri' => '/estimates'));
        
        // Submenu?
        if (isset($options['submenu'])) {
            $menu['Estimates']->setChildrenAttribute('class', 'submenu');
        } else {
            $menu['Estimates']['Overview']->setAttribute('divider_append', true);    
        }
        $menu['Estimates']->addChild('Gaia', array('uri' => '/estimates/team/gaia'));
        $menu['Estimates']->addChild('A-Team', array('uri' => '/estimates/team/ateam'));
        $menu['Estimates']->addChild('Prime', array('uri' => '/estimates/team/prime'));
        $menu['Estimates']->addChild('Raptor', array('uri' => '/estimates/team/raptor'));
 
        // Stories
        $menu->addChild('Stories')
             ->setAttribute('dropdown', true);
        $menu['Stories']->addChild('Overview', array('uri' => '/stories'));

        // Submenu
        if (isset($options['submenu'])) {
            $menu['Stories']->setChildrenAttribute('class', 'submenu');
        } else {
            $menu['Stories']['Overview']->setAttribute('divider_append', true);                 
        }
        
        $menu['Stories']->addChild('Gaia', array('uri' => '/stories/team/gaia'));
        $menu['Stories']->addChild('A-Team', array('uri' => '/stories/team/ateam'));
        $menu['Stories']->addChild('Prime', array('uri' => '/stories/team/prime'));
        $menu['Stories']->addChild('Raptor', array('uri' => '/stories/team/raptor'));

        // Acceptance
        $menu->addChild('Acceptance')
             ->setAttribute('dropdown', true);
        $menu['Acceptance']->addChild('Overview', array('uri' => '/stories/acceptancerate'));

        // Submenu?
        if (isset($options['submenu'])) {
            $menu['Acceptance']->setChildrenAttribute('class', 'submenu');
        } else {
            $menu['Acceptance']['Overview']->setAttribute('divider_append', true);
        }
        $menu['Acceptance']->addChild('Gaia', array('uri' => '/stories/acceptancerateteam/gaia'));
        $menu['Acceptance']->addChild('A-Team', array('uri' => '/stories/acceptancerateteam/ateam'));
        $menu['Acceptance']->addChild('Prime', array('uri' => '/stories/acceptancerateteam/prime'));
        $menu['Acceptance']->addChild('Raptor', array('uri' => '/stories/acceptancerateteam/raptor'));        

        // Velocity
        $menu->addChild('Velocity')
             ->setAttribute('dropdown', true);
        $menu['Velocity']->addChild('Overview', array('uri' => '/velocity'));
        // Submenu?
        if (isset($options['submenu'])) {
            $menu['Velocity']->setChildrenAttribute('class', 'submenu');
        } else {
            $menu['Velocity']['Overview']->setAttribute('divider_append', true);             
        }                        
        $menu['Velocity']->addChild('Gaia', array('uri' => '/velocity/team/gaia'));
        $menu['Velocity']->addChild('A-Team', array('uri' => '/velocity/team/ateam'));
        $menu['Velocity']->addChild('Prime', array('uri' => '/velocity/team/prime'));
        $menu['Velocity']->addChild('Raptor', array('uri' => '/velocity/team/raptor'));                
        
        return $menu;
    }
}