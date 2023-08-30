<?php

namespace ImmoDev\keyShop;

use pocketmine\Server;
use pocketmine\player\Player;

use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;

use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;

use ImmoDev\KeyShop\Form\SimpleForm;

use onebone\economyapi\EconomyAPI;
use onebone\coinapi\CoinAPI;

class Main extends PluginBase
{
    public $config;
    public int $i;
    public string $prefix;
    
    public function onEnable(): void 
    {
        $this->getLogger()->info(TF::GREEN . "Plugin Has Been Enable");
        $this->prefix = $this->config->get("Prefix");
    }
    
    public function onDisable(): void {
        $this->getLogger()->info(TF::RED . "Plugin Has Been Disable");
    }

    /** @param CommandSender $sender
	 * @param Command $cmd
	 * @param string $label
	 * @param array $args
	 */
    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
    {   
        if($sender instanceof ConsoleCommandSender){
            $sender->sendMessage(self::prefix.TF::RED."please use command in game!");
            return false;
        }
        if ($cmd->getName() == "keyshop") {
            $this->KeyShopMenu($sender);
        }
        return true;
    }

    public function KeyShopMenu($sender){ 
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, array());
        $form = new SimpleForm(function (Player $sender, $data = null){
            $result = $data;
            if($data === null){
                return true;
            }             
            switch($result){
                case 0:
                    $this->KeyShopMoney($sender);
                    break;   
                case 1:
                    $this->KeyShopCoin($sender);
                    break;   
                case 2:
                    $this->KeyShopExp($sender);
                    break;
                case 3:
                    break;
            }
        });
        $form->setTitle($this->config->get("Title"));
        $form->setContent($this->config->get("Content"));
        $form->addButton($this->config->get("money-btn"),0,"textures/ui/napxu");
        $form->addButton($this->config->get("coin-btn"),0,"textures/ui/lixi");
        $form->addButton($this->config->get("exp-btn"), 0, "textures/items/experience_bottle");
        $form->addButton($this->config->get("closed-btn"),0,"textures/ui/cancel");
        $form->sendToPlayer($sender);
        return true;
    }

    public function KeyShopMoney($player)
    {
        $this->saveResource("money.yml");
        $this->config = new Config($this->getDataFolder() . "money.yml", Config::YAML, array());
        $form = new SimpleForm(function (Player $player, $data) {
            if ($data === null || $data === 0) {
                $this->KeyShopMenu($player);
                return true;
            }
            
            $money = EconomyAPI::getInstance()->myMoney($player);
            if ($money >= $this->config->get($data)["Key"]["Price"]) {
                EconomyAPI::getInstance()->reduceMoney($player, $this->config->get($data)["Key"]["Price"]);
                $this->getServer()->getCommandMap()->dispatch(new ConsoleCommandsender($this->getServer(), $this->getServer()->getLanguage()), "key " . $this->config->get($data)["Key"]["Name"] . " 1 \"" . $player->getName() . "\"");
                $player->sendMessage(self::prefix.$this->config->get($data)["Message"]["Succes"]);
            } else {
                $player->sendMessage(self::prefix.$this->config->get($data)["Message"]["Failed"]);
            }
        });
        $mymoney = EconomyAPI::getInstance()->myMoney($player);
        $formatted = $this->converter($mymoney);
        $form->setTitle($this->config->get("Title"));
        $form->setContent("§g>> §eHi, §b" . $player->getName() . "\n§g>> §eYour Balance §a" . $formatted);
        $form->addButton($this->config->get("bck-btn"), 0, "textures/ui/icon_import");
        for ($i = 1; $i <= 100; $i++) {
            if ($this->config->exists("$i")) {
                $form->addButton($this->config->get("$i")["Button"]["Name"] . "\n§rPrice : " . $this->config->get("$i")["Key"]["Price"], 0, "textures/items/paper");
            }
        }
        $player->sendForm($form);
    }

    public function KeyShopCoin($player)
    {
        $this->saveResource("coin.yml");
        $this->config = new Config($this->getDataFolder() . "coin.yml", Config::YAML, array());
        $form = new SimpleForm(function (Player $player, $data) {
            if ($data === null || $data === 0) {
                $this->KeyShopMenu($player);
                return true;
            }
            
            $coin = CoinAPI::getInstance()->myCoin($player);
            if ($coin >= $this->config->get($data)["Key"]["Price"]) {
                CoinAPI::getInstance()->reduceCoin($player, $this->config->get($data)["Key"]["Price"]);
                $this->getServer()->getCommandMap()->dispatch(new ConsoleCommandsender($this->getServer(), $this->getServer()->getLanguage()), "key " . $this->config->get($data)["Key"]["Name"] . " 1 \"" . $player->getName() . "\"");
                $player->sendMessage(self::prefix.$this->config->get($data)["Message"]["Succes"]);
            } else {
                $player->sendMessage(self::prefix.$this->config->get($data)["Message"]["Failed"]);
            }
        });
        $mycoin = CoinAPI::getInstance()->myCoin($player);
        $formatted = $this->converter($mycoin);
        $form->setTitle($this->config->get("Title"));
        $form->setContent("§g>> §eHi, §b" . $player->getName() . "\n§g>> §eYour Balance §a" . $formatted);
        $form->addButton($this->config->get("bck-btn"), 0, "textures/ui/icon_import");
        for ($i = 1; $i <= 100; $i++) {
            if ($this->config->exists("$i")) {
                $form->addButton($this->config->get("$i")["Button"]["Name"] . "\n§rPrice : " . $this->config->get("$i")["Key"]["Price"], 0, "textures/items/paper");
            }
        }
        $player->sendForm($form);
    }

    public function KeyShopExp($player) {
        $this->saveResource("exp.yml");
        $this->config = new Config($this->getDataFolder() . "exp.yml", Config::YAML, array());
        $form = new SimpleForm(function (Player $player, $data) {
            if ($data === null || $data === 0) {
                $this->KeyShopMenu($player);
                return true;
            }
            
            $exp = $player->getXpManager()->getXpLevel();
            if ($exp >= $this->config->get($data)["Key"]["Price"]) {
                $player->getXpManager()->setXpLevel($exp - $this->config->get($data)["Key"]["Price"]);
                $this->getServer()->getCommandMap()->dispatch(new ConsoleCommandSender($this->getServer(), $this->getServer()->getLanguage()), "key " . $this->config->get($data)["Key"]["Name"] . " 1 \"" . $player->getName() . "\"");
                $player->sendMessage(self::prefix . $this->config->get($data)["Message"]["Succes"]);
            } else {
                $player->sendMessage(self::prefix . $this->config->get($data)["Message"]["Failed"]);
            }
        });
        
        $exp = $player->getXpManager()->getXpLevel();
        $formatted = $this->converter($exp);
        $form->setTitle($this->config->get("Title"));
        $form->setContent("§g>> §eHi, §b" . $player->getName() . "\n§g>> §eYour Balance §a" . $formatted);
        $form->addButton($this->config->get("bck-btn"), 0, "textures/ui/icon_import");
        
        for ($i = 1; $i <= 100; $i++) {
            if ($this->config->exists("$i")) {
                $form->addButton($this->config->get("$i")["Button"]["Name"] . "\n§rPrice : " . $this->config->get("$i")["Key"]["Price"], 0, "textures/items/paper");
            }
        }
        
        $player->sendForm($form);
    }    

    public function converter($n, $precision =1) {
        $n_format = number_format($n, $precision, '.', ',');

        if ($precision > 0) {

            $dotzero = '.' . str_repeat('0', $precision);
            $n_format = str_replace($dotzero, '', $n_format);
        }
        return $n_format;
    }

}