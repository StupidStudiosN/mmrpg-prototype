<?
// SPARK MAN
$robot = array(
    'robot_number' => 'DWN-023',
    'robot_game' => 'MM03',
    'robot_name' => 'Spark Man',
    'robot_token' => 'spark-man',
    'robot_image_editor' => 110,
    'robot_image_size' => 80,
    'robot_image_alts' => array(
        array('token' => 'alt', 'name' => 'Spark Man (Blue Alt)', 'summons' => 100, 'colour' => 'water'),
        array('token' => 'alt2', 'name' => 'Spark Man (Yellow Alt)', 'summons' => 200, 'colour' => 'electric'),
        array('token' => 'alt9', 'name' => 'Spark Man (Darkness Alt)', 'summons' => 900, 'colour' => 'empty')
        ),
    'robot_core' => 'electric',
    'robot_field' => 'power-plant',
    'robot_description' => 'Double Spark-Plug Robot',
    'robot_energy' => 100,
    'robot_attack' => 100,
    'robot_defense' => 100,
    'robot_speed' => 100,
    'robot_weaknesses' => array('shadow', 'crystal'), //shadow-blade
    'robot_resistances' => array('water', 'freeze'),
    'robot_abilities' => array(
        'spark-shock',
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
                array('level' => 0, 'token' => 'spark-shock')
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