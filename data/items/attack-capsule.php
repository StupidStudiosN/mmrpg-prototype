<?
// ITEM : ATTACK CAPSULE
$ability = array(
    'ability_name' => 'Attack Capsule',
    'ability_token' => 'attack-capsule',
    'ability_game' => 'MMRPG',
    'ability_group' => 'MM00/Items/Attack',
    'ability_class' => 'item',
    'ability_type' => 'attack',
    'ability_description' => 'A large weapon capsule that that boosts the attack stat of one robot on the user\'s side of the field by {RECOVERY}%. This item\'s effects appear to be permanent, though only up until the target has reached its max stat limit.',
    'ability_energy' => 0,
    'ability_speed' => 10,
    'ability_recovery' => 20,
    'ability_recovery_percent' => true,
    'ability_accuracy' => 100,
    'ability_target' => 'select_this',
    'ability_function' => function($objects){

        // Call the global stat booster item function
        return rpg_ability::item_function_stat_booster($objects);

        }
    );
?>