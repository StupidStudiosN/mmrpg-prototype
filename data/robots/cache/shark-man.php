<?
// SHARK MAN
$robot = array(
  'robot_number' => 'PCR-001',
  'robot_game' => 'MM19',
  'robot_group' => 'MMAZ/Masters/MM19',
  'robot_name' => 'Shark Man',
  'robot_token' => 'shark-man',
  'robot_core' => 'water',
  'robot_description' => 'Swift Swimming Robot',
  'robot_energy' => 100,
  'robot_attack' => 100,
  'robot_defense' => 100,
  'robot_speed' => 100,
  'robot_weaknesses' => array(), //blizzard-attack,[gyro-attack],atomic-fire
  'robot_resistances' => array(),
  'robot_affinities' => array(),
  'robot_abilities' => array(
  	//'plant-barrier',
  	'buster-shot',
  	'attack-boost', 'attack-break', 'attack-swap', 'attack-mode',
  	'defense-boost', 'defense-break', 'defense-swap', 'defense-mode',
    'speed-boost', 'speed-break', 'speed-swap', 'speed-mode',
    'energy-boost', 'energy-break', 'energy-swap', 'repair-mode',
    'field-support', 'mecha-support',
    'light-buster', 'wily-buster', 'cossack-buster'
    ),
  'robot_rewards' => array(
    'abilities' => array(
        array('level' => 0, 'token' => 'fin-splash'),
        array('level' => 10, 'token' => 'fin-cutter')
      )
    ),
  'robot_quotes' => array(
    'battle_start' => '',
    'battle_taunt' => '',
    'battle_victory' => '',
    'battle_defeat' => ''
    )
  );
?>