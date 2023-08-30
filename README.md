# V-KeyShopMenu

KeyShopMenu is a plugin designed for selling keys to use with PiggyCrates.

## Dependencies
- EconomyAPI
- CoinAPI
- PiggyCrates

## Commands
- `/keyshop`: Opens the KeyShop Menu

## Configuration
To configure the KeyShopMenu, you can modify the following settings in the plugin's configuration file:

```yaml
### [ V-KeyShop ] ###

# use § To Change Colors Text

# Depencies Plugin Need
- EconomyAPI
- CoinAPI
- PiggyCrates

# Back Button Configurations
bck-btn: "§l§cBack\n§r§8Tap To Back"

# Config Money
Title: "§l§bV§f-§eKey§aShop"
1:
  Key:
    Name: "common" # Key Name
    Price: 1000 # Money Price key
  Button:
    Name: "§l§eCommon Key" # Button Name
  Message:
    Succes: "§aSucces buy a key Common" # Success Message Buy A Key
    Failed: "§cYour money is not enough" # Failed Message Buy A Key
    
2:
  Key:
    Name: "Legendary"
    Price: 5000
  Button:
    Name: "§l§eLegendary Key"
  Message:
    Succes: "§aSucces buy a key Legendary"
    Failed: "§cYour money is not enough"

#    XXXXXXXXb  XXX      XXX   XXX      XXX  XXXXX     XXXXX  XXXXXXXXXXb  
#   XXXXXXXXXX  XXX      XXX   XXX      XXX  XXXXXX   XXXXXX  XXXXXXXXXXX  
#  XXXP         XXX      XXX   XXX      XXX  XXX XXX XXX XXX  XXX
#  XXX          XXXXXXXXXXXX   XXX      XXX  XXX XXX XXX XXX  XXXXXXXD
#  XXXb         XXX      XXX   XXX      XXX  XXX  XXXXX  XXX  XXX
#   XXXXXXXXXX  XXX      XXX   YXXXXXXXXXXY  XXX   XXX   XXX  XXXXXXXXXXX
#    XXXXXXXXP  XXX      XXX    XXXXXXXXXP   XXX         XXX  XXXXXXXXXXP
