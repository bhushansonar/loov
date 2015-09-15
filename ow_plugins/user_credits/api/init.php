<?php

OW::getRouter()->addRoute(
    new OW_Route('usercredits.buy_credits', 'user-credits/buy-credits', 'USERCREDITS_CTRL_BuyCredits', 'index')
);

USERCREDITS_CLASS_EventHandler::getInstance()->genericInit();