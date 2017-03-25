<?php

// Prevent updating if logged into a file
if ($this_user['userid'] != MMRPG_SETTINGS_GUEST_ID){ die('<strong>FATAL UPDATE ERROR!</strong><br /> You cannot be logged in while importing!');  }

// Collect any extra request variables for the import
$this_import_limit = !empty($_REQUEST['limit']) && is_numeric($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;

// Print out the menu header so we know where we are
ob_start();
?>
<div style="margin: 0 auto 20px; font-weight: bold;">
<a href="admin.php">Admin Panel</a> &raquo;
<a href="admin.php?action=import-missions&limit=<?= $this_import_limit?>">Update Mission Database</a> &raquo;
</div>
<?php
$this_page_markup .= ob_get_clean();



// ------------------------------ //
// MISSION VARIABLES / FUNCTIONS
// ------------------------------ //

// Define a variable to hold all database missions
$mmrpg_database_missions = array();

// Define a quick insert function for new missions
function mmrpg_insert_mission($this_mission){
    global $mmrpg_database_missions;

    // Define defaults for required mission keys
    if (!isset($this_mission['phase'])){ $this_mission['phase'] = 0; }
    if (!isset($this_mission['chapter'])){ $this_mission['chapter'] = 0; }
    if (!isset($this_mission['group'])){ $this_mission['group'] = ''; }
    if (!isset($this_mission['field'])){ $this_mission['field'] = ''; }
    if (!isset($this_mission['player'])){ $this_mission['player'] = ''; }

    // Generate a unique token for this missing using keys
    $token = array();
    //if (!empty($this_mission['phase'])){ $token[] = 'p'.$this_mission['phase']; }
    if (!empty($this_mission['chapter'])){ $token[] = 'c'.$this_mission['chapter']; }
    if (!empty($this_mission['group'])){ $token[] = $this_mission['group']; }
    if (!empty($this_mission['field']) && $this_mission['field'] != 'field'){ $token[] = $this_mission['field']; }
    if (!empty($this_mission['player']) && $this_mission['player'] != 'player'){ $token[] = $this_mission['player']; }
    $token = implode('_', $token);
    $this_mission['token'] = $token;

    // Define defaults for all other mission fields
    if (!isset($this_mission['field_types'])){ $this_mission['field_types'] = array(); }
    if (!isset($this_mission['field_sources'])){ $this_mission['field_sources'] = array(); }
    if (!isset($this_mission['target_player'])){ $this_mission['target_player'] = ''; }
    if (!isset($this_mission['target_robots'])){ $this_mission['target_robots'] = array(); }
    if (!isset($this_mission['target_mooks'])){ $this_mission['target_mooks'] = array(); }
    if (!isset($this_mission['level_start'])){ $this_mission['level_start'] = 0; }
    if (!isset($this_mission['level_limit'])){ $this_mission['level_limit'] = 0; }
    if (!isset($this_mission['button_size'])){ $this_mission['button_size'] = '1x1'; }
    if (!isset($this_mission['button_order'])){ $this_mission['button_order'] = 0; }

    // Implode any array-based fields into strings
    foreach ($this_mission AS $field => $value){
        if (is_array($value)){
            $value = implode(',', $value);
            $this_mission[$field] = $value;
        }
    }

    // Compensate for mission level limit variable
    if (empty($this_mission['level_limit'])){
        $this_mission['level_limit'] = $this_mission['level_start'];
    }

    // Append the "mission" prefix to all fields then insert
    $this_backup = $this_mission;
    $this_mission = array();
    foreach ($this_backup AS $f => $v){ $this_mission['mission_'.$f] = $v; }
    $mmrpg_database_missions[] = $this_mission;

}



// ------------------------- //
// MISSION SEEDS / TOKENS
// ------------------------- //

// Define the ROBOT tokens we'll be generating missions for
$mmrpg_omega_factors = $db->get_array_list("SELECT
    irobots.robot_token AS omega_robot,
    irobots.robot_core AS omega_type,
    ifields.field_token AS omega_field,
    ifields.field_mechas AS omega_mechas,
    irobots.robot_game AS omega_group
    FROM mmrpg_index_robots AS irobots
    LEFT JOIN mmrpg_index_fields AS ifields ON ifields.field_token = irobots.robot_field
    WHERE
    irobots.robot_class = 'master'
    AND irobots.robot_core <> ''
    AND irobots.robot_core <> 'copy'
    AND irobots.robot_core <> 'empty'
    AND irobots.robot_flag_complete = 1
    AND ifields.field_flag_complete = 1
    ORDER BY
    irobots.robot_order ASC
    ;");

// Define the PLAYER+FIELD tokens we'll be generating missions for
$mmrpg_player_tokens = array('dr-light', 'dr-wily', 'dr-cossack');
$mmrpg_player_stat_tokens = array('defense', 'attack', 'speed');
$mmrpg_player_field_tokens = array('light-laboratory', 'wily-castle', 'cossack-citadel');
$mmrpg_player_robot_master_tokens = array('mega-man', 'bass', 'proto-man');
$mmrpg_player_target_mooks_tokens = array('roll', 'disco', 'rhythm');

// Define the RIVAL-PLAYER+FIELD tokens we'll be generating missions for
$mmrpg_rival_tokens = array('dr-wily', 'dr-cossack', 'dr-light');

// Define the MECHA-JOE tokens that we'll be using in our missions
$mmrpg_mecha_joe_tokens = array('sniper-joe', 'skeleton-joe', 'crystal-joe');

// Define the KILLER-ROBOT tokens that we'll be using in our missions
$mmrpg_killer_robot_tokens = array('enker', 'punk', 'ballade');

// Define the MISSION-LEVEL counters that the entire game will use for missions
$mmrpg_mission_levels = array(
    1 => array(1, 2, 3),        // Chapter One   (Intro Battles)
    2 => array(4, 1, 12),       // Chapter Two   (Master Battles)
    3 => array(14, 16, 18),     // Chapter Three (Rival Battles)
    4 => array(20, 2, 30),      // Chapter Four  (Fusion Battles)
    5 => array(35, 40, 45),     // Chapter Five  (Darkness Battles)
    6 => array(50, 60, 70),     // Chapter Six   (Stardroid Battles)
    7 => array(80, 90, 100),    // Chapter Seven (Final Battles)
    8 => array(200, 100, 1000)  // Chapter Eight (Cache Battles)
    );

// Define the MISSION-GROUP-COUNTERS that the entire game will use for missions
$mmrpg_mission_group_counters = array(
    1 => array(3),              // Chapter One   (Intro Battles)
    2 => array(8, 1),           // Chapter Two   (Master Battles)
    3 => array(3),              // Chapter Three (Rival Battles)
    4 => array(4, 1),           // Chapter Four  (Fusion Battles)
    5 => array(3),              // Chapter Five  (Darkness Battles)
    6 => array(3),              // Chapter Six   (Stardroid Battles)
    7 => array(3),              // Chapter Seven (Final Battles)
    8 => array(8, 1)            // Chapter Eight (Cache Battles)
    );

// Define the MISSION-BUTTON-SIZE counters that the entire game will use for missions
$mmrpg_mission_button_sizes = array(
    1 => array(1, 1, 1),       // Chapter One   (Intro Battles)
    2 => array(4, 4, 1),       // Chapter Two   (Master Battles)
    3 => array(1, 1, 1),       // Chapter Three (Rival Battles)
    4 => array(2, 2, 1),       // Chapter Four  (Fusion Battles)
    5 => array(1, 1, 1),       // Chapter Five  (Darkness Battles)
    6 => array(1, 1, 1),       // Chapter Six   (Stardroid Battles)
    7 => array(1, 1, 1),       // Chapter Seven (Final Battles)
    8 => array(4, 4, 1)        // Chapter Eight (Cache Battles)
    );



// ------------------------------ //
// MISSION GENERATION : INIT
// ------------------------------ //

// Define the global PHASE and CHAPTER variables at zero
$this_mission_phase = 0;
$this_mission_chapter = 0;



// ------------------------------ //
// MISSION GENERATION : PHASE ONE
// ------------------------------ //

// PHASE 1 START
$this_mission_phase++;

// CHAPTER ONE / Intro Battles
$this_mission_chapter++;
$this_mission_levels = $mmrpg_mission_levels[$this_mission_chapter];
$this_mission_group_counters = $mmrpg_mission_group_counters[$this_mission_chapter];
$this_mission_button_sizes = $mmrpg_mission_button_sizes[$this_mission_chapter];
foreach ($mmrpg_player_tokens AS $player_key => $player_token){
    $button_order = 0;

    $rival_token = $mmrpg_rival_tokens[$player_key];
    $rival_key = array_search($rival_token, $mmrpg_player_tokens);
    $final_key = isset($mmrpg_player_tokens[$rival_key + 1]) ? $rival_key + 1 : 0;

    // vs MET
    $button_order++;
    $group_token = 'intro-battle';
    $field_token = 'intro-field';
    $target_player = '';
    $target_robots = array('met');
    $target_mooks = array();
    $field_types = array();
    $field_sources = array($field_token);
    $level_start = $this_mission_levels[0];
    $button_size = '1x'.$this_mission_button_sizes[0];
    mmrpg_insert_mission(array(
        'phase' => $this_mission_phase,
        'chapter' => $this_mission_chapter,
        'group' => $group_token,
        'field' => $field_token,
        'player' => $player_token,
        'target_player' => $target_player,
        'target_robots' => $target_robots,
        'target_mooks' => $target_mooks,
        'field_types' => $field_types,
        'field_sources' => $field_sources,
        'level_start' => $level_start,
        'button_size' => $button_size,
        'button_order' => $button_order
        ));

    // vs MECHA JOE
    $button_order++;
    $group_token = 'intro-battle';
    $field_token = $mmrpg_player_field_tokens[$player_key];
    $target_player = '';
    $target_robots = array($mmrpg_mecha_joe_tokens[$player_key]);
    $target_mooks = array();
    $field_types = array();
    $field_sources = array($field_token);
    $level_start = $this_mission_levels[1];
    $button_size = '1x'.$this_mission_button_sizes[1];
    mmrpg_insert_mission(array(
        'phase' => $this_mission_phase,
        'chapter' => $this_mission_chapter,
        'group' => $group_token,
        'field' => $field_token,
        'player' => $player_token,
        'target_player' => $target_player,
        'target_robots' => $target_robots,
        'target_mooks' => $target_mooks,
        'field_types' => $field_types,
        'field_sources' => $field_sources,
        'level_start' => $level_start,
        'button_size' => $button_size,
        'button_order' => $button_order
        ));

    // vs TRILL
    $button_order++;
    $group_token = 'intro-battle';
    $field_token = 'prototype-subspace';
    $target_player = '';
    $target_robots = array('trill_'.$mmrpg_player_stat_tokens[$rival_key]);
    $target_mooks = array();
    $field_types = array();
    $field_sources = array($field_token);
    $level_start = $this_mission_levels[2];
    $button_size = '1x'.$this_mission_button_sizes[2];
    mmrpg_insert_mission(array(
        'phase' => $this_mission_phase,
        'chapter' => $this_mission_chapter,
        'group' => $group_token,
        'field' => $field_token,
        'player' => $player_token,
        'target_player' => $target_player,
        'target_robots' => $target_robots,
        'target_mooks' => $target_mooks,
        'field_types' => $field_types,
        'field_sources' => $field_sources,
        'level_start' => $level_start,
        'button_size' => $button_size,
        'button_order' => $button_order
        ));

}

// ------------------------------ //
// MISSION GENERATION : PHASE TWO
// ------------------------------ //

// PHASE 2 START
$this_mission_phase++;

// CHAPTER TWO / Master Battles
$this_mission_chapter++;
$this_mission_levels = $mmrpg_mission_levels[$this_mission_chapter];
$this_mission_group_counters = $mmrpg_mission_group_counters[$this_mission_chapter];
$this_mission_button_sizes = $mmrpg_mission_button_sizes[$this_mission_chapter];
if (true){
    $button_order = 0;

    // vs MASTERS
    $button_order++;
    $level_start = $this_mission_levels[0];
    $level_limit = $level_start + (($this_mission_group_counters[0] - 1) * $this_mission_levels[1]);
    $button_size = '1x'.$this_mission_button_sizes[0];
    foreach ($mmrpg_omega_factors AS $omega_key => $omega_factor){

        $group_token = 'master-battle';
        $field_token = $omega_factor['omega_field'];
        $player_token = '';
        $omega_mechas = !empty($omega_factor['omega_mechas']) ? json_decode($omega_factor['omega_mechas'], true) : array();
        $target_player = '';
        $target_robots = array($omega_factor['omega_robot']);
        $target_mooks = array_values($omega_mechas);
        $field_types = array($omega_factor['omega_type']);
        $field_sources = array($field_token);
        mmrpg_insert_mission(array(
            'phase' => $this_mission_phase,
            'chapter' => $this_mission_chapter,
            'group' => $group_token,
            'field' => $field_token,
            'player' => $player_token,
            'target_player' => $target_player,
            'target_robots' => $target_robots,
            'target_mooks' => $target_mooks,
            'field_types' => $field_types,
            'field_sources' => $field_sources,
            'level_start' => $level_start,
            'level_limit' => $level_limit,
            'button_size' => $button_size,
            'button_order' => $button_order
            ));

    }

    // vs DOC-ROBOT
    $button_order++;
    foreach ($mmrpg_player_tokens AS $player_key => $player_token){

        $rival_token = $mmrpg_rival_tokens[$player_key];
        $rival_key = array_search($rival_token, $mmrpg_player_tokens);
        $final_key = isset($mmrpg_player_tokens[$rival_key + 1]) ? $rival_key + 1 : 0;

        $group_token = 'fortress-battle';
        $field_token = 'xxx-field';
        $target_player = '';
        $target_robots = array('doc-robot');
        $target_mooks = array();
        $field_types = array();
        $field_sources = array($field_token);
        $level_start = $this_mission_levels[2];
        $button_size = '1x'.$this_mission_button_sizes[2];
        mmrpg_insert_mission(array(
            'phase' => $this_mission_phase,
            'chapter' => $this_mission_chapter,
            'group' => $group_token,
            'field' => $field_token,
            'player' => $player_token,
            'target_player' => $target_player,
            'target_robots' => $target_robots,
            'target_mooks' => $target_mooks,
            'field_types' => $field_types,
            'field_sources' => $field_sources,
            'level_start' => $level_start,
            'button_size' => $button_size,
            'button_order' => $button_order
            ));

    }

}

// CHAPTER THREE / Rival Battles
$this_mission_chapter++;
$this_mission_levels = $mmrpg_mission_levels[$this_mission_chapter];
$this_mission_group_counters = $mmrpg_mission_group_counters[$this_mission_chapter];
$this_mission_button_sizes = $mmrpg_mission_button_sizes[$this_mission_chapter];
foreach ($mmrpg_player_tokens AS $player_key => $player_token){
    $button_order = 0;

    $rival_token = $mmrpg_rival_tokens[$player_key];
    $rival_key = array_search($rival_token, $mmrpg_player_tokens);
    $final_key = isset($mmrpg_player_tokens[$rival_key + 1]) ? $rival_key + 1 : 0;

    // vs RIVALS
    $button_order++;
    $group_token = 'rival-battle';
    $field_token = $mmrpg_player_field_tokens[$rival_key];
    $target_player = $rival_token;
    $target_robots = array($mmrpg_player_robot_master_tokens[$rival_key], $mmrpg_player_target_mooks_tokens[$rival_key]);
    $target_mooks = array();
    $field_types = array();
    $field_sources = array($field_token);
    $level_start = $this_mission_levels[0];
    $button_size = '1x'.$this_mission_button_sizes[0];
    mmrpg_insert_mission(array(
        'phase' => $this_mission_phase,
        'chapter' => $this_mission_chapter,
        'group' => $group_token,
        'field' => $field_token,
        'player' => $player_token,
        'target_player' => $target_player,
        'target_robots' => $target_robots,
        'target_mooks' => $target_mooks,
        'field_types' => $field_types,
        'field_sources' => $field_sources,
        'level_start' => $level_start,
        'button_size' => $button_size,
        'button_order' => $button_order
        ));

    // vs KILLERS
    $button_order++;
    $group_token = 'killer-battle';
    $field_token = 'xxx-field';
    $target_player = '';
    $target_robots = array($mmrpg_killer_robot_tokens[$player_key], 'quint');
    $target_mooks = array();
    $field_types = array();
    $field_sources = array($field_token);
    $level_start = $this_mission_levels[1];
    $button_size = '1x'.$this_mission_button_sizes[1];
    mmrpg_insert_mission(array(
        'phase' => $this_mission_phase,
        'chapter' => $this_mission_chapter,
        'group' => $group_token,
        'field' => $field_token,
        'player' => $player_token,
        'target_player' => $target_player,
        'target_robots' => $target_robots,
        'target_mooks' => $target_mooks,
        'field_types' => $field_types,
        'field_sources' => $field_sources,
        'level_start' => $level_start,
        'button_size' => $button_size,
        'button_order' => $button_order
        ));

    // vs ALIENS
    $button_order++;
    $group_token = 'alien-battle';
    $field_token = 'xxx-field';
    $target_player = '';
    $target_robots = array('sunstar','trill_'.$mmrpg_player_stat_tokens[$final_key]);
    $target_mooks = array();
    $field_types = array();
    $field_sources = array($field_token);
    $level_start = $this_mission_levels[2];
    $button_size = '1x'.$this_mission_button_sizes[2];
    mmrpg_insert_mission(array(
        'phase' => $this_mission_phase,
        'chapter' => $this_mission_chapter,
        'group' => $group_token,
        'field' => $field_token,
        'player' => $player_token,
        'target_player' => $target_player,
        'target_robots' => $target_robots,
        'target_mooks' => $target_mooks,
        'field_types' => $field_types,
        'field_sources' => $field_sources,
        'level_start' => $level_start,
        'button_size' => $button_size,
        'button_order' => $button_order
        ));

}



// ------------------------------ //
// MISSION GENERATION : PHASE THREE
// ------------------------------ //

// PHASE 3 START
$this_mission_phase++;

// CHAPTER FOUR / Fusion Battles
$this_mission_chapter++;
$this_mission_levels = $mmrpg_mission_levels[$this_mission_chapter];
$this_mission_group_counters = $mmrpg_mission_group_counters[$this_mission_chapter];
$this_mission_button_sizes = $mmrpg_mission_button_sizes[$this_mission_chapter];
if (true){
    $button_order = 0;

    // vs MASTERS
    $button_order++;
    $level_start = $this_mission_levels[0];
    $level_limit = $level_start + (($this_mission_group_counters[0] - 1) * $this_mission_levels[1]);
    $button_size = '1x'.$this_mission_button_sizes[0];
    foreach ($mmrpg_omega_factors AS $omega_key1 => $omega_factor1){
        foreach ($mmrpg_omega_factors AS $omega_key2 => $omega_factor2){

            if ($omega_key1 === $omega_key2){ continue; }

            $group_token = 'fusion-battle';
            $player_token = '';
            $field1 = $omega_factor1['omega_field'];
            $field2 = $omega_factor2['omega_field'];
            $mechas1 = !empty($omega_factor1['omega_mechas']) ? json_decode($omega_factor1['omega_mechas'], true) : array();
            $mechas2 = !empty($omega_factor2['omega_mechas']) ? json_decode($omega_factor2['omega_mechas'], true) : array();
            $field_left = preg_replace('/^([a-z0-9]+)-([a-z0-9]+)$/i', '$1', $omega_factor1['omega_field']);
            $field_right = preg_replace('/^([a-z0-9]+)-([a-z0-9]+)$/i', '$2', $omega_factor2['omega_field']);
            $field_token = $field_left.'-'.$field_right;
            $target_player = '';
            $target_robots = array($omega_factor1['omega_robot'], $omega_factor2['omega_robot']);
            $target_mooks = array_filter(array_merge($mechas1, $mechas2));
            $field_types = array_filter(array($omega_factor1['omega_type'], $omega_factor2['omega_type']));
            $field_sources = array($omega_factor1['omega_field'], $omega_factor2['omega_field']);
            mmrpg_insert_mission(array(
                'phase' => $this_mission_phase,
                'chapter' => $this_mission_chapter,
                'group' => $group_token,
                'field' => $field_token,
                'player' => $player_token,
                'target_player' => $target_player,
                'target_robots' => $target_robots,
                'target_mooks' => $target_mooks,
                'field_types' => $field_types,
                'field_sources' => $field_sources,
                'level_start' => $level_start,
                'level_limit' => $level_limit,
                'button_size' => $button_size,
                'button_order' => $button_order
                ));

        }
        //break;
    }

    // vs KING + DOC-ROBOT
    $button_order++;
    foreach ($mmrpg_player_tokens AS $player_key => $player_token){

        $rival_token = $mmrpg_rival_tokens[$player_key];
        $rival_key = array_search($rival_token, $mmrpg_player_tokens);
        $final_key = isset($mmrpg_player_tokens[$rival_key + 1]) ? $rival_key + 1 : 0;

        $group_token = 'fortress-battle';
        $field_token = 'xxx-field';
        $target_player = '';
        $target_robots = array('king', 'doc-robot');
        $target_mooks = array();
        $field_types = array();
        $field_sources = array($field_token);
        $level_start = $this_mission_levels[2];
        $button_size = '1x'.$this_mission_button_sizes[2];
        mmrpg_insert_mission(array(
            'phase' => $this_mission_phase,
            'chapter' => $this_mission_chapter,
            'group' => $group_token,
            'field' => $field_token,
            'player' => $player_token,
            'target_player' => $target_player,
            'target_robots' => $target_robots,
            'target_mooks' => $target_mooks,
            'field_types' => $field_types,
            'field_sources' => $field_sources,
            'level_start' => $level_start,
            'button_size' => $button_size,
            'button_order' => $button_order
            ));

    }

}





// DEBUG
echo('<pre>$mmrpg_database_missions('.count($mmrpg_database_missions).') = '.print_r($mmrpg_database_missions, true).'</pre>');


// Truncate any robots currently in the database
$db->query('TRUNCATE TABLE mmrpg_index_missions');

// Loop through and insert these missions into the database
foreach ($mmrpg_database_missions AS $mission_key => $mission_info){

    $db->insert('mmrpg_index_missions', $mission_info);

}


exit();


// Generate page headers
$this_page_markup .= '<p style="margin-bottom: 10px;"><strong>$mmrpg_database_missions</strong><br />';
$this_page_markup .= 'Count:'.(!empty($mmrpg_database_missions) ? count($mmrpg_database_missions) : 0).'<br />';
//$this_page_markup .= '<pre>'.htmlentities(print_r($mmrpg_database_missions, true), ENT_QUOTES, 'UTF-8', true).'</pre><br />';
$this_page_markup .= '</p>';

$spreadsheet_mission_stats = array(); //mmrpg_spreadsheet_mission_stats();
$spreadsheet_mission_descriptions = array(); //mmrpg_spreadsheet_mission_descriptions();


// Sort the mission index based on mission number
$temp_pattern_first = array();
$temp_pattern_first[] = '/^dr-light$/i';
$temp_pattern_first[] = '/^dr-wily$/i';
$temp_pattern_first[] = '/^dr-cossack/i';
//$temp_pattern_first = array_reverse($temp_pattern_first);
$temp_pattern_last = array();
//$temp_pattern_last = array_reverse($temp_pattern_last);
// Sort the mission index based on mission number
function mmrpg_index_sort_missions($mission_one, $mission_two){
    // Pull in global variables
    global $temp_pattern_first, $temp_pattern_last;
    // Loop through all the temp patterns and compare them one at a time
    foreach ($temp_pattern_first AS $key => $pattern){
        // Check if either of these two missions matches the current pattern
        if (preg_match($pattern, $mission_one['mission_token']) && !preg_match($pattern, $mission_two['mission_token'])){ return -1; }
        elseif (!preg_match($pattern, $mission_one['mission_token']) && preg_match($pattern, $mission_two['mission_token'])){ return 1; }
    }
    foreach ($temp_pattern_last AS $key => $pattern){
        // Check if either of these two missions matches the current pattern
        if (preg_match($pattern, $mission_one['mission_token']) && !preg_match($pattern, $mission_two['mission_token'])){ return 1; }
        elseif (!preg_match($pattern, $mission_one['mission_token']) && preg_match($pattern, $mission_two['mission_token'])){ return -1; }
    }
    if ($mission_one['mission_game'] > $mission_two['mission_game']){ return 1; }
    elseif ($mission_one['mission_game'] < $mission_two['mission_game']){ return -1; }
    elseif ($mission_one['mission_token'] > $mission_two['mission_token']){ return 1; }
    elseif ($mission_one['mission_token'] < $mission_two['mission_token']){ return -1; }
    elseif ($mission_one['mission_token'] > $mission_two['mission_token']){ return 1; }
    elseif ($mission_one['mission_token'] < $mission_two['mission_token']){ return -1; }
    else { return 0; }
}
uasort($mmrpg_database_missions, 'mmrpg_index_sort_missions');

// Loop through each of the mission info arrays
$mission_key = 0;
$mission_order = 0;
$temp_empty = $mmrpg_database_missions['mission'];
unset($mmrpg_database_missions['mission']);
array_unshift($mmrpg_database_missions, $temp_empty);
if (!empty($mmrpg_database_missions)){
    foreach ($mmrpg_database_missions AS $mission_token => $mission_data){

        // If this mission's image exists, assign it
        if (file_exists(MMRPG_CONFIG_ROOTDIR.'images/missions/'.$mission_token.'/')){ $mission_data['mission_image'] = $mission_data['mission_token']; }
        else { $mission_data['mission_image'] = 'mission'; }

        // Define the insert array and start populating it with basic details
        $temp_insert_array = array();
        //$temp_insert_array['mission_id'] = isset($mission_data['mission_id']) ? $mission_data['mission_id'] : $mission_key;
        $temp_insert_array['mission_token'] = $mission_data['mission_token'];
        $temp_insert_array['mission_number'] = !empty($mission_data['mission_number']) ? $mission_data['mission_number'] : '';
        $temp_insert_array['mission_name'] = !empty($mission_data['mission_name']) ? $mission_data['mission_name'] : '';
        $temp_insert_array['mission_game'] = !empty($mission_data['mission_game']) ? $mission_data['mission_game'] : '';
        $temp_insert_array['mission_group'] = !empty($mission_data['mission_group']) ? $mission_data['mission_group'] : '';

        $temp_insert_array['mission_class'] = !empty($mission_data['mission_class']) ? $mission_data['mission_class'] : 'mission';

        $temp_insert_array['mission_image'] = !empty($mission_data['mission_image']) ? $mission_data['mission_image'] : '';
        $temp_insert_array['mission_image_size'] = !empty($mission_data['mission_image_size']) ? $mission_data['mission_image_size'] : 40;
        $temp_insert_array['mission_image_editor'] = !empty($mission_data['mission_image_editor']) ? $mission_data['mission_image_editor'] : 0;
        $temp_insert_array['mission_image_alts'] = json_encode(!empty($mission_data['mission_image_alts']) ? $mission_data['mission_image_alts'] : array());

        $temp_insert_array['mission_type'] = !empty($mission_data['mission_type']) ? $mission_data['mission_type'] : '';
        $temp_insert_array['mission_type2'] = !empty($mission_data['mission_type2']) ? $mission_data['mission_type2'] : '';

        $temp_insert_array['mission_description'] = !empty($mission_data['mission_description']) ? trim($mission_data['mission_description']) : '';
        $temp_insert_array['mission_description2'] = !empty($mission_data['mission_description2']) ? trim($mission_data['mission_description2']) : '';

        $temp_insert_array['mission_energy'] = !empty($mission_data['mission_energy']) ? $mission_data['mission_energy'] : 0;
        $temp_insert_array['mission_weapons'] = !empty($mission_data['mission_weapons']) ? $mission_data['mission_weapons'] : 0;
        $temp_insert_array['mission_attack'] = !empty($mission_data['mission_attack']) ? $mission_data['mission_attack'] : 0;
        $temp_insert_array['mission_defense'] = !empty($mission_data['mission_defense']) ? $mission_data['mission_defense'] : 0;
        $temp_insert_array['mission_speed'] = !empty($mission_data['mission_speed']) ? $mission_data['mission_speed'] : 0;

        // Define the rewardss for this mission
        $temp_insert_array['mission_robots_rewards'] = json_encode(!empty($mission_data['mission_rewards']['robots']) ? $mission_data['mission_rewards']['robots'] : array());
        $temp_insert_array['mission_abilities_rewards'] = json_encode(!empty($mission_data['mission_rewards']['abilities']) ? $mission_data['mission_rewards']['abilities'] : array());

        // Define compatibilities for this mission
        $temp_insert_array['mission_robots_compatible'] = json_encode(!empty($mission_data['mission_robots_unlockable']) ? $mission_data['mission_robots_unlockable'] : array());
        $temp_insert_array['mission_abilities_compatible'] = json_encode(!empty($mission_data['mission_abilities']) ? $mission_data['mission_abilities'] : array());

        // Define the battle quotes for this mission
        if (!empty($mission_data['mission_quotes'])){ foreach ($mission_data['mission_quotes'] AS $key => $quote){ $mission_data['mission_quotes'][$key] = html_entity_decode($quote, ENT_QUOTES, 'UTF-8'); } }
        $temp_insert_array['mission_quotes_start'] = !empty($mission_data['mission_quotes']['battle_start']) && $mission_data['mission_quotes']['battle_start'] != '...' ? $mission_data['mission_quotes']['battle_start'] : '';
        $temp_insert_array['mission_quotes_taunt'] = !empty($mission_data['mission_quotes']['battle_taunt']) && $mission_data['mission_quotes']['battle_taunt'] != '...' ? $mission_data['mission_quotes']['battle_taunt'] : '';
        $temp_insert_array['mission_quotes_victory'] = !empty($mission_data['mission_quotes']['battle_victory']) && $mission_data['mission_quotes']['battle_victory'] != '...' ? $mission_data['mission_quotes']['battle_victory'] : '';
        $temp_insert_array['mission_quotes_defeat'] = !empty($mission_data['mission_quotes']['battle_defeat']) && $mission_data['mission_quotes']['battle_defeat'] != '...' ? $mission_data['mission_quotes']['battle_defeat'] : '';


        $temp_insert_array['mission_functions'] = !empty($mission_data['mission_functions']) ? $mission_data['mission_functions'] : 'missions/mission.php';

        // Collect applicable spreadsheets for this mission
        $spreadsheet_stats = !empty($spreadsheet_mission_stats[$mission_data['mission_token']]) ? $spreadsheet_mission_stats[$mission_data['mission_token']] : array();
        $spreadsheet_descriptions = !empty($spreadsheet_mission_descriptions[$mission_data['mission_token']]) ? $spreadsheet_mission_descriptions[$mission_data['mission_token']] : array();

        // Collect any user-contributed data for this mission
        if (!empty($spreadsheet_descriptions['mission_description'])){ $temp_insert_array['mission_description2'] = trim($spreadsheet_descriptions['mission_description']); }

        // Define the flags
        $temp_insert_array['mission_flag_hidden'] = in_array($temp_insert_array['mission_token'], array('mission')) ? 1 : 0;
        $temp_insert_array['mission_flag_complete'] = $mission_data['mission_image'] != 'mission' ? 1 : 0;
        $temp_insert_array['mission_flag_published'] = 1;

        // Define the order counter
        if ($temp_insert_array['mission_class'] != 'system'){
            $temp_insert_array['mission_order'] = $mission_order;
            $mission_order++;
        } else {
            $temp_insert_array['mission_order'] = 0;
        }


        // Check if this mission already exists in the database
        $temp_success = true;
        $temp_exists = $db->get_array("SELECT mission_token FROM mmrpg_index_missions WHERE mission_token LIKE '{$temp_insert_array['mission_token']}' LIMIT 1") ? true : false;
        if (!$temp_exists){ $temp_success = $db->insert('mmrpg_index_missions', $temp_insert_array); }
        else { $temp_success = $db->update('mmrpg_index_missions', $temp_insert_array, array('mission_token' => $temp_insert_array['mission_token'])); }

        // Print out the generated insert array
        $this_page_markup .= '<p style="margin: 2px auto; padding: 6px; background-color: '.($temp_success === false ? 'rgb(255, 218, 218)' : 'rgb(218, 255, 218)').';">';
        $this_page_markup .= '<strong>$mmrpg_database_missions['.$mission_token.']</strong><br />';
        //$this_page_markup .= '<pre>'.print_r($mission_data, true).'</pre><br /><hr /><br />';
        $this_page_markup .= '<pre>'.print_r($temp_insert_array, true).'</pre><br /><hr /><br />';
        //$this_page_markup .= '<pre>'.print_r(rpg_mission::parse_index_info($temp_insert_array), true).'</pre><br /><hr /><br />';
        $this_page_markup .= '</p><hr />';

        $mission_key++;

        //die('end');

    }
}
// Otherwise, if empty, we're done!
else {
    $this_page_markup .= '<p style="padding: 6px; background-color: rgb(218, 255, 218);"><strong>ALL ROBOT HAVE BEEN IMPORTED UPDATED!</strong></p>';
}

?>