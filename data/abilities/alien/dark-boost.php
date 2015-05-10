<?
// DARK BOOST
$ability = array(
  'ability_name' => 'Dark Boost',
  'ability_token' => 'dark-boost',
  'ability_game' => 'MMEXE',
  'ability_class' => 'mecha',
  'ability_type' => 'empty',
  'ability_description' => 'The user cloaks itself in dark energy to recover a random one of its stats by {RECOVERY2}%.',
  'ability_energy' => 0,
  'ability_recovery' => 80,
  'ability_recovery_percent' => true,
  'ability_recovery2' => 20,
  'ability_recovery_percent' => true,
  'ability_accuracy' => 100,
  'ability_function' => function($objects){

    // Extract all objects into the current scope
    extract($objects);

    // Target the opposing robot
    $this_ability->target_options_update(array(
      'frame' => 'summon',
      'success' => array(0, 75, 0, 10, 'The '.$this_robot->print_robot_name().' started boosting its stats!')
      ));
    $this_robot->trigger_target($target_robot, $this_ability);

    // Define the lowest stat for the user
    $temp_stat_values = array('attack' => $this_robot->robot_attack, 'defense' => $this_robot->robot_defense, 'speed' => $this_robot->robot_speed);
    asort($temp_stat_values, SORT_NUMERIC);
    reset($temp_stat_values);
    $temp_stat_boost = key($temp_stat_values);
    if ($this_robot->robot_energy <= ceil($this_robot->robot_base_energy / 2)){ $temp_stat_boost = 'energy'; }

    // If an energy boost was requested by the above variables
    if ($temp_stat_boost == 'energy'){

      // Increase the target robot's energy stat
      if ($this_robot->robot_energy < $this_robot->robot_base_energy){
        $this_ability->recovery_options_update(array(
          'kind' => 'energy',
          'percent' => true,
          'modifiers' => false,
          'frame' => 'taunt',
          'success' => array(0, -2, 0, -10, $this_robot->print_robot_name().'&#39;s energy was restored!'),
          'failure' => array(9, -2, 0, -10, $this_robot->print_robot_name().'&#39;s energy was not affected&hellip;')
          ));
        $energy_recovery_amount = ceil($this_robot->robot_base_energy * ($this_ability->ability_recovery2 / 100));
        $this_robot->trigger_recovery($this_robot, $this_ability, $energy_recovery_amount);
      }

    }
    // Else if an attack boost was requested by the above variables
    elseif ($temp_stat_boost == 'attack'){

      // Increase this robot's attack stat
      $this_ability->recovery_options_update(array(
        'kind' => 'attack',
        'percent' => true,
        'modifiers' => false,
        'frame' => 'taunt',
        'success' => array(9, 0, 0, -9999, 'The '.$this_robot->print_robot_name().'&#39;s weapons powered up!'),
        'failure' => array(9, 0, 0, -9999, 'The '.$this_robot->print_robot_name().'&#39;s weapons were not affected&hellip;')
        ));
      $attack_recovery_amount = ceil($this_robot->robot_base_attack * ($this_ability->ability_recovery2 / 100));
      $this_robot->trigger_recovery($this_robot, $this_ability, $attack_recovery_amount);

    }
    // Else if an defense boost was requested by the above variables
    elseif ($temp_stat_boost == 'defense'){

      // Increase this robot's defense stat
      $this_ability->recovery_options_update(array(
        'kind' => 'defense',
        'percent' => true,
        'modifiers' => false,
        'frame' => 'taunt',
        'success' => array(9, 0, 0, -9999, 'The '.$this_robot->print_robot_name().'&#39;s shields powered up!'),
        'failure' => array(9, 0, 0, -9999, 'The '.$this_robot->print_robot_name().'&#39;s shields were not affected&hellip;')
        ));
      $defense_recovery_amount = ceil($this_robot->robot_base_defense * ($this_ability->ability_recovery2 / 100));
      $this_robot->trigger_recovery($this_robot, $this_ability, $defense_recovery_amount);

    }
    // Else if an speed boost was requested by the above variables
    elseif ($temp_stat_boost == 'speed'){

      // Increase this robot's speed stat
      $this_ability->recovery_options_update(array(
        'kind' => 'speed',
        'percent' => true,
        'modifiers' => false,
        'frame' => 'taunt',
        'success' => array(9, 0, 0, -9999, 'The '.$this_robot->print_robot_name().'&#39;s mobility improved!'),
        'failure' => array(9, 0, 0, -9999, 'The '.$this_robot->print_robot_name().'&#39;s mobility was not affected&hellip;')
        ));
      $speed_recovery_amount = ceil($this_robot->robot_base_speed * ($this_ability->ability_recovery2 / 100));
      $this_robot->trigger_recovery($this_robot, $this_ability, $speed_recovery_amount);

    }

    // Return true on success
    return true;

  }
  );
?>