<?
// BURST MAN
$robot = array(
    'robot_number' => 'DWN-051',
    'robot_game' => 'MM07',
    'robot_name' => 'Burst Man',
    'robot_token' => 'burst-man',
    'robot_image_editor' => 3842,
    'robot_image_size' => 80,
    'robot_core' => 'explode',
    'robot_description' => 'Liquid Fireworks Robot',
    'robot_energy' => 100,
    'robot_attack' => 100,
    'robot_defense' => 100,
    'robot_speed' => 100,
    'robot_weaknesses' => array('freeze', 'swift'),
    'robot_resistances' => array('water'),
    'robot_abilities' => array(
        'danger-wrap',
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
                array('level' => 0, 'token' => 'danger-wrap')
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