<?
// CLOUD MAN
$robot = array(
    'robot_number' => 'DWN-052',
    'robot_game' => 'MM07',
    'robot_name' => 'Cloud Man',
    'robot_token' => 'cloud-man',
    'robot_image_editor' => 3842,
    'robot_image_size' => 80,
    'robot_core' => 'electric',
    'robot_description' => 'Hovering Cloud Robot',
    'robot_energy' => 100,
    'robot_attack' => 100,
    'robot_defense' => 100,
    'robot_speed' => 100,
    'robot_weaknesses' => array('explode', 'wind'),
    'robot_immunities' => array('earth'),
    'robot_abilities' => array(
        'thunder-bolt',
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
                array('level' => 0, 'token' => 'thunder-bolt')
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