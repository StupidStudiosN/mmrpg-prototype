<?
// DIVE MAN
$robot = array(
    'robot_number' => 'DCN-031',
    'robot_game' => 'MM04',
    'robot_name' => 'Dive Man',
    'robot_token' => 'dive-man',
    'robot_image_editor' => 18,
    'robot_image_size' => 80,
    'robot_image_alts' => array(
        array('token' => 'alt', 'name' => 'Dive Man (Crimson Alt)', 'summons' => 100, 'colour' => 'flame'),
        array('token' => 'alt2', 'name' => 'Dive Man (Platinum Alt)', 'summons' => 200, 'colour' => 'cutter'),
        array('token' => 'alt9', 'name' => 'Dive Man (Darkness Alt)', 'summons' => 900, 'colour' => 'empty')
        ),
    'robot_core' => 'missile',
    'robot_field' => 'submerged-armory',
    'robot_description' => 'Submerged Warfare Robot',
    'robot_energy' => 100,
    'robot_attack' => 100,
    'robot_defense' => 100,
    'robot_speed' => 100,
    'robot_weaknesses' => array('shadow', 'electric'), //skull-barrier, thunder-beam
    'robot_resistances' => array('water', 'cutter'),
    'robot_abilities' => array(
        'dive-torpedo',
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
                array('level' => 0, 'token' => 'dive-torpedo')
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