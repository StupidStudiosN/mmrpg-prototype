<?
// ITEM : NATURE SHARD
$item = array(
    'item_name' => 'Nature Shard',
    'item_token' => 'nature-shard',
    'item_game' => 'MMRPG',
    'item_group' => 'MMRPG/Items/Nature',
    'item_class' => 'item',
    'item_subclass' => 'collectible',
    'item_type' => 'nature',
    'item_description' => 'A mysterious elemental shard that radiates with the Nature type energy of a defeated support mecha.  Collect four of these items to generate a new core that can be traded in at the shop for a variable amount of Zenny.',
    'item_energy' => 0,
    'item_speed' => 10,
    'item_accuracy' => 100,
    'item_target' => 'auto',
    'item_function' => function($objects){
        return rpg_item::item_function_shard($objects);
    }
    );
?>