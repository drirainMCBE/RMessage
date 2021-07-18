<?php

namespace RoMo\RMessage;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\DisconnectPacket;
use pocketmine\network\mcpe\protocol\OnScreenTextureAnimationPacket;
use pocketmine\plugin\PluginBase;

class RMessage extends PluginBase implements Listener{
    public function onEnable(): void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    public function onJoin(PlayerJoinEvent $event){
        $event->setJoinMessage("");
        $player = $event->getPlayer();
        $name = $player->getName();
        $this->getServer()->broadcastTip(" §b". $name ."§f님이 접속 하셨습니다! ( §a". count($this->getServer()->getOnlinePlayers()) ."명§f ) ");
        $player->sendTitle("§lWELCOME");
        $player->sendSubTitle("§b이슬비 프로젝트§f에 오신것을 환영합니다!");
        $packet = new OnScreenTextureAnimationPacket();
        $packet->effectId = 10;
        $player->getNetworkSession()->sendDataPacket($packet);
        if(!$player->hasPlayedBefore()){
            $this->getServer()->broadcastMessage("\n§b". $name ."§f님이 첫 접속 하셨습니다!\n");
        }
    }
    /*public function justwhitelist(PlayerPreLoginEvent $event){
        $player = $event->getPlayerInfo();
        if($this->getServer()->isWhitelisted($player->getUsername())){
            $player->close(" ", "서버가 §c점검중§f입니다\n나중에 다시 §b시도§f해주세요");
        }
    }*/
    public function onQuit(PlayerQuitEvent $event){
        $event->setQuitMessage("");
        $name = $event->getPlayer()->getName();
        $this->getServer()->broadcastTip("§b". $name ."§f님이 퇴장 하셨습니다! ( §a". (count($this->getServer()->getOnlinePlayers()) -1) ."명§f ) ");
    }
    public function onPacket(DataPacketSendEvent $event){
        $pack = $event->getPackets();
        if($pack instanceof DisconnectPacket){
            if($pack->message == "Internal server error"){
                $pack->message = "§c오류§f로 연결이 끊겼습니다\n§a밴드§f로 해당 §c오류§f를 공유 해주세요!";
                $name = $event->getTargets()[0];
                $this->getServer()->broadcastMessage( "\n§b( §fDROP §b)§r §b". $name ."§f님이 오류로 연결이 끊겼습니다.\n");
                return;
            }
        }
    }
}
