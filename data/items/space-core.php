<?
// ITEM : SPACE CORE
$ability = array(
  'ability_name' => 'Space Core',
  'ability_token' => 'space-core',
  'ability_game' => 'MMRPG',
  'ability_class' => 'item',
  'ability_type' => 'space',
  'ability_description' => 'A mysterious elemental core that radiates with the Space type energy of a defeated robot master. When used in battle, this item can be thrown at any target to deal Space type damage of up to {DAMAGE}%!',
  'ability_energy' => 0,
  'ability_speed' => 10,
  'ability_damage' => 10,
  'ability_damage_percent' => true,
  'ability_accuracy' => 100,
  'ability_target' => 'select_target',
  'ability_function' => function($objects){
    return rpg_ability::item_function_core($objects);
  }
  );
?>