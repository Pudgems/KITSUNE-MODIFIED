<?php

namespace Kitsune\ClubPenguin\Handlers\Game;

use Kitsune\ClubPenguin\Packets\Packet;

trait General {
    

    public function handleGameOver($socket)
    {
        $penguin = $this->penguins[$socket];
        $score = Packet::$Data[2];   
        $NoDivide = array(916, 906, 905, 904, 912);
    		
		if (in_array($penguin->room->externalId, $NoDivide))
        {
			$penguin->addCoins($score);
        } else if($score < 99999) {
			$penguin->addCoins(floor($score/10));
        }
     

        if (isset($this->gameStamps[$penguin->room->externalId]))
        {
            $Stamps = explode(",", $penguin->database->getColumnById($penguin->id, "Stamps"));
            $Collected = array_intersect($Stamps, $this->gameStamps[$penguin->room->externalId]);
            $CollectedStamps = implode("|", $Collected);

            $GameStamps = array_merge(array_values($this->gameStamps));

            $penguin->send("%xt%zo%-1%{$penguin->coins}%{$CollectedStamps}%" . count($Collected) . "%" . count($this->gameStamps[$penguin->room->externalId]) . "%" . count($Stamps) . "%");
        } else 
        {
            $penguin->send("%xt%zo%-1%{$penguin->coins}%%%%%");
        }
    }
}
?>