<meta charset="utf-8">
<link href="classes.css" rel="stylesheet">
<?
ini_set( 'display_errors', '1' );
ini_set( 'error_reporting', E_ALL );
include_once 'c.php';

function printInfo( $title, $info ) {
	if ( is_array( $info ) ) {
		$info = '<pre>' . print_r( $info, true ) . '</pre>';
	} else {
		$info = "<b>" . $info . "</b>";
	}
	echo "<li>" . $title . ": " . $info . "</li>";
}

function getCards( $quanity = 1 ) {
	$rand_cards = array_rand( $GLOBALS['cards_tmp'], $quanity );

	if ( !is_array( $rand_cards ) ) {
		$rand_cards = array( $rand_cards );
	}

	foreach ( $rand_cards as $rand_card ) {
		unset( $GLOBALS['cards_tmp'][$rand_card] );
	}
	return $rand_cards;
}

function printPlayers() {
	$td = '';
	foreach ( $GLOBALS['players_tmp'] as $player ) {
		$userName = "<H3>" . $player['id'] . "</H3>";
		$td .= "<td>" . $userName . printCards( $player ) . "</td>";
	}

	echo "<table class='players'><tr>" . $td . "</tr></table>";
}

function printCards( $player ) {
	$card_ids = array_keys( $player['cards'] );
	$cards = '';
	foreach ( $card_ids as $card_id ) {
		$cards .= printCard( $card_id );
	}
	return $cards;
}

function printCard( $card_id ) {
	$tr = '';

	foreach ( $GLOBALS['cards'][$card_id] as $row ) {
		$td = '';
		for ( $c = 1; $c <= 9; $c++ ) {
			$prev_curr = 0;
			$got = false;
			$col_content = '';
			$curr = $c * 10;

			foreach ( $row as $col ) {
				$prev_curr = $curr - 10;
				if ( $prev_curr < 0 ) {
					$prev_curr = 0;
				}
				if (
						( $col !== 90 && $col < $curr && $col >= $prev_curr && $got == false ) ||
						( $col == 90 && $col <= $curr && $col >= $prev_curr && $got == false )
				) {
					$col_content = $col;
					$got = true;
				}
			}
			$checked = in_array( $col_content, $GLOBALS['fallen'] ) ? "class='checked'" : '';
			$td .= "<td " . $checked . ">" . $col_content . "</td>";
		}
		$tr .= "<tr>" . $td . "</tr>";
	}
	return $card_id . "<table class='card'>" . $tr . "</table>";
}

function draw_number( $steps = 0 ) {
	$barrel = array_rand( $GLOBALS['barrels_tmp'], 1 );
	$num = $GLOBALS['barrels_tmp'][$barrel];

	$GLOBALS['fallen'][] = $num;
	unset( $GLOBALS['barrels_tmp'][$barrel] );

	foreach ( $GLOBALS['players_tmp'] as $k => $player ) {
		foreach ( $player['cards'] as $c => $card ) {
			foreach ( $card as $r => $row ) {
				if ( sizeof( $row ) == 0 ) {
					if ( $r == '3' && $GLOBALS['game_type'] === 3 || $GLOBALS['game_type'] === 2 ) {
						$num = 'stop';

						if ( !array_key_exists( $player['id'], $GLOBALS['stat'] ) ) {
							$GLOBALS['stat'][$player['id']] = array();
						}
						if ( !array_key_exists( 'row_' . $r, $GLOBALS['stat'][$player['id']] ) ) {
							$GLOBALS['stat'][$player['id']]['row_' . $r] = '';
						}
						if ( !array_key_exists( 'win', $GLOBALS['stat'][$player['id']] ) ) {
							$GLOBALS['stat'][$player['id']]['win'] = 0;
						}
						$GLOBALS['stat'][$player['id']]['win'] = $GLOBALS['stat'][$player['id']]['win'] + 1;
						$GLOBALS['stat'][$player['id']]['row_' . $r] = $GLOBALS['stat'][$player['id']]['row_' . $r] + 1;
					}
				}
				foreach ( $row as $cc => $col ) {
					if ( $col == $num ) {
						unset( $GLOBALS['players_tmp'][$k]['cards'][$c][$r][$cc] );
					}
				}
			}
		}
	}

	return $num;
}

function setCards() {
	foreach ( $GLOBALS['players_tmp'] as $k => $player ) {
		if ( array_key_exists( 'cards_ids', $player ) ) {
			foreach ( $player['cards_ids'] as $card_id ) {
				unset( $GLOBALS['cards_tmp'][$card_id] );
			}
		}
	}

	foreach ( $GLOBALS['players_tmp'] as $k => $player ) {
		if ( array_key_exists( 'cards_ids', $player ) ) {
			foreach ( $player['cards_ids'] as $card_id ) {
				$GLOBALS['players_tmp'][$k]['cards'][$card_id] = $GLOBALS['cards'][$card_id];
			}
		} else {
			$card_ids = getCards( $player['cards_count'] );
			foreach ( $card_ids as $card_id ) {
				$GLOBALS['players_tmp'][$k]['cards'][$card_id] = $GLOBALS['cards'][$card_id];
			}
		}
	}
}

function refresh() {
	$GLOBALS['cards_tmp'] = $GLOBALS['cards'];
	$GLOBALS['barrels_tmp'] = $GLOBALS['barrels'];
	$GLOBALS['players_tmp'] = $GLOBALS['players'];
	$GLOBALS['fallen'] = array();
}

function printStat() {
	$tr = '<tr><th>Player</th><th>Row 1</th><th>Row 2</th><th>Row 3</th><th>Wins</th><th>Wins*</th></tr>';
	asort( $GLOBALS['stat'] );

	foreach ( $GLOBALS['players_tmp'] as $k => $player ) {


		if ( !array_key_exists( $player['id'], $GLOBALS['stat'] ) ) {
			$GLOBALS['stat'][$player['id']] = array();
		}

		if ( !array_key_exists( 'row_1', $GLOBALS['stat'][$player['id']] ) ) {
			$GLOBALS['stat'][$player['id']]['row_1'] = '';
		}

		if ( !array_key_exists( 'row_2', $GLOBALS['stat'][$player['id']] ) ) {
			$GLOBALS['stat'][$player['id']]['row_2'] = '';
		}

		if ( !array_key_exists( 'row_3', $GLOBALS['stat'][$player['id']] ) ) {
			$GLOBALS['stat'][$player['id']]['row_3'] = '';
		}

		if ( !array_key_exists( 'win', $GLOBALS['stat'][$player['id']] ) ) {
			$GLOBALS['stat'][$player['id']]['win'] = 0;
		}

		$tr .= '<tr>' .
				'<td>' . $player['id'] . '</td>' .
				'<td>' . $GLOBALS['stat'][$player['id']]['row_1'] . '</td>' .
				'<td>' . $GLOBALS['stat'][$player['id']]['row_2'] . '</td>' .
				'<td>' . $GLOBALS['stat'][$player['id']]['row_3'] . '</td>' .
				'<td>' . $GLOBALS['stat'][$player['id']]['win'] . '</td>' .
				'<td>' . ($GLOBALS['stat'][$player['id']]['win'] - $GLOBALS['stat'][$player['id']]['row_1']) . '</td>' .
				'</tr>';
	}
	echo "<table class='stat'>" . $tr . "</table>" .
	'* For game type = 2';
}

function start() {
	$end = false;
	$i = 1;
	$nums = array();

	while ( $end !== true ) {
		$num = draw_number();
		$nums[] = $num;
		$i++;

		if ( $num == 'stop' ) {
			$end = true;
		}

		if ( $i > 90 ) {
			$end = true;
		}
	}
}

for ( $c1 = 1; $c1 <= $meets; $c1++ ) {
	for ( $c = 1; $c <= $rounds; $c++ ) {
		setCards();
		start();
		printPlayers();

		refresh();
	}
	printStat();
	//printInfo( 'Result', $GLOBALS['stat'] );
	$GLOBALS['stat'] = array();
}

