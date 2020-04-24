<?php

namespace PokerHand;

class PokerHand
{
    public function __construct($hand)
    {
    	$hand = str_replace(['2', '3', '4', '5', '6', '7', '8', '9', 'J', 'Q', 'K', 'A'], ['02', '03', '04', '05', '06', '07', '08', '09', '11', '12', '13', '14'], $hand);
    	$hand = explode(' ', $hand);
    	$this->hand = $hand;

    	//$hand = implode(", ", $this->hand);

    	$handLength = count($hand);

    	$newHand = array
    	(
    		str_split($hand[0], 2),
    		str_split($hand[1], 2),
    		str_split($hand[2], 2),
    		str_split($hand[3], 2),
    		str_split($hand[4], 2),
    	);

    	$hand = $newHand;

    	$values = [];
    	$suits = [];

    	for ($i = 0; $i < $handLength; $i++){
    		array_push($values, $hand[$i][0]);
    		array_push($suits, $hand[$i][1]);
    	}
    	$finishedHand = [];
    	$finishedHand[0] = $values;
    	$finishedHand[1] = $suits;

    	$this->hand = $finishedHand;
    	
    	// print_r($values);
    	// print_r($suits);
    	for ($i = 0; $i < $handLength; $i++){
    		$this->hand[0][$i] = (int)$this->hand[0][$i];
    	}

    	$kinds = array_values(array_count_values($this->hand[0]));
    	$this->hand[2] = $kinds;

    	$this->sortKinds();
    	$this->sortValues();

    	$straights = array
    	(
    		array(14, 2, 3, 4, 5),
    		array(1, 2, 3, 4, 5),
    		array(2, 3, 4, 5, 6),
    		array(3, 4, 5, 6, 7),
    		array(4, 5, 6, 7, 8),
    		array(5, 6, 7, 8, 9),
    		array(6, 7, 8, 9, 10),
    		array(7, 8, 9, 10, 11),
    		array(8, 9, 10, 11, 12),
    		array(9, 10, 11, 12, 13),
    		array(10, 11, 12, 13, 14)
    	);

    	    	$this->hand[3] = $straights;
    }

    protected function sortKinds(){
    	usort($this->hand[2], function ($x, $y){
    		return -( $x <=> $y);
    	});
    }

    protected function sortValues(){
    	usort($this->hand[0], function ($x, $y){
    			return -( $x <=> $y);
    	});
    }


    protected function royalFlush(){
    	if (($this->straightFlush()) && ($this->hand[0][0] == 14)){
    		return true;
    	}else {
    	return false;
    	}
	}

	 protected function threeOfAKind(){
    	if ($this->hand[2][0] === 3){
    		return true;
    	};
    }

    protected function twoPair(){
    	if ((count(array_unique($this->hand[0])) === 3) && ($this->hand[2][0] === 2) && ($this->hand[2][1] === 2)){
    		return true;
    	}
    }

    protected function pair(){
    	if (count(array_unique($this->hand[0])) === 4){
    		return true;
    	}
    }

    protected function flush(){
	if (count(array_unique($this->hand[1])) === 1){
    	 	return true;
    	 };
    }

    protected function straightFlush(){
    	if ($this->straight() && $this->flush()){
    		return true;
    	}
    		return false;
    	
    }

    protected function straightCheck(){
    	for ($i = 0; $i < 10; $i++){
    		if (count(array_intersect($this->hand[3][$i], $this->hand[0])) !== 5){
    			return false;
    		}
    	}
    	return true;
    }

    protected function fullHouse(){
    	if (($this->hand[2][0] === 3) && ($this->hand[2][1] === 2)){
    		return true;
    	};
    }

    protected function fourOfAKind(){
    	if (($this->hand[2][0] === 4) && (count(array_unique($this->hand[0])) === 2)){
    		return true;
    	}
    }

    protected function evilStraight(){
    	if (($this->hand[0][0] === 14) && ($this->hand[0][1] === 5)){
    		$this->hand[0][0] = 1;
    		$correctHand = [1, 2, 3, 4, 5];
    		if (count(array_intersect($correctHand, $this->hand[0]) == count($correctHand))){
    			return true;
    		}
    	}
    }

    protected function reverseStraight(){
    	for ($i = 0; $i < count($this->hand[0]); $i++){
    		 if ($this->hand[0][$i] !== (($this->hand[0][$i+1])+1)){
    		 	return false;
    		 }
    	}
    		return true;
    }

    protected function straight(){

    	for ($i = 1; $i < count($this->hand[0]); $i++){
    		 if ($this->hand[0][$i-1] !== ($this->hand[0][$i]+1)){
    		 	return false;
    		 }
    	}

    	return true;

    }

    public function getRank()
    {
        // TODO: Implement poker hand ranking
        switch($this){
        	case $this->royalFlush(): return 'Royal Flush';
        	break;
        	case $this->twoPair(): return 'Two Pair';
        	break;
        	case $this->pair(): return 'One Pair';
        	break;
        	case $this->flush(); return 'Flush';
        	break;
        	case $this->straightFlush(): return 'Straight Flush';
        	break;
        	case $this->fullHouse(): return 'Full House';
        	break;
        	case $this->fourOfAKind(): return 'Four of a Kind';
        	break;
        	case $this->evilStraight(): return 'Evil Straight';
        	break;
        	case $this->threeOfAKind(): return 'Three of a Kind';
        	break;
        	case $this->straight(): return 'Straight';
        	break;
        	case $this->straight() && $this->flush(): return 'Straight Flush';
        	break;
        	default: return 'High Card';
        }
    }
}