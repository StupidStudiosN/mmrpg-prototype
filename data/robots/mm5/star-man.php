<?
// STAR MAN
$robot = array(
    'robot_number' => 'DWN-037',
    'robot_game' => 'MM05',
    'robot_name' => 'Star Man',
    'robot_token' => 'star-man',
    'robot_image_editor' => 18,
    'robot_core' => 'space',
    'robot_description' => 'Interstellar Research Robot',
    'robot_energy' => 100,
    'robot_attack' => 100,
    'robot_defense' => 100,
    'robot_speed' => 100,
    'robot_weaknesses' => array('water', 'earth'), //water-wave,power-stone
    'robot_affinities' => array('space'),
    'robot_abilities' => array(
        'star-crash',
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
                array('level' => 0, 'token' => 'star-crash')
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