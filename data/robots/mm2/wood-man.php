<?
// WOOD MAN
$robot = array(
    'robot_number' => 'DWN-016',
    'robot_game' => 'MM02',
    'robot_name' => 'Wood Man',
    'robot_token' => 'wood-man',
    'robot_image_editor' => 412,
    'robot_image_alts' => array(
        array('token' => 'alt', 'name' => 'Wood Man (Burnt Alt)', 'summons' => 100, 'colour' => 'shield'),
        array('token' => 'alt2', 'name' => 'Wood Man (Mossy Alt)', 'summons' => 200, 'colour' => 'nature'),
        array('token' => 'alt9', 'name' => 'Wood Man (Darkness Alt)', 'summons' => 900, 'colour' => 'empty')
        ),
    'robot_core' => 'nature',
    'robot_description' => 'Forest Protector Robot',
    'robot_field' => 'preserved-forest',
    'robot_energy' => 100,
    'robot_attack' => 100,
    'robot_defense' => 100,
    'robot_speed' => 100,
    'robot_weaknesses' => array('flame', 'cutter'),
    'robot_resistances' => array('electric', 'water'),
    'robot_abilities' => array(
        'leaf-shield',
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
                array('level' => 0, 'token' => 'leaf-shield')
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