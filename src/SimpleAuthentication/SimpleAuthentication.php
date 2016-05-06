<?php

namespace SimpleAuthentication;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

use pocketmine\entity\Effect;
use pocketmine\item\Item;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerMoveEvent;

use pocketmine\plugin\PluginManager;

use pocketmine\math\Vector3;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\utils\Config;
use pocketmine\level\sound\AnvilUseSound;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\utils\Random;
use pocketmine\event\entity\ExplosionPrimeEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\protocol\UseItemPacket;

use pocketmine\command\ConsoleCommandSender;

use pocketmine\level\sound\ExplodeSound;
use pocketmine\level\sound\AnvilBreakSound;
use pocketmine\level\sound\Sound;

use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\utils\TextFormat as color;

class SimpleAuthentication extends PluginBase implements Listener{
    
    
    /* @param CancelEvent */
    private $cancelEvent = false;
    
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        
        $this->config = new Config($this->getDataFolder() . "config.yml" , Config::YAML, Array(
            "set.lang" => "pt_br",
            "passwords.folder" => "accounts",
            "player.not.move" => true,
            ));
        
    }
    
    /*public function onMove(PlayerMoveEvent $event){
        $cancelEvent = $this->config->get("player.not.move");
        $event->setCancelled($cancelEvent);
    }*/
    
    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
        switch($command->getName()){
            case "simplelogin":
                if(isset($args[0])){
                    switch(strtolower($args[0])){
                        case "register":
                            if(isset($args[1])){
                                $filesfolder = $this->config->get("passwords.folder");
                                $player = $sender->getPlayer()->getName();
                                    @mkdir($this->getDataFolder() . $filesfolder."/");
               $this->register = new Config($this->getDataFolder() . $filesfolder."/".  $player.".yml" , Config::YAML, Array(
                   "password" => $args[1],
                   "registred" => true,
            ));  
               $sender->sendMessage("§bRegistrado com Sucesso! §bSua §bsenha §bserá: §e".$args[1]);
             }
             return false;
                        case "login":
                            $login = $this->register->get("password");
                                if(isset($args[1])){
                                     switch(strtolower($args[1])){
                                        case "$login":
                                            $sender->sendMessage("§bVocê logou com Sucesso!");
                         return false;
                 }
             } 
             if(isset($args[1])){
                 switch(strtolower($args[1])){
                     case "$args[1]":
                     $sender->getPlayer()->kick("§cSENHA INCORRETA!");
                     return false;
                    }
                    
                    
                }
                return false;
                     case "unregister":
                         $player = $sender->getPlayer()->getName();
                                    @mkdir($this->getDataFolder() . $filesfolder."/");
               $this->register = new Config($this->getDataFolder() . $filesfolder."/".  $player.".yml" , Config::YAML, Array(
                   "password" => "semsenha",
                   "registred" => false,
            ));
               $sender->sendMessage("§cSenha Retirada!");
               return false;
               case "changepassword":
                   if(isset($args[1])){
                       $player = $sender->getPlayer()->getName();
                                    @mkdir($this->getDataFolder() . $filesfolder."/");
               $this->register = new Config($this->getDataFolder() . $filesfolder."/".  $player.".yml" , Config::YAML, Array(
                   "password" => $args[1],
                   "registred" => true,
            ));
            $player->sendMessage("§bSenha Trocada!");
                   }
                   
        }
    }
    
}
    }
    
    public function PlayerJoinEvent(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $player->sendMessage("§cSe Sua conta não está registrada,§c use /simplelogin §cregister <password>");
        $player->sendMessage("§cSe Sua conta está registrada,§c use /simplelogin §clogin <password>");
        
    }
    
}
