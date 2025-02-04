<?php 
namespace App\Classes;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;


class ManageSession
{
    private $session;
    private $em;
    public function __construct(EntityManagerInterface $em, RequestStack $stack)
    {
        $this->em = $em; 
        $this->session = $stack->getSession();
    }

    //ajouter un user a l'equipe 
    public function addUser($id) {
        //creeer un tableu vide si la session vient d'etre cree
        $team = $this->session->get('team', []);
        if(empty($team[$id]))
            $team[$id] = 1; 

        $this->session->set('team', $team);
    }
    
    //recuperer user
    public function getUser(){
        return $this->session->get('team');
    }

    //vider la session 
    public function remove(){
        return $this->session->remove('team'); 
    }

    //retirer un user a partir de son id
    public function delete($id){
        $team = $this->session->get('team',[]); 
        unset($team[$id]); 
        return $this->session->set('team', $team);
    }

}           