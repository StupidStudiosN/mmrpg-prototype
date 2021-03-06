<?
// NAPALM MAN
$robot = array(
    'robot_number' => 'DWN-039',
    'robot_game' => 'MM05',
    'robot_name' => 'Napalm Man',
    'robot_token' => 'napalm-man',
    'robot_image_editor' => 110,
    'robot_image_size' => 80,
    'robot_core' => 'explode',
    'robot_description' => 'Walking Arsenal Robot',
    'robot_energy' => 100,
    'robot_attack' => 100,
    'robot_defense' => 100,
    'robot_speed' => 100,
    'robot_weaknesses' => array('crystal', 'flame'), //crystal-eye,pharaoh-wave
    'robot_immunities' => array('explode'),
    'robot_abilities' => array(
        'napalm-bomb',
        'buster-shot',
        'attack-boost', 'attack-break', 'attack-swap', 'attack-mode',
        'defense-boost', 'defense-break', 'defense-swap', 'defense-mode',
        'speed-boost', 'speed-break', 'speed-swap', 'speed-mode',
        'energy-boost', 'energy-break', 'energy-swap', 'energy-mode',
        'field-support', 'mecha-support',
        'light-buster', 'wily-buster', 'cossack-buster'
        ),
    'robot_rewards' => array(
        'abilities' => array(
                array('level' => 0, 'token' => 'napalm-bomb')
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