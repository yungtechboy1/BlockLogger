<?php


namespace BlockLogger;


use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\Player;
use pocketmine\IPlayer;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;


class Main extends PluginBase  implements Listener {
	
	
	public function onEnable() {
		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
		$this->reloadConfig();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info( TextFormat::GREEN . "BlockLogger - Enabled!" );
	}
	
	
	public function onBreak(BlockBreakEvent $ev) {
            date_default_timezone_set('America/Los_Angeles');
            $provider = $this->getConfig()->get("Provider");
            $player = $ev->getPlayer();
            $name = $player->getName();
            $auth = $this->getConfig()->getNested("Users." . $name);
            $block = $ev->getBlock();
            $date = date("[m/d g:ia]");
            $pos = new Vector3($block->getX(),$block->getY(),$block->getZ());
            if($this->getConfig()->get("Enabled") && $this->getConfig()->get("Provider") !== null) {
		if($provider == "CONFIG" && !file_exists($this->getDataFolder() . "Players/" . $name . ".yml")) {
                    $this->conf = new Config($this->getDataFolder() . "Players/" . $name . ".yml", CONFIG::YAML);
                    $this->conf->set("Breaks", [$pos->getX() . "," . $pos->getY() . "," . $pos->getZ() . ", Time->" . $date,]);
                    $this->conf->save();
                    return true;
                }
            }
        }
}
