<?
// SHEEP MAN
$robot = array(
    'robot_number' => 'DWN-077',
    'robot_game' => 'MM10',
    'robot_name' => 'Sheep Man',
    'robot_token' => 'sheep-man',
    'robot_image_editor' => 3842,
    'robot_core' => 'electric',
    'robot_description' => 'Static Electricity Robot',
    'robot_energy' => 100,
    'robot_attack' => 100,
    'robot_defense' => 100,
    'robot_speed' => 100,
    'robot_weaknesses' => array('impact', 'flame'),
    'robot_immunities' => array('electric'),
    'robot_abilities' => array(
        'thunder-wool',
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
                array('level' => 0, 'token' => 'thunder-wool')
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