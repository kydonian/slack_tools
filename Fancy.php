<?php
//Here you can add any emoji from your workspace and use function to randomly generate an emoji for new message
function Get_Random_Emoji( $type ){
	$emojis = [
		'hello'=>[ ":fox-hello:", ":pikachu_hello:", ":shine:", ":hi:", ":privet:", ":goodnews:" ],			
	];
	
	$max = count( $emojis[$type] );

	return $emojis[$type][rand( 1, count( $emojis[$type] ) - 1 )];

}

?>